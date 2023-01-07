<?php

namespace Elegasoft\Cipher\Tests\Feature;

use Elegasoft\Cipher\Ciphers\Base36Cipher;
use Elegasoft\Cipher\Tests\CipherTestCase;

class Base36CipherTest extends CipherTestCase
{
    public function setCipher(): void
    {
        $this->encoder = new Base36Cipher(config('ciphers.keys.base36'));
        $this->decoder = new Base36Cipher(config('ciphers.keys.base36'));
    }
}
