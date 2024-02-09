<?php

namespace Elegasoft\Cipher\Tests\Feature;

use Elegasoft\Cipher\KeyGenerator;
use Elegasoft\Cipher\Tests\TestCase;

class KeyGeneratorTest extends TestCase
{
    /** @test */
    public function it_can_generate_keys_for_all_bases(): void
    {
        $keyGenerator = new KeyGenerator();

        $keys = $keyGenerator->generate();

        $this->assertCount(7, $keys);

        $keys->each(fn($keyBase) => $this->assertCount(5, $keyBase));
    }

    /** @test */
    public function it_can_generate_keys_for_the_given_bases(): void
    {
        $keyGenerator = new KeyGenerator();

        $bases = collect(config('cipher.ciphers'))
            ->map(fn($base) => new $base['characters'])
            ->random($count = random_int(1, 6))->toArray();

        $keys = $keyGenerator->generate($bases, $numKeys = random_int(1, 5));

        $this->assertCount($count, $keys);

        $keys->each(fn($keyBase) => $this->assertCount($numKeys, $keyBase));
    }
}