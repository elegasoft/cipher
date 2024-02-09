<?php

namespace Elegasoft\Cipher\Ciphers;

use Elegasoft\Cipher\CharacterBases\Base36;

class Base36Cipher extends Cipher
{
    public function __construct(?array $keys = null)
    {
        parent::__construct(new Base36, $keys);
    }
}
