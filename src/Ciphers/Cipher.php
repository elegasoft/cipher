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

    public function encipher(string $string): string
    {
        $this->previousCharacter = null;
        $stringCollection = collect(mb_str_split($string));

        $string = $stringCollection->map(function ($character, $index)
        {
            if (!Str::contains($this->cipherCharacters, $character))
            {
                return $character;
            }
            $encipheredCharacter = $this->encipherCharacter($character, $index);
            $this->previousCharacter = $encipheredCharacter;

            return $encipheredCharacter;
        })->implode('');

        return str_rot13($string);
    }

    private function encipherCharacter($character, $index): string
    {
        $currentCipher = $this->getCurrentCipher($index);
        $position = strpos($currentCipher, $character);

        return $this->getCipherCharacterAtPosition($position, $this->cipherCharacters);
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
        $splitOn = strpos($currentCipher, $this->previousCharacter ?? $index);
        $character = str_split($currentCipher)[$splitOn % strlen($currentCipher)];

        [$first, $last] = explode($character, $currentCipher) + ['', ''];

        return $character . $last . $first;
    }

    private function getCipherCharacterAtPosition(int $position, string $currentCipher): string
    {
        return mb_str_split($currentCipher)[$position];
    }

    public function decipher(string $string): string
    {
        $this->previousCharacter = null;
        $stringCollection = new Collection(mb_str_split(str_rot13($string)));

        return $stringCollection->map(function ($encipheredCharacter, $index)
        {
            if (!Str::contains($this->cipherCharacters, $encipheredCharacter))
            {
                return $encipheredCharacter;
            }
            $decipheredCharacter = $this->decipherCharacter($encipheredCharacter, $index);
            $this->previousCharacter = $encipheredCharacter;

            return $decipheredCharacter;
        })->implode('');
    }

    private function decipherCharacter(string $encipheredCharacter, int $index): string
    {
        $currentCipher = $this->getCurrentCipher($index);

        $position = strpos($this->cipherCharacters, $encipheredCharacter);

        return $this->getCipherCharacterAtPosition($position, $currentCipher);
    }
}
