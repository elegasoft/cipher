<?php

namespace Elegasoft\Cipher\Tests\Feature;

use Elegasoft\Cipher\Ciphers\Base16Cipher;
use Elegasoft\Cipher\Tests\CipherTestCase;

class Base16CipherTest extends CipherTestCase
{
    public function setCipher(): void
    {
        $this->encoder = new Base16Cipher(config('ciphers.keys.base16'));
        $this->decoder = new Base16Cipher(config('ciphers.keys.base16'));
    }
}
