<?php

namespace Elegasoft\Cipher\Ciphers;

use Elegasoft\Cipher\CharacterBases\CharacterBase;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Cipher
{
    public array $ciphers;

    public int $cipherCount;
    public string $cipherCharacters;
    protected ?string $previousCharacter = null;

    public function __construct(CharacterBase $characterBase, array $ciphers)
    {
        $this->cipherCharacters = $characterBase->getCharacters();
        $this->setCiphers($ciphers);
    }

    private function setCiphers(array $ciphers): void
    {
        if (count($ciphers))
        {
            $this->ciphers = $ciphers;
        }

        $this->cipherCount = count($ciphers);
    }

    public function encode(string $string): string
    {
        $stringCollection = collect(mb_str_split($string));

        $string = $stringCollection->map(function ($character, $index)
        {
            if (!Str::contains($this->cipherCharacters, $character))
            {
                return $character;
            }
            $encodedCharacter = $this->cipherEncode($character, $index);
            $this->previousCharacter = $encodedCharacter;

            return $encodedCharacter;
        })->implode('');

        return str_rot13($string);
    }

    private function cipherEncode($character, $index): string
    {
        $currentCipher = $this->getCurrentCipher($index);
        $position = strpos($currentCipher, $character);

        return $this->getCharacterAtPosition($position, $this->cipherCharacters);
    }

    protected function getCurrentCipher(int $index): string
    {
        $cipherIndex = 0;

        if ($this->cipherCount > 1)
        {
            $cipherIndex = $index % $this->cipherCount;
        }

        $currentCipher = $this->ciphers[$cipherIndex];

        if ($this->previousCharacter)
        {
            $currentCipher = $this->shiftCipherByIndex($currentCipher, $index);
        }

        return $currentCipher;
    }

    private function shiftCipherByIndex(mixed $currentCipher, int $index)
    {
        $character = str_split($currentCipher)[$index % strlen($currentCipher)];

        [$first, $last] = explode($character, $currentCipher) + ['', ''];

        return $character . $last . $first;
    }

    private function getCharacterAtPosition(int $position, string $currentCipher): string
    {
        return mb_str_split($currentCipher)[$position];
    }

    public function decode(string $string): string
    {
        $stringCollection = new Collection(mb_str_split(str_rot13($string)));

        return $stringCollection->map(function ($character, $index)
        {
            if (!Str::contains($this->cipherCharacters, $character))
            {
                return $character;
            }
            $decodedCharacter = $this->cipherDecode($character, $index);
            $this->previousCharacter = $character;

            return $decodedCharacter;
        })->implode('');
    }

    private function cipherDecode($character, $index): string
    {
        $currentCipher = $this->getCurrentCipher($index);

        $position = strpos($this->cipherCharacters, $character);

        return $this->getCharacterAtPosition($position, $currentCipher);
    }
}
