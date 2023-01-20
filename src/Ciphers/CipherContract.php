<?php

namespace Elegasoft\Cipher\Ciphers;

interface CipherContract
{
    public function encipher(string $string): string;

    public function decipher(string $string): string;
}
