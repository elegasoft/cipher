<?php

namespace Elegasoft\Cipher\Console\Commands;

use Elegasoft\Cipher\KeyGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command as CommandAlias;

class KeyGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cipher:generate-keys --keys=5';

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
        $numKeys = $this->hasOption('keys') ? $this->option('keys') : 5;

        $keyGenerator = new KeyGenerator();
        $keys = $keyGenerator->generate([], $numKeys);

        $storage = config('cipher.storage');

        Storage::disk($storage['disk'])
               ->put($storage['path'].DIRECTORY_SEPARATOR.$storage['filename'], $keys->toJson());

        return CommandAlias::SUCCESS;
    }
}
