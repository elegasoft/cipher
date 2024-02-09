<?php

use Elegasoft\Cipher\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class KeyGeneratorCommandTest extends TestCase
{
    /** @test */
    public function it_can_save_the_keys_as_a_file(): void
    {
        $storage = config('cipher.storage');

        Storage::fake($storage['disk']);

        Storage::disk($storage['disk'])->assertMissing($storage['path'].DIRECTORY_SEPARATOR.$storage['filename']);

        Artisan::call('cipher:generate-keys');

        Storage::disk($storage['disk'])->assertExists($storage['path'].DIRECTORY_SEPARATOR.$storage['filename']);
    }
}