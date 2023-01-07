<?php

namespace Elegasoft\Cipher\CharacterBases;

class Base58 extends CharacterBase
{
    protected int $characterCount = 58;

    protected string $characters = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ123456789';
}
