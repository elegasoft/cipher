<?php

namespace Elegasoft\Cipher\Tests\Feature;

use Elegasoft\Cipher\Tests\TestCase;
use Illuminate\Support\Str;

class CipherTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::cipherStringsToEncrypt()
     */
    public function it_accurately_enciphers_and_deciphers_strings($data, $text): void
    {
        $cipherKeys = [
            str_shuffle($data['characters']),
            str_shuffle($data['characters']),
            str_shuffle($data['characters']),
            str_shuffle($data['characters']),
            str_shuffle($data['characters']),
        ];

        $cipher = new $data['cipher']($cipherKeys);

        $enciphered = $cipher->encipher($text);

        $deciphered = $cipher->decipher($enciphered);

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
     * @test
     *
     * @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::cipherStringsToEncrypt()
     */
    public function it_can_pad_enciphers($data, $text): void
    {
        $cipherKeys = [
            str_shuffle($data['characters']),
            str_shuffle($data['characters']),
            str_shuffle($data['characters']),
            str_shuffle($data['characters']),
            str_shuffle($data['characters']),
        ];

        $cipher = new $data['cipher']($cipherKeys);

        $enciphered = $cipher->paddedEncipher($text, 36);

        $deciphered = $cipher->paddedDecipher($enciphered);

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
     * @test
     *
     * @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::cipherStringsToEncrypt()
     */
    public function it_can_multi_character_pad_enciphers($data, $text): void
    {
        $cipherKeys = [
            str_shuffle($data['characters']),
            str_shuffle($data['characters']),
            str_shuffle($data['characters']),
            str_shuffle($data['characters']),
            str_shuffle($data['characters']),
        ];

        $cipher = new $data['cipher']($cipherKeys);

        $enciphered = $cipher->paddedEncipher($text, 36, '*^');

        $deciphered = $cipher->paddedDecipher($enciphered, '*^');

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
     * @test
     *
     * @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::cipherStringsToEncrypt()
     */
    public function it_can_reverse_enciphers($data, $text): void
    {
        $cipherKeys = [
            str_shuffle($data['characters']),
            str_shuffle($data['characters']),
            str_shuffle($data['characters']),
            str_shuffle($data['characters']),
            str_shuffle($data['characters']),
        ];

        $cipher = new $data['cipher']($cipherKeys);

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
}
