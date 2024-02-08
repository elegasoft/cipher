<?php

namespace Elegasoft\Cipher\Tests;

use Elegasoft\Cipher\CipherManager;
use Elegasoft\Cipher\Ciphers\Cipher;
use Elegasoft\Cipher\Ciphers\CipherContract;

class CipherManagerTest extends \Elegasoft\Cipher\Tests\TestCase
{
    /** @test  @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::cipherTypes */
    public function it_can_build_ciphers(string $characterBase, string $driver)
    {
        $cipher = app(CipherManager::class, ['driver' => $driver]);

        $this->assertInstanceOf(Cipher::class, $cipher);
        $this->assertInstanceOf($characterBase, $cipher->characterBase);
    }

    /** @test  @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::cipherTypes */
    public function it_can_swap_character_bases(string $characterBase, string $driver)
    {
        $characters = (new $characterBase)->getCharacters();
        $keys = [str_shuffle($characters)];
        $cipher = app(CipherManager::class);
        $cipher
            ->setCharacterBase(new $characterBase);

        $this->assertInstanceOf($characterBase, $cipher->characterBase);

        $cipher
            ->setCharacterBase($characterBase);

        $this->assertInstanceOf($characterBase, $cipher->characterBase);
    }

    /** @test  @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::cipherTypes */
    public function it_can_swap_keys(string $characterBase, string $driver): void
    {
        $characters = (new $characterBase)->getCharacters();
        $keys = [str_shuffle($characters)];
        $cipher = app(CipherManager::class);
        $cipher->setCharacterBase($characterBase);
        $cipher
            ->keys($keys);
        $enciphered = $cipher->encipher($text = 'sample');
        $this->assertInstanceOf(Cipher::class, $cipher);
        $this->assertNotEquals($text, $enciphered);
    }

    /** @test  @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::cipherTypes */
    public function it_can_be_used_fluently(string $characterBase, string $driver)
    {
        $characters = (new $characterBase)->getCharacters();
        $keys = [str_shuffle($characters)];
        $cipher = app(CipherManager::class);
        $cipher
            ->setCharacterBase(new $characterBase)
            ->keys($keys);

        $enciphered = $cipher->encipher($text = 'test');

        $this->assertInstanceOf(Cipher::class, $cipher);
        $this->assertNotEquals($text, $enciphered);
    }

    /** @test */
    public function it_provides_a_default_cipher()
    {
        config()->set('ciphers.default', 'base96');
        $cipher = app(CipherManager::class);

        $this->assertInstanceOf(Cipher::class, $cipher);
    }

    /** @test */
    public function it_can_use_an_alternate_default_cipher_class()
    {
        $this->markTestSkipped('Overriding Config during runtime needs work');
        config()->set('ciphers.class', TestCipher::class);

        $defaultClass = config('ciphers.class');

        $cipher = CipherManager::make();

        $this->assertInstanceOf(TestCipher::class, $cipher);
    }
}

class AlternativeCipher implements CipherContract
{
    public function encipher(string $string): string
    {
        return '';
    }

    public function decipher(string $string): string
    {
        return '';
    }
}
