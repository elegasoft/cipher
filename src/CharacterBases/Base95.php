<?php

namespace Elegasoft\Cipher\CharacterBases;

class Base95 extends CharacterBase
{
    protected int $characterCount = 95;

    protected string $characters = 'abcdefghijklmnopqrstuvwxyz`~!@#$%^&*()-_=+[]\{}|;\':",./<>? ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
}
