<?php

namespace Elegasoft\Cipher\CharacterBases;

class Base16 extends CharacterBase
{
    protected int $characterCount = 16;

    protected string $characters = 'abcdef0123456789';
}
