<?php

namespace Elegasoft\Cipher\Facades;

use Elegasoft\Cipher\CipherManager;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Elegasoft\Cipher\Ciphers\Cipher
 */
class Cipher extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CipherManager::class;
    }
}
