<?php

namespace Elegasoft\Cipher\Ciphers;

use Elegasoft\Cipher\CharacterBases\Base16;

class Base16Cipher extends Cipher
{
    public function __construct(?array $keys = null)
    {
        parent::__construct(new Base16, $keys);
    }
}
