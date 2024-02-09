<?php

namespace Elegasoft\Cipher\Ciphers;

use Elegasoft\Cipher\CharacterBases\CharacterBase;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use InvalidArgumentException;
use RuntimeException;

class Cipher implements CipherContract
{
    public CharacterBase $characterBase;

    protected string $strSoFar;

    protected array $keys;

    protected ?string $previousCharacter = null;

    /**
     * @throws \InvalidArgumentException|\JsonException
     */
    public function __construct(CharacterBase $characterBase, ?array $keys = null)
    {
        $this->setCharacterBase($characterBase);

        if (is_null($keys)) {
            $config = config('cipher.storage');
            $keyBase = class_basename($characterBase);
            $json = Storage::disk($config['disk'])
                           ->get($config['path'].DIRECTORY_SEPARATOR.$config['filename']);
            if (!empty($json)) {
                $keys = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
                $this->setKeys($keys[$keyBase]);
            }
        } elseif (count($keys)) {
            $this->setKeys($keys);
        }
    }

    protected function setKeys(array $keys): void
    {
        $this->ensureCipherKeysMatchKeyBase($keys);
        $this->keys = $keys;
    }

    public function keys(array $keys): static
    {
        $this->setKeys($keys);

        return $this;
    }

    public function encipher(string $string): string
    {
        $this->previousCharacter = null;
        $this->strSoFar = '';

        $stringCollection = collect(mb_str_split($string));

        return $stringCollection->map(function ($character, $index)
        {
            if (!Str::contains($this->characterBase->getCharacters(), $character)) {
                return $character;
            }
            $encipheredCharacter = $this->encipherCharacter($character, $index);
            $this->previousCharacter = $encipheredCharacter;
            $this->strSoFar .= $character;

            return $encipheredCharacter;
        })->implode('');
    }

    public function decipher(string $string): string
    {
        $this->previousCharacter = null;
        $this->strSoFar = '';

        $stringCollection = new Collection(mb_str_split($string));

        return $stringCollection->map(function ($encipheredCharacter, $index)
        {
            if (!Str::contains($this->characterBase->getCharacters(), $encipheredCharacter)) {
                return $encipheredCharacter;
            }
            $decipheredCharacter = $this->decipherCharacter($encipheredCharacter, $index);
            $this->previousCharacter = $encipheredCharacter;

            $this->strSoFar .= $decipheredCharacter;

            return $decipheredCharacter;
        })->implode('');
    }

    public function paddedEncipher(
        string $string,
        int $minOutputLength = 8,
        string $paddingCharacter = '0',
        bool $useReverse = true
    ): string {
        $this->checkPaddingCharacterLength($paddingCharacter);
        $this->confirmUsesSafePaddingCharacter($paddingCharacter, $string, $useReverse);
        $paddedString = Str::of($string)
                           ->padLeft($minOutputLength, $paddingCharacter)
                           ->when($useReverse, fn(Stringable $string) => $string->reverse());
        return $this->encipher($paddedString);
    }

    public function paddedDecipher(string $string, string $paddingCharacter = '0', bool $useReverse = true): string
    {
        $this->checkPaddingCharacterLength($paddingCharacter);
        $decipheredString = $this->decipher($string);
        return Str::of($decipheredString)
                  ->when($useReverse, fn(Stringable $string) => $string->reverse())
                  ->ltrim($paddingCharacter);
    }

    public function reverseEncipher($string): string
    {
        return $this->encipher(Str::of($string)->reverse());
    }

    public function reverseDecipher($string): string
    {
        return Str::of($this->decipher($string))->reverse();
    }

    private function encipherCharacter($character, $index): string
    {
        $currentCipher = $this->getCurrentCipher($index);
        $position = $this->getCharacterPosition($currentCipher, $character);

        return $this->getCharacterAtPosition($position, $this->characterBase->getCharacters());
    }

