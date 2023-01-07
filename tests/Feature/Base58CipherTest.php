<?php

namespace Elegasoft\Cipher\Tests\Feature;

use Elegasoft\Cipher\Ciphers\Base58Cipher;

class Base58CipherTest extends \Elegasoft\Cipher\Tests\CipherTestCase
{
    public function setCipher(): void
    {
        $this->encoder = new Base58Cipher(config('ciphers.keys.base58'));
        $this->decoder = new Base58Cipher(config('ciphers.keys.base58'));
    }
}
