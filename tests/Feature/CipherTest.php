<?php

namespace Elegasoft\Cipher\Tests\Feature;

use Elegasoft\Cipher\Ciphers\Cipher;
use Elegasoft\Cipher\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CipherTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        Artisan::call('cipher:generate-keys');
    }

    /**
     * @test
     *
     * @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::cipherStringsToEncrypt()
     */
    public function it_accurately_enciphers_and_deciphers_strings($data, $text): void
    {
        $cipher = $this->createCipher($data);

        $enciphered = $cipher->encipher($text);

        $deciphered = $cipher->decipher($enciphered);

        $this->checkResults($cipher, $text, $enciphered, $deciphered);
    }

    /**
     * @test
     *
     * @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::cipherStringsToEncrypt()
     */
    public function it_can_pad_enciphers($data, $text): void
    {
        $cipher = $this->createCipher($data);

        if (Str::startsWith($text, $paddingCharacter = '~')) {
            $this->expectException(\RuntimeException::class);
        }

        $enciphered = $cipher->paddedEncipher($text, 36, $paddingCharacter);

        $deciphered = $cipher->paddedDecipher($enciphered, $paddingCharacter);

        if (!Str::startsWith($text, $paddingCharacter)) {
            $this->checkResults($cipher, $text, $enciphered, $deciphered);
        }
    }

    /**
     * @test
     *
     * @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::cipherStringsToEncrypt()
     */
    public function it_rejects_multi_character_padded_enciphers($data, $text): void
    {
        $cipher = $this->createCipher($data);

        $this->expectException(\InvalidArgumentException::class);

        $cipher->paddedEncipher($text, 36, '**');
    }

    /**
     * @test
     *
     * @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::cipherStringsToEncrypt()
     */
    public function it_rejects_multi_character_padded_deciphers($data, $text): void
    {
        $cipher = $this->createCipher($data);

        $this->expectException(\InvalidArgumentException::class);

        $cipher->paddedDecipher($text, 36, '**');
    }

    /**
     * @test
     *
     * @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::cipherStringsToEncrypt()
     */
    public function it_can_reverse_enciphers($data, $text): void
    {
        $cipher = $this->createCipher($data);

        $enciphered = $cipher->reverseEncipher($text);

        $deciphered = $cipher->reverseDecipher($enciphered);

        if (Str::contains($text, str_split($cipher->characterBase->getCharacters()))) {
            $this->assertNotEquals($text, $enciphered,
                "Failed asserting that text '{$text}' is different from enciphered '{$enciphered}' using a character base of ".class_basename($cipher));

            $this->assertNotEquals($enciphered, $deciphered,
                "Failed asserting that enciphered '{$enciphered}' is different from deciphered '{$deciphered}' using a character base of ".class_basename($cipher));
        }
        $this->assertEquals($text, $deciphered,
            "Failed asserting that text '{$text}' is equal to deciphered '{$deciphered}' using a character base of ".class_basename($cipher));
    }

    /**
     * @throws \JsonException
     */
    private function createCipher($data): Cipher
    {
        return new Cipher(new $data['characterBase']);
    }

    private function checkResults(Cipher $cipher, $text, string $enciphered, string $deciphered)
    {
        if (Str::contains($text, str_split($cipher->characterBase->getCharacters()))) {
            $this->assertNotEquals($text, $enciphered,
                "Failed asserting that text '{$text}' is different from enciphered '{$enciphered}' using a character base of ".class_basename($cipher));
            $this->assertNotEquals($enciphered, $deciphered,
                "Failed asserting that enciphered '{$enciphered}' is different from deciphered '{$deciphered}' using a character base of ".class_basename($cipher));
        }
        $this->assertEquals($text, $deciphered,
            "Failed asserting that text '{$text}' is equal to deciphered '{$deciphered}' using a character base of ".class_basename($cipher));
    }
}
