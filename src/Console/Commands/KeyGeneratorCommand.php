<?php

namespace Elegasoft\Cipher\Console\Commands;

use Elegasoft\Cipher\KeyGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command as CommandAlias;
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
            $numKeys = 5;
        } else {

            $numKeys = text('How many keys per KeyBase?', '5', 5, false, 'int');
        }

        $keyGenerator = new KeyGenerator();
        $keys = $keyGenerator->generate([], $numKeys);

        $storage = config('cipher.storage');

        Storage::disk($storage['disk'])
               ->put($storage['path'].DIRECTORY_SEPARATOR.$storage['filename'], $keys->toJson());

        return CommandAlias::SUCCESS;
    }
}
