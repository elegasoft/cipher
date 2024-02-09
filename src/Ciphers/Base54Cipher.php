<?php

namespace Elegasoft\Cipher\Ciphers;

use Elegasoft\Cipher\CharacterBases\Base54;

class Base54Cipher extends Cipher
{
    public function __construct(?array $keys = null)
    {
        parent::__construct(new Base54, $keys);
    }
}
