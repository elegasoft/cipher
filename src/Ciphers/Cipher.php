<?php

namespace Elegasoft\Cipher\Ciphers;

use Elegasoft\Cipher\CharacterBases\CharacterBase;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;

class Cipher
{
    /**
     * @var \Elegasoft\Cipher\CharacterBases\CharacterBase
     */
    readonly public CharacterBase $characterBase;
    protected array $ciphers;

    protected int $cipherCount;
    readonly public string $cipherCharacters;
    protected ?string $previousCharacter = null;

    /**
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(CharacterBase $characterBase, array $ciphers)
    {
        $this->characterBase = $characterBase;
        $this->cipherCharacters = $characterBase->getCharacters();

        $this->ensureCipherKeysMatchKeyBase($ciphers);

        $this->setCiphers($ciphers);
    }

    protected function setCiphers(array $ciphers): void
    {
        $this->ciphers = $ciphers;
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

    private function shiftCipherByIndex(mixed $currentCipher, int $index): string
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

    /**
     * @param array $ciphers
     *
     * @throws \InvalidArgumentException
     * @return void
     */
    private function ensureCipherKeysMatchKeyBase(array $ciphers): void
    {
        $baseCharacters = mb_str_split($this->characterBase->getCharacters());
        $baseCount = $this->characterBase->getCharacterCount();

        foreach ($ciphers as $index => $cipher)
        {
            $count = strlen($cipher);
            $base = $this->characterBase::class;
            if ($count !== $baseCount)
            {
                throw new InvalidArgumentException("Cipher key length at index {$index} has {$count} characters and not the {$baseCount} expected by {$base}.");
            }

            if (!Str::containsAll($cipher, $baseCharacters))
            {
                throw new InvalidArgumentException("Cipher key at index {$index} has characters and not expected by {$base}.");
            }
        }
    }
}
