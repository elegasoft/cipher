<?php

use Elegasoft\Cipher\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class KeyGeneratorCommandTest extends TestCase
{
    public array $storage;

    public function setUp(): void
    {
        parent::setUp();

        $this->storage = config('cipher.storage');

        Storage::fake($this->storage['disk']);
    }

    /** @test */
    public function it_can_save_the_keys_as_a_file(): void
    {
        Storage::disk($this->storage['disk'])->assertMissing($this->storage['path'].DIRECTORY_SEPARATOR.$this->storage['filename']);

        Artisan::call('cipher:generate-keys');

        Storage::disk($this->storage['disk'])->assertExists($this->storage['path'].DIRECTORY_SEPARATOR.$this->storage['filename']);
    }

    /** @test */
    public function it_prevents_overwriting_existing_keys(): void
    {
        Artisan::call('cipher:generate-keys');

        $this->expectException(RuntimeException::class);

        Artisan::call('cipher:generate-keys');
    }
}