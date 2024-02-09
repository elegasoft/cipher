<?php

namespace Elegasoft\Cipher;

use Illuminate\Support\Collection;

class KeyGenerator
{
    public function generate(array $bases = [], int $numberOfKeys = 5): Collection
    {
        if (empty($bases)) {
            $bases = $this->getKeyBases();
        }

        if (is_array($bases)) {
            $bases = collect($bases);
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

    public function randomizeKeyBases(Collection $bases, int $times = 1): Collection
    {
        return $bases->mapWithKeys(function ($characterBase) use ($times)
        {
            $keys = collect([])->times($times, function () use ($characterBase)
            {
                return str_shuffle($characterBase->getCharacters());
            });

            return [class_basename($characterBase) => $keys->toArray()];
        });
    }

    private function getKeyBases(): Collection
    {
        return collect(config('cipher.ciphers'))->map(fn($base) => new $base['characters']);
    }
}