    protected function getCurrentCipher(int $index): string
    {
        if (!count($this->keys)) {
            throw new \RuntimeException('Missing cipher keys');
        }

        $cipherIndex = $index % count($this->keys);

        $currentCipher = Arr::shuffle($this->keys, $index)[$cipherIndex];

        if ($this->previousCharacter) {
            $currentCipher = $this->shiftCipher($currentCipher, $index);
        }

        return $currentCipher;
    }

    private function shiftCipher(mixed $currentCipher, int $index): string
    {
        $charPos = $this->getCharacterPosition($currentCipher, $this->previousCharacter ?? (string) $index);
        $strValue = $this->calcStringValue($index);
        $charPosValue = $this->calcStringValue($charPos);
        $splitOn = $charPos + $strValue;
        $character = str_split($currentCipher)[($splitOn + $index) % strlen($currentCipher)];
        [$first, $last] = explode($character, $currentCipher) + ['', ''];
        $switch = abs($charPosValue + ($strValue * $index) - $charPos) + $index;
        return $this->arrangeCharacters($switch % 24, $character, $first, $last);
    }

    private function arrangeCharacters(int $index, string $character, string $first, string $last): string
    {
        $range = intdiv($index, 8);
        $positions = [$first, $last, $character];
        array_splice($positions, $range, 0, array_splice($positions, 2, 1));
        $reverseIndex = $index % 8;
        if ($reverseIndex >= 2 && $reverseIndex <= 5) {
            $positions[0] = Str::reverse($positions[0]);
        }
        if ($reverseIndex >= 4) {
            $positions[1] = Str::reverse($positions[1]);
        }
        return implode('', $positions);
    }

    private function getCharacterAtPosition(int $position, string $currentCipher): string
    {
        return $currentCipher[$position];
    }

    private function decipherCharacter(string $encipheredCharacter, int $index): string
    {
        $currentCipher = $this->getCurrentCipher($index);

        $position = $this->getCharacterPosition($this->characterBase->getCharacters(), $encipheredCharacter);

        return $this->getCharacterAtPosition($position, $currentCipher);
    }

    private function getCharacterPosition(string $haystack, string $needle): int
    {
        return strpos($haystack, $needle);
    }

    /**
     * @param  array  $keys
     *
     * @throws \InvalidArgumentException
     * @return void
     *
     */
    private function ensureCipherKeysMatchKeyBase(array $keys): void
    {
        $baseCharacters = mb_str_split($this->characterBase->getCharacters());
        $baseCount = $this->characterBase->getCharacterCount();

        foreach ($keys as $index => $key) {
            $count = strlen($key);
            $base = $this->characterBase::class;
            if ($count !== $baseCount) {
                throw new InvalidArgumentException("Cipher key length at index $index has $count characters and not the $baseCount expected by $base.");
            }

            if (!Str::containsAll($key, $baseCharacters)) {
                throw new InvalidArgumentException("Cipher key at index $index has characters and not expected by $base.");
            }
        }
    }

    private function calcStringValue(int $index): int
    {
        $sum = 0;
        foreach (mb_str_split($this->strSoFar) as $char) {
            $pos = $this->getCharacterPosition($this->characterBase->getCharacters(), $char) ?? 0;
            $sum += $pos + ($index * 2);
        }

        return $sum;
    }

    public function setCharacterBase(CharacterBase|string $characterBase): static
    {
        if (is_string($characterBase)) {
            $characterBase = new $characterBase;
        }
        $this->characterBase = $characterBase;

        return $this;
    }

    private function confirmUsesSafePaddingCharacter(string $paddingCharacter, string $string, bool $useReverse): void
    {
        if ($useReverse ? Str::startsWith($string, $paddingCharacter) : Str::endsWith($string, $paddingCharacter)) {
            throw new RuntimeException("Deciphering this string will yield an incorrect results because the padding character of \"$paddingCharacter\" conflicts is already the last character of the string $string",
                E_USER_WARNING);
        }
    }

    private function checkPaddingCharacterLength(string $paddingCharacter): void
    {
        if (($length = strlen($paddingCharacter)) > 1) {
            throw new InvalidArgumentException("Padding Character should be a single character given character of $paddingCharacter has a length of $length");
        }
    }
}
