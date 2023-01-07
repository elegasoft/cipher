<?php

namespace Elegasoft\Cipher\Tests\Feature;


use Elegasoft\Cipher\Facades\Cipher;

class CipherFacadeTest extends \Elegasoft\Cipher\Tests\TestCase
{
    /** @test */
    public function the_facade_accessor_works()
    {
        $test = Cipher::encode('test');

        $this->assertNotSame('test', $test);
    }
}