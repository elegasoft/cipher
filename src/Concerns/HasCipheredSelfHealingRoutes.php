<?php

namespace Elegasoft\Cipher\Concerns;

use Elegasoft\Cipher\Ciphers\Base62Cipher;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait HasCipheredSelfHealingRoutes
{
    /**
     * @throws HttpResponseException
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $cipheredKey = last(explode('-', $value));
        $key = $this->decipherKey($cipheredKey);

        $model = parent::resolveRouteBinding($key, $field);

        if (!$model || $model->getRouteKey() === $value) {
            return $model;
        }

        throw new HttpResponseException(
            redirect()->route(Route::currentRouteName(), $model)
        );
    }

    public function decipherKey(string $cipheredKey): string|int
    {
        $decipheredKey = $this->getKeyCipher()->decipher($cipheredKey);

        return ltrim(strrev($decipheredKey), $this->getKeyPaddingCharacter());
    }

    public function getKeyCipher(): Base62Cipher
    {
        return new Base62Cipher(config('cipher.keys.base62'));
    }

    public function getKeyPaddingCharacter(): string
    {
        return 'z';
    }

    public function encipherKey(string|int $key): string
    {
        $paddedKey = Str::padLeft($key, 8, $this->getKeyPaddingCharacter());

        return $this->getKeyCipher()->paddedEncipher(strrev($paddedKey));
    }
}
