<?php

namespace Elegasoft\Cipher;

use Elegasoft\Cipher\CharacterBases\Base16;
use Elegasoft\Cipher\CharacterBases\Base34;
use Elegasoft\Cipher\CharacterBases\Base36;
use Elegasoft\Cipher\CharacterBases\Base54;
use Elegasoft\Cipher\CharacterBases\Base58;
use Elegasoft\Cipher\CharacterBases\Base62;
use Elegasoft\Cipher\CharacterBases\Base96;
use Elegasoft\Cipher\CharacterBases\CharacterBase;
use Elegasoft\Cipher\Ciphers\Cipher;
use Elegasoft\Cipher\Ciphers\CipherContract;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Manager;

class CipherManager extends Manager
{
    public static function make(): CipherContract|Cipher
    {
        return app(self::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultDriver()
    {
        return config('cipher.default', 'base96');
    }

    public function cipher($cipher = null, array $cipherKeys = [], ?string $cipherClass = null): CipherContract|Cipher
    {
        return $this->driver($cipher, $cipherKeys, $cipherClass);
    }

    public function createCipher(
        CharacterBase $characterBase,
        array $cipherKeys = [],
        ?string $cipherClass = null
    ): Cipher {
        $cipherClass = $cipherClass ?? Config::get('cipher.class');

        return new $cipherClass($characterBase, $cipherKeys);
    }

    public function createBase16Driver(array $cipherKeys = [], ?string $cipherClass = null): Cipher
    {
        return $this->createCipher(new Base16, $cipherKeys, $cipherClass ?? config('cipher.cipher.base16.class'));
    }

    public function createBase34Driver(array $cipherKeys = [], ?string $cipherClass = null): Cipher
    {
        return $this->createCipher(new Base34, $cipherKeys, $cipherClass ?? config('cipher.cipher.base34.class'));
    }

    public function createBase36Driver(array $cipherKeys = [], ?string $cipherClass = null): Cipher
    {
        return $this->createCipher(new Base36, $cipherKeys, $cipherClass ?? config('cipher.cipher.base36.class'));
    }

    public function createBase54Driver(array $cipherKeys = [], ?string $cipherClass = null): Cipher
    {
        return $this->createCipher(new Base54, $cipherKeys, $cipherClass ?? config('cipher.cipher.base54.class'));
    }

    public function createBase58Driver(array $cipherKeys = [], ?string $cipherClass = null): Cipher
    {
        return $this->createCipher(new Base58, $cipherKeys, $cipherClass ?? config('cipher.cipher.base58.class'));
    }

    public function createBase62Driver(array $cipherKeys = [], ?string $cipherClass = null): Cipher
    {
        return $this->createCipher(new Base62, $cipherKeys, $cipherClass ?? config('cipher.cipher.base62.class'));
    }

    public function createBase96Driver(array $cipherKeys = [], ?string $cipherClass = null): Cipher
    {
        return $this->createCipher(new Base96, $cipherKeys, $cipherClass ?? config('cipher.cipher.base96.class'));
    }
}
