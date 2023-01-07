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

    public Cipher $encoder;

    public Cipher $decoder;

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
            $encoded = $this->encoder->encode($text);

            $decoded = $this->decoder->decode($encoded);

            if (Str::contains($text, str_split($this->encoder->cipherCharacters)))
            {
                $this->assertNotSame($text, $encoded, "Failed asserting that text '{$text}' is different from encoded '{$encoded}' using a character base of " . class_basename($this->encoder));
                $this->assertNotSame($encoded, $decoded, "Failed asserting that encoded '{$encoded}' is different from decoded '{$decoded}' using a character base of " . class_basename($this->encoder));
            }
            $this->assertSame($text, $decoded, "Failed asserting that text '{$text}' is equal to decoded '{$decoded}' using a character base of " . class_basename($this->encoder));
        }
    }

    private function setTestData()
    {
        $filePath = storage_path('testData/testdata.json');
        if (file_exists($filePath))
        {
            $this->testData = json_decode(file_get_contents(storage_path('testData/testdata.json')), false, 512,
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
        file_put_contents(storage_path('testData/testdata.json'), json_encode($this->testData, JSON_THROW_ON_ERROR));
    }
}
