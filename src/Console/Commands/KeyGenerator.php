<?php

namespace Elegasoft\Cipher\Console\Commands;

class KeyGenerator extends \Illuminate\Console\Command
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
     * @return mixed
     */
    public function handle()
    {
        $numKeys = $this->hasOption('keys') ? $this->option('keys') : 5;

        $keyGenerator = new \Elegasoft\Cipher\KeyGenerator();
        $keyGenerator->generateConfig($numKeys);
    }
}
