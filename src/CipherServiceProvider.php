<?php

namespace Elegasoft\Cipher;

use Elegasoft\Cipher\Ciphers\Cipher;
use Elegasoft\Cipher\Ciphers\CipherContract;
use Elegasoft\Cipher\Console\Commands\KeyGenerator;
use Illuminate\Support\Arr;
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

        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'ciphers');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'cipher');

        $this->app->singleton(CipherManager::class, function ($app, $args): CipherContract|Cipher
        {
            return (new CipherManager($app))->cipher(Arr::get($args, 'driver') ?? config('ciphers.default'));
        });
    }
}
