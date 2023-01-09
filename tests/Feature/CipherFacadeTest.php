<?php

namespace Elegasoft\Cipher\Tests\Feature;


use Elegasoft\Cipher\Facades\Cipher;

class CipherFacadeTest extends \Elegasoft\Cipher\Tests\TestCase
{
    /** @test */
    public function the_facade_accessor_works()
    {
        $enciphered = Cipher::encipher('test');

        $this->assertNotEquals('test', $enciphered);

        $deciphered = Cipher::decipher($enciphered);

        $this->assertEquals('test', $deciphered);
    }
}