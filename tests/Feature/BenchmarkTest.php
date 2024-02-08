<?php

namespace Elegasoft\Cipher\Tests\Feature;

use Elegasoft\Cipher\Ciphers\Base62Cipher;

class BenchmarkTest
{
    public function benchmark($func, $input, $iterations): float|int
    {
        $start = microtime(true);
        for ($i = 0; $i < $iterations; $i++) {
            $func($i);
        }
        return microtime(true) - $start;
    }

    /**
     * @test
     *
     * @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::ciphers()
     */
    public function it_benchmarks_the_time($characters, $cipher): void
    {
        $cipherKeys = [
            str_shuffle($characters),
            str_shuffle($characters),
            str_shuffle($characters),
            str_shuffle($characters),
            str_shuffle($characters),
        ];

        $cipher = new $cipher($cipherKeys);

        $plainText = '1';
        $iterations = 1000;

        $cipherTest = function ($input) use ($cipher)
        {
            return $cipher->paddedEncipher($input, 8, 'a');
        };

// Config for AES-128-CBC
        $key = '3F451A69D7936124E8167A567586910A'; // Needs to be a 16 byte long key
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));

        $aesTest = function ($input) use ($key, $iv)
        {
            return openssl_encrypt($input, 'AES-256-CBC', $key, 0, $iv);
        };

        $cipherTime = $this->benchmark($cipherTest, $plainText, $iterations);
        $aesTime = $this->benchmark($aesTest, $plainText, $iterations);

        $this->assertLessThan($aesTime, $cipherTime);
    }
}