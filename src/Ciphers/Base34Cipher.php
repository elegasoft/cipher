<?php

namespace Elegasoft\Cipher\Ciphers;

use Elegasoft\Cipher\CharacterBases\Base34;

class Base34Cipher extends Cipher
{
    public function __construct(array $ciphers)
    {
        $ciphers = $ciphers ?? config('ciphers.keys.base36');
        parent::__construct(new Base34, $ciphers);
    }
}
