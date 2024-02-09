<?php

namespace Elegasoft\Cipher\Console\Commands;

use Elegasoft\Cipher\KeyGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command as CommandAlias;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\text;

class KeyGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cipher:generate-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Cipher Key File';

    /**
     * Execute the console command.
     *
     * @throws \JsonException
     * @return int
     */
    public function handle(): int
    {
        if (app()->runningUnitTests()) {
            $keyBases = [];
            $numKeys = 5;
        } else {
            $keyBases = multiselect('Generate Keys for which key bases?', [
                null => 'All',
                ...collect(config('cipher.ciphers')->map(fn($value, $key) => $key))->toArray(),
            ]);
            $numKeys = text('How many keys per KeyBase?', '5', 5, false, 'int');
        }

        $keyGenerator = new KeyGenerator();
        $keys = $keyGenerator->generate(array_filter($keyBases), $numKeys);

        $storage = config('cipher.storage');

        $this->updateKeys($keys, $storage);

        return CommandAlias::SUCCESS;
    }

    /**
     * @throws \JsonException
     */
    private function updateKeys(Collection $keys, array $storage): void
    {
        $keysToUpdate = $this->getKeysToUpdate($keys, $storage);
        if ($keysToUpdate?->count()) {
            Storage::disk($storage['disk'])
                   ->put($storage['path'].DIRECTORY_SEPARATOR.$storage['filename'], $keys->toJson());
        }
    }

    /**
     * @throws \JsonException
     */
    private function getKeysToUpdate(Collection $keys, array $storage): Collection
    {
        $existingKeyData = Storage::disk($storage['disk'])
                                  ->get($storage['path'].DIRECTORY_SEPARATOR.$storage['filename']);

        if (empty($existingKeyData)) {
            return $keys;
        }

        $existingKeys = collect(json_decode($existingKeyData, true, 512, JSON_THROW_ON_ERROR));

        $diff = array_diff_key($existingKeys->toArray(), $keys->toArray());

        if (!count($diff)) {
            throw new \RuntimeException('Keys already existing, unable to overwrite existing keys');
        }

        return $keys->merge($existingKeys);
    }
}
