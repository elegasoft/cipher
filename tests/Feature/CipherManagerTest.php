<?php

namespace Elegasoft\Cipher\Tests\Feature;

use Elegasoft\Cipher\CharacterBases\Base16;
use Elegasoft\Cipher\CharacterBases\Base96;
use Elegasoft\Cipher\CipherManager;
use Elegasoft\Cipher\Ciphers\Cipher;
use Elegasoft\Cipher\Ciphers\CipherContract;
use Elegasoft\Cipher\Tests\TestCipher;

class CipherManagerTest extends \Elegasoft\Cipher\Tests\TestCase
{
    /** @test  @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::cipherTypes */
    public function it_can_build_ciphers(string $characterBase, string $driver): void
    {
        $cipher = app(CipherManager::class, ['driver' => $driver]);

        $this->assertInstanceOf(Cipher::class, $cipher);
        $this->assertInstanceOf($characterBase, $cipher->characterBase);
    }

    /** @test  @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::cipherTypes */
    public function it_can_swap_character_bases(string $characterBase, string $driver): void
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
    public function it_can_be_used_fluently(string $characterBase, string $driver): void
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
    public function it_provides_a_default_cipher_base(): void
    {
        $cipher = app(CipherManager::class);

        $this->assertInstanceOf(Cipher::class, $cipher);

        $this->assertInstanceOf(Base96::class, $cipher->characterBase);
    }

    /** @test */
    public function it_uses_the_config_to_override_default_cipher_base(): void
    {
        config()->set('cipher.default', 'base16');

        $cipher = app(CipherManager::class);

        $this->assertInstanceOf(Cipher::class, $cipher);

        $this->assertInstanceOf(Base16::class, $cipher->characterBase);
    }

    /** @test */
    public function it_can_use_an_alternate_default_cipher_class(): void
    {
        config()->set('cipher.class', TestCipher::class);

        $cipher = CipherManager::make();

        $this->assertInstanceOf(TestCipher::class, $cipher);
    }
}