<?php

namespace Elegasoft\Cipher\CharacterBases;

abstract class CharacterBase
{
    protected int $characterCount;

    protected string $characters;

    public function getCharacters(): string
    {
        return $this->characters;
    }

    public function getCharacterCount(): int
    {
        return $this->characterCount;
    }
}
