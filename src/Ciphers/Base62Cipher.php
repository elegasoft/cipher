<?php

namespace Elegasoft\Cipher\Ciphers;

use Elegasoft\Cipher\CharacterBases\Base62;

class Base62Cipher extends Cipher
{
    public function __construct(?array $keys)
    {
        parent::__construct(new Base62, $keys ?? config('cipher.keys.base62'));
    }
}
