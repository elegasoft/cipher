<?php

namespace Elegasoft\Cipher\Ciphers;

use Elegasoft\Cipher\CharacterBases\Base34;

class Base34Cipher extends Cipher
{
    public function __construct(?array $keys)
    {
        parent::__construct(new Base34, $keys ?? config('cipher.keys.base36'));
    }
}
