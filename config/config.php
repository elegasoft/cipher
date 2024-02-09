<?php

use Elegasoft\Cipher\CharacterBases\Base16;
use Elegasoft\Cipher\CharacterBases\Base34;
use Elegasoft\Cipher\CharacterBases\Base36;
use Elegasoft\Cipher\CharacterBases\Base54;
use Elegasoft\Cipher\CharacterBases\Base58;
use Elegasoft\Cipher\CharacterBases\Base62;
use Elegasoft\Cipher\CharacterBases\Base96;
use Elegasoft\Cipher\Ciphers\Base16Cipher;
use Elegasoft\Cipher\Ciphers\Base34Cipher;
use Elegasoft\Cipher\Ciphers\Base36Cipher;
use Elegasoft\Cipher\Ciphers\Base54Cipher;
use Elegasoft\Cipher\Ciphers\Base58Cipher;
use Elegasoft\Cipher\Ciphers\Base62Cipher;
use Elegasoft\Cipher\Ciphers\Base96Cipher;
use Elegasoft\Cipher\Ciphers\Cipher;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cipher Characters Set
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default Cipher characters that should be used
    | by the plugin. The "base96" cipher, as well as a variety of alternate
    | cipher character bases are available to your application.
    |
    */

    'default' => env('CIPHER_DEFAULT', 'base96'),

    /*
    |--------------------------------------------------------------------------
    | Default Cipher Class
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default Cipher class that should be instantiated
    | by the plugin. The default class, as well as any alternate cipher class
    | may be provided as an alternative to the default cipher provided here.
    |
    */

    'class'   => Cipher::class,

    /*
    |--------------------------------------------------------------------------
    | Ciphers
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "ciphers" as you wish, and you
    | may even configure multiple ciphers of the same characters. Defaults have
    | been set up for each cipher as an example of the required values.
    |
    | Supported Ciphers: "base16", "base36", "base58", "base62", "base96"
    |
    */
    'ciphers' => [
        'base16' => [
            'characters' => Base16::class,
            'class'      => Base16Cipher::class,
        ],
        'base34' => [
            'characters' => Base34::class,
            'class'      => Base34Cipher::class,
        ],
        'base36' => [
            'characters' => Base36::class,
            'class'      => Base36Cipher::class,
        ],
        'base54' => [
            'characters' => Base54::class,
            'class'      => Base54Cipher::class,
        ],
        'base58' => [
            'characters' => Base58::class,
            'class'      => Base58Cipher::class,
        ],
        'base62' => [
            'characters' => Base62::class,
            'class'      => Base62Cipher::class,
        ],
        'base96' => [
            'characters' => Base96::class,
            'class'      => Base96Cipher::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cipher Keys Storage path
    |--------------------------------------------------------------------------
    |
    | Here you may configure the storage path for the cipher keys which will
    | be used for to encipher and decipher to the strings for each cipher.
    |
    */

    'storage' => [
        'disk'     => env('CIPHER_STORAGE_DISK', 'local'),
        'path'     => env('CIPHER_STORAGE_PATH', 'cipher'),
        'filename' => env('CIPHER_STORAGE_FILENAME', 'keys.json'),
    ],
];
