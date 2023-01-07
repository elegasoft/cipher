<?php

namespace Elegasoft\Cipher\Ciphers;

use Elegasoft\Cipher\CharacterBases\Base95;

class Base95Cipher extends Cipher
{
    public function __construct(array $ciphers)
    {
        parent::__construct(new Base95, $ciphers);
    }
}
