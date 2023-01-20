<?php

use Elegasoft\Cipher\CharacterBases\Base16;
use Elegasoft\Cipher\CharacterBases\Base36;
use Elegasoft\Cipher\CharacterBases\Base58;
use Elegasoft\Cipher\CharacterBases\Base62;
use Elegasoft\Cipher\CharacterBases\Base96;
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
            'class'      => null,
        ],
        'base36' => [
            'characters' => Base36::class,
            'class'      => null,
        ],
        'base58' => [
            'characters' => Base58::class,
            'class'      => null,
        ],
        'base62' => [
            'characters' => Base62::class,
            'class'      => null,
        ],
        'base96' => [
            'characters' => Base96::class,
            'class'      => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cipher Keys
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many cipher keys "ciphers" as you wish, and you
    | may even configure multiple keys of the same characters. Keys should be
    | unique to the application. Each set of keys should contain an array
    | of random sequences of the allowed cipher characters in a randomly
    | generated sequence. While only one key set is required, it is
    | possible to define as many keys as you may deem necessary.
    |
    | Supported Ciphers: "base16", "base36", "base58", "base62", "base96"
    |
    */

    'keys' => [
        /*
         * A Base16 Cipher enciphers hexadecimal characters of numbers 0-9 and lowercase a-f.
         */
        'base16' => [
            '',
        ],

        /*
         * A Base36 Cipher enciphers alphanumeric characters of the numbers 0-9 and lowercase a-z.
         */
        'base36' => [
            '',
        ],

        /*
         * A Base58 Cipher enciphers alphanumeric characters of 1-9, a-z, and A-Z,
         * Except: uppercase I, lowercase L, uppercase O, and zero.
         */
        'base58' => [
            '',
        ],

        /*
         * A Base62 Cipher enciphers alphanumeric characters of 0-9, a-z, and A-Z.
         */
        'base62' => [
            '',
        ],

        /*
         * A Base96 Cipher enciphers the entire ASCII character set:
         *  - alphanumerics 0-9, a-z, and A-Z
         *  - symbols `~!@#$%^&*()-_=+[]{};':",./|\<>?
         *  - the space character
         */
        'Base96' => [
            '',
        ],
    ],
];
