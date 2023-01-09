<?php

namespace Elegasoft\Cipher\Tests;

use Elegasoft\Cipher\Ciphers\Cipher;
use Elegasoft\Cipher\KeyGenerator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CipherTestCase extends TestCase
{
    use WithFaker;

    public Cipher $cipher;

    public array $testData;

    public int $testDataLength;

    public function setUp(): void
    {
        parent::setUp();

        if (!file_exists(base_path('config/ciphers.php')))
        {
            (new KeyGenerator())->generateConfig(10);
        }

        $this->setCipher();
    }

    /** @test */
    public function it_has_perfect_cipher_accuracy()
    {
        $this->setTestData();
        $data = Arr::random($this->testData, env('NUM_GENERATIONS', 5000));
        $total = count($data);
        foreach ($data as $text)
        {
            if ($text == '')
            {
                continue;
            }
            $enciphered = $this->cipher->encipher($text);

            $deciphered = $this->cipher->decipher($enciphered);

            if (Str::contains($text, str_split($this->cipher->cipherCharacters)))
            {
                $this->assertNotEquals($text, $enciphered, "Failed asserting that text '{$text}' is different from enciphered '{$enciphered}' using a character base of " . class_basename($this->cipher));
                $this->assertNotEquals($enciphered, $deciphered, "Failed asserting that enciphered '{$enciphered}' is different from deciphered '{$deciphered}' using a character base of " . class_basename($this->cipher));
            }
            $this->assertEquals($text, $deciphered, "Failed asserting that text '{$text}' is equal to deciphered '{$deciphered}' using a character base of " . class_basename($this->cipher));
        }
    }

    private function setTestData()
    {
        $filePath = storage_path() . '/testData/testdata.json';
        if (file_exists($filePath))
        {
            $this->testData = json_decode(file_get_contents($filePath), false, 512,
                JSON_THROW_ON_ERROR);
            $this->testDataLength = count($this->testData);

            return;
        }

        $this->testDataLength = 50000;

        $this->testData = Text::factory()
                              ->count($this->testDataLength)
                              ->make()
                              ->pluck('string')
                              ->toArray();

        if (!is_dir(storage_path('testData')))
        {
            mkdir(storage_path('testData'));
        }
        file_put_contents($filePath, json_encode($this->testData, JSON_THROW_ON_ERROR));
    }
}
