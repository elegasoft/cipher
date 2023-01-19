<?php

namespace Elegasoft\Cipher\Ciphers;

use Elegasoft\Cipher\CharacterBases\Base96;

class Base96Cipher extends Cipher
{
    public function __construct(array $ciphers)
    {
        $ciphers = $ciphers ?? config('ciphers.keys.base96');
        parent::__construct(new Base96, $ciphers);
    }
}
