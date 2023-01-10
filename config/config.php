<?php

use Elegasoft\Cipher\Ciphers\Base96Cipher;

return [
    /**
     * This is the cipher instantiated when using the CipherFacade
     */
    'default' => Base96Cipher::class,

    /*
     * Keys should be unique to the application. Each set of keys
     * should contain an array of random sequences of the ciphers allowed
     * characters in a randomly generated sequence. While only one key set
     * is required, it is possible to define as many keys as you deem necessary.
     */

    'keys' => [
        /*
         * Base16Cipher enciphers hexadecimal characters of numbers 0-9 and lowercase a-f.
         */
        'base16' => [
            '',
        ],
        /*
         * Base36Cipher enciphers alphanumeric characters of the numbers 0-9 and lowercase a-z.
         */
        'base36' => [
            '',
        ],
        /*
         * Base58Cipher enciphers alphanumeric characters of 1-9, a-z, and A-Z,
         * Except: uppercase I, lowercase L, uppercase O, and zero.
         */
        'base58' => [
            '',
        ],
        /*
         * Base62Cipher enciphers alphanumeric characters of 0-9, a-z, and A-Z.
         */
        'base62' => [
            '',
        ],
        /*
         * Base96Cipher enciphers the entire ASCII character set:
         *  - alphanumerics 0-9, a-z, and A-Z
         *  - symbols `~!@#$%^&*()-_=+[]{};':",./|\<>?
         *  - the space character
         */
        'Base96' => [
            '',
        ],
    ],
];
