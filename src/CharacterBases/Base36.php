<?php

namespace Elegasoft\Cipher\CharacterBases;

class Base36 extends CharacterBase
{
    protected int $characterCount = 36;

    protected string $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
}
