<?php

namespace Elegasoft\Cipher;

use Elegasoft\Cipher\CharacterBases\Base16;
use Elegasoft\Cipher\CharacterBases\Base36;
use Elegasoft\Cipher\CharacterBases\Base58;
use Elegasoft\Cipher\CharacterBases\Base62;
use Elegasoft\Cipher\CharacterBases\Base96;
use Illuminate\Contracts\Filesystem\FileExistsException;
use Illuminate\Support\Collection;

class KeyGenerator
{
    public function generateConfig(int $numKeys = 1)
    {
        if (file_exists(base_path('config/ciphers.php')))
        {
            throw new FileExistsException('Unable to generate new config file, a configuration file already exists.');
        }
        $self = new self();
        $stub = $self->getStub();
        $config = $self->setStubKeys($stub, $self->default($numKeys));

        file_put_contents(base_path('config/ciphers.php'), $config);
    }

    public function getStub(): string
    {
        return file_get_contents(__DIR__ . '/../stubs/config.stub');
    }

    protected function setStubKeys(string $stub, Collection $keys)
    {
        $keyValues = $this->getKeyStrings($keys);

        $keyBases = array_keys($keyValues);

        return str_replace($keyBases, $keyValues, $stub);
    }

    private function getKeyStrings(Collection $characterBases): array
    {
        return $characterBases
            ->mapWithKeys(function ($keylist, $class)
            {
                $keyString = implode(PHP_EOL, $keylist);
                $keyString = str_replace(['\'', PHP_EOL], ['\\\'', '\',' . PHP_EOL . '\''], $keyString);

                return [
                    class_basename($class) => '\'' . $keyString . '\'',
                ];
            })->toArray();
    }

    public function default($times = 1): Collection
    {
        return collect([
            new Base16(),
            new Base36(),
            new Base58(),
            new Base62(),
            new Base96(),
        ])->mapWithKeys(function ($characterBase) use ($times)
        {
            $keys = collect([])->times($times, function () use ($characterBase)
            {
                return str_shuffle($characterBase->getCharacters());
            });

            return [class_basename($characterBase) => $keys->toArray()];
        });
    }
}
