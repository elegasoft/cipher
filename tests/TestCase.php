<?php

namespace Elegasoft\Cipher\Tests;

use Elegasoft\Cipher\CipherServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        ini_set('memory_limit', '1G');
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            CipherServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
