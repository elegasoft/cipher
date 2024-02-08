<?php

namespace Elegasoft\Cipher\Ciphers;

use Elegasoft\Cipher\CharacterBases\CharacterBase;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;

class Cipher implements CipherContract
{
    /**
     * @var \Elegasoft\Cipher\CharacterBases\CharacterBase
     */
    public CharacterBase $characterBase;

    protected string $strSoFar;

    protected array $ciphers;

    protected int $cipherCount;

    protected ?string $previousCharacter = null;

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(CharacterBase $characterBase, array $ciphers = [])
    {
        $this->setCharacterBase($characterBase);

        if (count($ciphers)) {
            $this->setCiphers($ciphers);
        }
    }

    protected function setCiphers(array $ciphers): void
    {
        $this->ensureCipherKeysMatchKeyBase($ciphers);
        $this->ciphers = $ciphers;
        $this->cipherCount = count($ciphers);
    }

    public function keys(array $keys): static
    {
        $this->setCiphers($keys);

        return $this;
    }

    public function encipher(string $string): string
    {
        $this->previousCharacter = null;
        $this->strSoFar = '';

        $stringCollection = collect(mb_str_split($string));

        $string = $stringCollection->map(function ($character, $index)
        {
            if (!Str::contains($this->characterBase->getCharacters(), $character)) {
                return $character;
            }
            $encipheredCharacter = $this->encipherCharacter($character, $index);
            $this->previousCharacter = $encipheredCharacter;
            $this->strSoFar .= $character;

            return $encipheredCharacter;
        })->implode('');

        return str_rot13($string);
    }

    public function decipher(string $string): string
    {
        $this->previousCharacter = null;
        $this->strSoFar = '';

        $stringCollection = new Collection(mb_str_split(str_rot13($string)));

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

    public function paddedEncipher(string $string, int $minOutputLength = 8, string $paddingCharacter = '0'): string
    {
        $paddedString = Str::of($string)->padLeft($minOutputLength, $paddingCharacter)->reverse();
        return $this->encipher($paddedString);
    }

    public function paddedDecipher(string $string, string $paddingCharacter = '0'): string
    {
        $decipheredString = $this->decipher($string);
        return Str::of($decipheredString)->reverse()->ltrim($paddingCharacter);
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
        if (!$this->cipherCount) {
            throw new \Exception(' Missing cipher keys');
        }

        $cipherIndex = $index % $this->cipherCount;

        $currentCipher = Arr::shuffle($this->ciphers, $index)[$cipherIndex];

        if ($this->previousCharacter) {
            $currentCipher = $this->shiftCipher($currentCipher, $index);
        }

        return $currentCipher;
    }

    private function shiftCipher(mixed $currentCipher, int $index): string
    {
        $charPos = $this->getCharacterPosition($currentCipher, $this->previousCharacter ?? $index);

        $strValue = $this->calcStringValue($index);

        $splitOn = $charPos + $strValue;

        $character = str_split($currentCipher)[$splitOn % strlen($currentCipher)];

        [$first, $last] = explode($character, $currentCipher) + ['', ''];

        return match ($strValue % 4) {
            0 => $character.$first.$last,
            1 => $last.$first.$character,
            2 => $character.Str::reverse($first).$last,
            3 => $last.Str::reverse($first).$character,
        };
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
     * @param  array  $ciphers
     *
     * @throws \InvalidArgumentException
     * @return void
     *
     */
    private function ensureCipherKeysMatchKeyBase(array $ciphers): void
    {
        $baseCharacters = mb_str_split($this->characterBase->getCharacters());
        $baseCount = $this->characterBase->getCharacterCount();

        foreach ($ciphers as $index => $cipher) {
            $count = strlen($cipher);
            $base = $this->characterBase::class;
            if ($count !== $baseCount) {
                throw new InvalidArgumentException("Cipher key length at index {$index} has {$count} characters and not the {$baseCount} expected by {$base}.");
            }

            if (!Str::containsAll($cipher, $baseCharacters)) {
                throw new InvalidArgumentException("Cipher key at index {$index} has characters and not expected by {$base}.");
            }
        }
    }

    private function calcStringValue(int $index): int
    {
        $sum = 0;
        foreach (mb_str_split($this->strSoFar) as $char) {
            $pos = $this->getCharacterPosition($this->characterBase->getCharacters(), $char) ?? 0;
            $pos = (int) $pos;
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
}
