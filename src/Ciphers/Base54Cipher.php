<?php

namespace Elegasoft\Cipher\Ciphers;

use Elegasoft\Cipher\CharacterBases\Base54;

class Base54Cipher extends Cipher
{
    public function __construct(array $ciphers)
    {
        $ciphers = $ciphers ?? config('ciphers.keys.base54');
        parent::__construct(new Base54, $ciphers);
    }
}
