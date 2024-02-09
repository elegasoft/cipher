<?php

namespace Elegasoft\Cipher\Tests\Feature;

use Elegasoft\Cipher\Facades\Cipher;
use Elegasoft\Cipher\Tests\TestCase;

class CipherFacadeTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::cipherTypes
     */
    public function the_facade_accessor_works(string $characterBase, string $driver)
    {

        $cipher = Cipher::setCharacterBase($characterBase);

        $this->assertInstanceOf(\Elegasoft\Cipher\Ciphers\Cipher::class, $cipher);

        $this->assertInstanceOf($characterBase, $cipher->characterBase);
    }
}
