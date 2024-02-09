<?php

namespace Elegasoft\Cipher\Ciphers;

interface CipherContract
{
    public function encipher(string $string): string;

    public function decipher(string $string): string;

    public function paddedEncipher(
        string $string,
        int $minOutputLength = 8,
        string $paddingCharacter = '0',
        bool $useReverse = true
    ): string;

    public function paddedDecipher(string $string, string $paddingCharacter = '0', bool $useReverse = true): string;

    public function reverseEncipher(string $string): string;

    public function reverseDecipher(string $string): string;
}
