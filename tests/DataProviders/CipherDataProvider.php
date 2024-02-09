<?php

namespace Elegasoft\Cipher\Tests\DataProviders;

use Elegasoft\Cipher\CharacterBases\Base16;
use Elegasoft\Cipher\CharacterBases\Base34;
use Elegasoft\Cipher\CharacterBases\Base36;
use Elegasoft\Cipher\CharacterBases\Base54;
use Elegasoft\Cipher\CharacterBases\Base58;
use Elegasoft\Cipher\CharacterBases\Base62;
use Elegasoft\Cipher\CharacterBases\Base96;
use Elegasoft\Cipher\Ciphers\Base16Cipher;
use Elegasoft\Cipher\Ciphers\Base34Cipher;
use Elegasoft\Cipher\Ciphers\Base36Cipher;
use Elegasoft\Cipher\Ciphers\Base54Cipher;
use Elegasoft\Cipher\Ciphers\Base58Cipher;
use Elegasoft\Cipher\Ciphers\Base62Cipher;
use Elegasoft\Cipher\Ciphers\Base96Cipher;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\WithFaker;

class CipherDataProvider
{
    public static Generator $faker;

    public static function cipherStringsToEncrypt(): iterable
    {
        static::$faker = Factory::create();
        foreach (range(0, env('NUM_GENERATIONS', 5000)) as $i) {
            $string = static::stringData($i);
            foreach (self::ciphers() as $index => $cipher) {
                yield $cipher['cipher'].' '.$i.' '.$string => [$cipher, $string];
            }
        }
    }

    public static function stringData($i): string
    {
        static::$faker->seed(mt_rand() * $i);

        return static::$faker->randomElement([
            static::$faker->sentence,
            static::$faker->realTextBetween(5, 60),
            static::$faker->buildingNumber,
            static::$faker->address,
            static::$faker->iban,
            static::$faker->name,
            static::$faker->hexColor,
            static::$faker->iban,
            static::$faker->isbn13(),
            static::$faker->e164PhoneNumber(),
            static::$faker->filePath(),
            static::$faker->url,
            static::$faker->uuid,
            static::$faker->company,
            static::$faker->email,
            static::$faker->creditCardNumber,
            static::$faker->imei,
            static::$faker->ean13(),
            static::$faker->asciify('****************'),
            static::$faker->asciify('****************'),
            static::$faker->asciify('****************'),
            static::$faker->asciify('****************'),
            static::$faker->asciify('****************'),
            static::$faker->asciify('****************'),
            static::$faker->asciify('****************'),
            static::$faker->asciify('****************'),
        ]);
    }

    public static function ciphers(): iterable
    {
        yield class_basename(Base16Cipher::class) => [
            'characters'    => (new Base16)->getCharacters(),
            'cipher'        => Base16Cipher::class,
            'config'        => 'cipher.keys.base16',
            'characterBase' => Base16::class,
        ];
        yield class_basename(Base34Cipher::class) => [
            'characters'    => (new Base34)->getCharacters(),
            'cipher'        => Base34Cipher::class,
            'config'        => 'cipher.keys.base34',
            'characterBase' => Base34::class,
        ];
        yield class_basename(Base36Cipher::class) => [
            'characters'    => (new Base36)->getCharacters(),
            'cipher'        => Base36Cipher::class,
            'config'        => 'cipher.keys.base36',
            'characterBase' => Base36::class,
        ];
        yield class_basename(Base54Cipher::class) => [
            'characters'    => (new Base54)->getCharacters(),
            'cipher'        => Base54Cipher::class,
            'config'        => 'cipher.keys.base54',
            'characterBase' => Base54::class,
        ];
        yield class_basename(Base58Cipher::class) => [
            'characters'    => (new Base58)->getCharacters(),
            'cipher'        => Base58Cipher::class,
            'config'        => 'cipher.keys.base58',
            'characterBase' => Base58::class,
        ];
        yield class_basename(Base62Cipher::class) => [
            'characters'    => (new Base62)->getCharacters(),
            'cipher'        => Base62Cipher::class,
            'config'        => 'cipher.keys.base62',
            'characterBase' => Base62::class,
        ];
        yield class_basename(Base96Cipher::class) => [
            'characters'    => (new Base96)->getCharacters(),
            'cipher'        => Base96Cipher::class,
            'config'        => 'cipher.keys.base96',
            'characterBase' => Base96::class,
        ];
    }

    public static function cipherTypes(): iterable
    {
        yield Base16::class => ['characters' => Base16::class, 'driver' => 'base16'];
        yield Base36::class => ['characters' => Base36::class, 'driver' => 'base36'];
        yield Base58::class => ['characters' => Base58::class, 'driver' => 'base58'];
        yield Base62::class => ['characters' => Base62::class, 'driver' => 'base62'];
        yield Base96::class => ['characters' => Base96::class, 'driver' => 'base96'];
    }
}
