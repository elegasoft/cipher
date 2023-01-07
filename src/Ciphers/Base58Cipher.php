<?php

namespace Elegasoft\Cipher\Ciphers;

use Elegasoft\Cipher\CharacterBases\Base58;

class Base58Cipher extends Cipher
{
    public function __construct(array $ciphers)
    {
        parent::__construct(new Base58, $ciphers);
    }
}
