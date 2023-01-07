<?php

namespace Elegasoft\Cipher\Tests\Feature;

use Elegasoft\Cipher\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class InstallKeysCommandTest extends TestCase
{
    /** @test */
    public function it_can_install_the_config_file_via_console_command()
    {
        if (file_exists(config_path('ciphers.php')))
        {
            $originalConfigContent = file_get_contents(config_path('ciphers.php'));
            unlink(config_path('ciphers.php'));
        }
        $this->assertFileDoesNotExist(config_path('ciphers.php'));

        Artisan::call('cipher:generate-keys');

        $this->assertFileExists(config_path('ciphers.php'));

        if ($originalConfigContent ?? false)
        {
            file_put_contents(config_path('ciphers.php'), $originalConfigContent);
        }
    }
}