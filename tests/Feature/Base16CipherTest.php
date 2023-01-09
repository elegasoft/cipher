<?php

namespace Elegasoft\Cipher\Tests\Feature;

use Elegasoft\Cipher\Ciphers\Base16Cipher;
use Elegasoft\Cipher\Tests\CipherTestCase;

class Base16CipherTest extends CipherTestCase
{
    public function setCipher(): void
    {
        $this->cipher = new Base16Cipher(config('ciphers.keys.base16'));
    }
}
