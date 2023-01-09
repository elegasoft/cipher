<?php

namespace Elegasoft\Cipher\Tests\Feature;

use Elegasoft\Cipher\Ciphers\Base95Cipher;

class Base95CipherTest extends \Elegasoft\Cipher\Tests\CipherTestCase
{
    public function setCipher(): void
    {
        $this->cipher = new Base95Cipher(config('ciphers.keys.base95'));
    }
}
