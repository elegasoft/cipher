<?php

namespace Elegasoft\Cipher\Tests\DataProviders;

use Elegasoft\Cipher\CharacterBases\Base16;
use Elegasoft\Cipher\CharacterBases\Base36;
use Elegasoft\Cipher\CharacterBases\Base58;
use Elegasoft\Cipher\CharacterBases\Base62;
use Elegasoft\Cipher\CharacterBases\Base96;
use Elegasoft\Cipher\Ciphers\Base16Cipher;
use Elegasoft\Cipher\Ciphers\Base36Cipher;
use Elegasoft\Cipher\Ciphers\Base58Cipher;
use Elegasoft\Cipher\Ciphers\Base62Cipher;
use Elegasoft\Cipher\Ciphers\Base96Cipher;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;

class CipherDataProvider
{

    use WithFaker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function cipherStringsToEncrypt(): iterable
    {
        foreach (range(0, env('NUM_GENERATIONS', 5000)) as $i)
        {
            $string = $this->stringData($i);
            foreach ($this->ciphers() as $index => $cipher)
            {
                yield $cipher['cipher'] . ' ' . $i . ' ' . $string => [$cipher, $string];
            }
        }
    }

    public
    function stringData($i): string
    {
        $this->faker->seed(mt_rand() * $i);
        return $this->faker->randomElement([
            $this->faker->sentence,
            $this->faker->realTextBetween(5, 60),
            $this->faker->buildingNumber,
            $this->faker->address,
            $this->faker->iban,
            $this->faker->name,
            $this->faker->hexColor,
            $this->faker->iban,
            $this->faker->isbn13(),
            $this->faker->e164PhoneNumber(),
            $this->faker->filePath(),
            $this->faker->url,
            $this->faker->uuid,
            $this->faker->company,
            $this->faker->email,
            $this->faker->creditCardNumber,
            $this->faker->imei,
            $this->faker->ean13(),
            $this->faker->asciify('****************'),
            $this->faker->asciify('****************'),
            $this->faker->asciify('****************'),
            $this->faker->asciify('****************'),
            $this->faker->asciify('****************'),
            $this->faker->asciify('****************'),
            $this->faker->asciify('****************'),
            $this->faker->asciify('****************'),
        ]);
    }

    public function ciphers(): iterable
    {
        yield class_basename(Base16Cipher::class) => [
            'characters' => (new Base16)->getCharacters(),
            'cipher'     => Base16Cipher::class,
            'config'     => 'ciphers.keys.base16',
        ];
        yield class_basename(Base36Cipher::class) => [
            'characters' => (new Base36)->getCharacters(),
            'cipher'     => Base36Cipher::class,
            'config'     => 'ciphers.keys.base36',
        ];
        yield class_basename(Base58Cipher::class) => [
            'characters' => (new Base58)->getCharacters(),
            'cipher'     => Base58Cipher::class,
            'config'     => 'ciphers.keys.base58',
        ];
        yield class_basename(Base62Cipher::class) => [
            'characters' => (new Base62)->getCharacters(),
            'cipher'     => Base62Cipher::class,
            'config'     => 'ciphers.keys.base62',
        ];
        yield class_basename(Base96Cipher::class) => [
            'characters' => (new Base96)->getCharacters(),
            'cipher'     => Base96Cipher::class,
            'config'     => 'ciphers.keys.base96',
        ];
    }
}