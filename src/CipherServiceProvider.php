<?php

namespace Elegasoft\Cipher;

use Elegasoft\Cipher\Ciphers\Base96Cipher;
use Elegasoft\Cipher\Console\Commands\KeyGenerator;
use Illuminate\Support\ServiceProvider;

class CipherServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole())
        {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('cipher.php'),
            ], 'config');
            $this->commands([KeyGenerator::class]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'cipher');


        $this->app->singleton('cipher', function ()
        {
            $defaultCipherClass = config('ciphers.default') ?? Base96Cipher::class;
            return new $defaultCipherClass(config('ciphers.keys.Base96'));
        });
    }
}
