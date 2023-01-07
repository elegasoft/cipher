<?php

namespace Elegasoft\Cipher\CharacterBases;

class Base62 extends CharacterBase
{
    protected int $characterCount = 62;

    protected string $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
}
