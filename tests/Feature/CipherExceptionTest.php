<?php

namespace Elegasoft\Cipher\Tests\Feature;

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
use Elegasoft\Cipher\Tests\TestCase;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CipherExceptionTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider mismatchedProvider
     */
    public function having_unequal_key_characters_than_character_base_will_cause_an_exception(string $characters,
        string $cipherClass): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $cipherKey = [substr(str_shuffle($characters),2)];

        $cipher = new $cipherClass($cipherKey);
    }

    /**
     * @test
     *
     * @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::ciphers()
     */
    public function having_key_characters_outside_normal_character_base_will_cause_an_exception($characters, $cipherClass,
        $config): void
    {
        $invalidCharacters = $this->setInvalidCharacters($characters);
        $characterToReplace = Arr::random(str_split($characters), 1);
        $testCharacters = Str::replace($characterToReplace, Arr::random($invalidCharacters, 1), $characters);
        $this->expectException(\InvalidArgumentException::class);
        $cipherKey = [$testCharacters];

        $cipher = new $cipherClass($cipherKey, config($config));
        $cipher->keys($cipherKey);
    }

    private function setInvalidCharacters(string $characters): array
    {
        if (strlen($characters) < 95)
        {
            $base96Characters = (new Base96())->getCharacters();

            return array_diff(str_split($base96Characters), str_split($characters));
        }

        return [
            'ó',
            'ú',
            'ñ',
            '®',
            '¯',
            '²',
            '¶',
            '§',
            'Ñ',
            '¿',
            'ª',
            'º',
            '¬',
            '½',
            '¼',
            '¡',
            '«',
            '»',
            '¦',
            'ß',
            'µ',
            '±',
            '°',
            '•',
            '·',
            '€',
            '„',
            '…',
            '†',
            '‡',
            'ˆ',
            '‰',
            'Š',
            '‹',
            '&amp;',
        ];
    }

    /**
     * @test
     *
     * @dataProvider \Elegasoft\Cipher\Tests\DataProviders\CipherDataProvider::ciphers()
     */
    public function it_requires_cipher_keys($characters, $cipher): void
    {
        $this->expectException(\Error::class);
        /** @var \Elegasoft\Cipher\Ciphers\Cipher $cipher */
        $cipher = new $cipher;
        $cipher->encipher($characters);
    }

    public static function mismatchedProvider(): iterable
    {
        /**
         * Base 16 Characters with cipher
         */
        yield class_basename(Base16::class) . ' characters with ' . class_basename(Base36Cipher::class) . ' cipher' => ['characters' => (new Base16)->getCharacters(), 'cipher' => Base36Cipher::class];
        yield class_basename(Base16::class) . ' characters with ' . class_basename(Base58Cipher::class) . ' cipher' => ['characters' => (new Base16)->getCharacters(), 'cipher' => Base58Cipher::class];
        yield class_basename(Base16::class) . ' characters with ' . class_basename(Base62Cipher::class) . ' cipher' => ['characters' => (new Base16)->getCharacters(), 'cipher' => Base62Cipher::class];
        yield class_basename(Base16::class) . ' characters with ' . class_basename(Base96Cipher::class) . ' cipher' => ['characters' => (new Base16)->getCharacters(), 'cipher' => Base96Cipher::class];

        /**
         * Base 36 Characters with cipher
         */
        yield class_basename(Base36::class) . ' characters with ' . class_basename(Base16Cipher::class) . ' cipher' => ['characters' => (new Base36)->getCharacters(), 'cipher' => Base16Cipher::class];
        yield class_basename(Base36::class) . ' characters with ' . class_basename(Base58Cipher::class) . ' cipher' => ['characters' => (new Base36)->getCharacters(), 'cipher' => Base58Cipher::class];
        yield class_basename(Base36::class) . ' characters with ' . class_basename(Base62Cipher::class) . ' cipher' => ['characters' => (new Base36)->getCharacters(), 'cipher' => Base62Cipher::class];
        yield class_basename(Base36::class) . ' characters with ' . class_basename(Base96Cipher::class) . ' cipher' => ['characters' => (new Base36)->getCharacters(), 'cipher' => Base96Cipher::class];

        /**
         * Base 58 Characters with cipher
         */
        yield class_basename(Base58::class) . ' characters with ' . class_basename(Base16Cipher::class) . ' cipher' => ['characters' => (new Base58)
            ->getCharacters(), 'cipher'                                                                                              => Base16Cipher::class];
        yield class_basename(Base58::class) . ' characters with ' . class_basename(Base36Cipher::class) . ' cipher' => ['characters' => (new Base58)->getCharacters(), 'cipher' => Base36Cipher::class];
        yield class_basename(Base58::class) . ' characters with ' . class_basename(Base62Cipher::class) . ' cipher' => ['characters' => (new Base58)->getCharacters(), 'cipher' => Base62Cipher::class];
        yield class_basename(Base58::class) . ' characters with ' . class_basename(Base96Cipher::class) . ' cipher' => ['characters' => (new Base58)->getCharacters(), 'cipher' => Base96Cipher::class];

        /**
         * Base 62 Characters with cipher
         */
        yield class_basename(Base62::class) . ' characters with ' . class_basename(Base16Cipher::class) . ' cipher' => ['characters' => (new Base62)->getCharacters(), 'cipher' => Base16Cipher::class];
        yield class_basename(Base62::class) . ' characters with ' . class_basename(Base36Cipher::class) . ' cipher' => ['characters' => (new Base62)
            ->getCharacters(), 'cipher'                                                                                              => Base36Cipher::class];
        yield class_basename(Base62::class) . ' characters with ' . class_basename(Base58Cipher::class) . ' cipher' => ['characters' => (new Base62)
            ->getCharacters(), 'cipher'                                                                                              => Base58Cipher::class];
        yield class_basename(Base62::class) . ' characters with ' . class_basename(Base96Cipher::class) . ' cipher' => ['characters' => (new Base62)->getCharacters(), 'cipher' => Base96Cipher::class];

        /**
         * Base 96 Characters with cipher
         */
        yield class_basename(Base96::class) . ' characters with ' . class_basename(Base16Cipher::class) . ' cipher' => ['characters' => (new Base96)->getCharacters(), 'cipher' => Base16Cipher::class];
        yield class_basename(Base96::class) . ' characters with ' . class_basename(Base36Cipher::class) . ' cipher' => ['characters' => (new Base96)->getCharacters(), 'cipher' => Base36Cipher::class];
        yield class_basename(Base96::class) . ' characters with ' . class_basename(Base58Cipher::class) . ' cipher' => ['characters' => (new Base96)->getCharacters(), 'cipher' => Base58Cipher::class];
        yield class_basename(Base96::class) . ' characters with ' . class_basename(Base62Cipher::class) . ' cipher' => ['characters' => (new Base96)->getCharacters(), 'cipher' => Base62Cipher::class];
    }
}
