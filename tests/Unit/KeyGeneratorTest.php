<?php

namespace Elegasoft\Cipher\Tests\Unit;

use Elegasoft\Cipher\KeyGenerator;
use Elegasoft\Cipher\Tests\TestCase;
use Illuminate\Contracts\Filesystem\FileExistsException;

class KeyGeneratorTest extends TestCase
{
    /**
     * @var \Elegasoft\Cipher\KeyGenerator
     */
    public KeyGenerator $keyGenerator;

    public function setUp(): void
    {
        parent::setUp();
        $this->keyGenerator = new KeyGenerator();
    }

    /** @test */
    public function it_can_generate_default_keys()
    {
        $defaultKeys = $this->keyGenerator->default();

        $this->assertCount(5, $defaultKeys);

        foreach ($defaultKeys as $keys)
        {
            $this->assertCount(1, $keys);
        }
    }

    /** @test */
    public function it_can_generate_mulitple_default_keys()
    {
        $defaultKeys = $this->keyGenerator->default(5);

        $this->assertCount(5, $defaultKeys);

        foreach ($defaultKeys as $keys)
        {
            $this->assertCount(5, $keys);
        }
    }

    /** @test */
    public function it_can_get_the_config_stub()
    {
        $stubContent = $this->keyGenerator->getStub();

        $this->assertNotNull($stubContent);
        $this->assertGreaterThan(50, strlen($stubContent));
    }

    /** @test */
    public function it_can_generate_a_config_file()
    {
        $filePath = base_path('config/ciphers.php');
        $originalFileContents = file_get_contents(base_path('config/ciphers.php'));
        unlink($filePath);

        $this->assertFileDoesNotExist($filePath);

        $this->keyGenerator->generateConfig(12);

        $this->assertFileExists($filePath);

        file_put_contents(base_path('config/ciphers.php'), $originalFileContents);
    }

    /** @test */
    public function it_prevents_overwriting_the_config_file()
    {
        $filePath = base_path('config/ciphers.php');
        if (!file_exists($filePath))
        {
            file_put_contents($filePath, file_get_contents(__DIR__ . '../../config/config.php'));
        }

        $this->expectException(FileExistsException::class);

        $this->keyGenerator->generateConfig(12);
    }
}
