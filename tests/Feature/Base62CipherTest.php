<?php

namespace Elegasoft\Cipher\Tests\Feature;

use Elegasoft\Cipher\Ciphers\Base62Cipher;

class Base62CipherTest extends \Elegasoft\Cipher\Tests\CipherTestCase
{
    public function setCipher(): void
    {
        $this->encoder = new Base62Cipher(config('ciphers.keys.base62'));
        $this->decoder = new Base62Cipher(config('ciphers.keys.base62'));
    }
}
