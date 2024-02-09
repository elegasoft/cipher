<?php

namespace Elegasoft\Cipher\Ciphers;

use Elegasoft\Cipher\CharacterBases\Base58;

class Base58Cipher extends Cipher
{
    public function __construct(?array $keys)
    {
        parent::__construct(new Base58, $keys ?? config('cipher.keys.base58'));
    }
}
