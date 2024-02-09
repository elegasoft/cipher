<?php

namespace Elegasoft\Cipher;

use Elegasoft\Cipher\CharacterBases\Base16;
use Elegasoft\Cipher\CharacterBases\Base34;
use Elegasoft\Cipher\CharacterBases\Base36;
use Elegasoft\Cipher\CharacterBases\Base54;
use Elegasoft\Cipher\CharacterBases\Base58;
use Elegasoft\Cipher\CharacterBases\Base62;
use Elegasoft\Cipher\CharacterBases\Base96;
use Illuminate\Support\Collection;

class KeyGenerator
{
    public function generate(array $bases = [], int $numberOfKeys = 5): Collection
    {
        if (empty($bases)) {
            $bases = $this->getKeyBases();
        }

        return $this->randomizeKeyBases($bases, $numberOfKeys);
    }

    private function getKeyStrings(Collection $characterBases): array
    {
        return $characterBases
            ->mapWithKeys(function ($keylist, $class)
            {
                $keyString = implode(PHP_EOL, $keylist);
                $keyString = str_replace(['\'', PHP_EOL], ['\\\'', '\','.PHP_EOL.'\''], $keyString);

                return [
                    class_basename($class) => '\''.$keyString.'\'',
                ];
            })->toArray();
    }

    public function randomizeKeyBases(array $bases, int $times = 1): Collection
    {
        return collect($bases)->mapWithKeys(function ($characterBase) use ($times)
        {
            $keys = collect([])->times($times, function () use ($characterBase)
            {
                return str_shuffle($characterBase->getCharacters());
            });

            return [class_basename($characterBase) => $keys->toArray()];
        });
    }

    private function getKeyBases(): array
    {
        return [
            new Base16(),
            new Base34(),
            new Base36(),
            new Base54(),
            new Base58(),
            new Base62(),
            new Base96(),
        ];
    }
}
