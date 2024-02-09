<?php

namespace Elegasoft\Cipher\Ciphers;

use Elegasoft\Cipher\CharacterBases\Base96;

class Base96Cipher extends Cipher
{
    public function __construct(?array $keys)
    {
        parent::__construct(new Base96, $keys ?? config('cipher.keys.base96'));
    }
}
