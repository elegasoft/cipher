# A Cipher Package for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elegasoft/cipher.svg?style=flat-square)](https://packagist.org/packages/elegasoft/cipher)
[![Total Downloads](https://img.shields.io/packagist/dt/elegasoft/cipher.svg?style=flat-square)](https://packagist.org/packages/elegasoft/cipher)
![GitHub Actions](https://github.com/elegasoft/cipher/actions/workflows/main.yml/badge.svg)

This is a Polyalphanumeric Cipher which great for obfuscating plain text from both casual and not so casual on lookers,
but it
<u>should not</u> be used as an alternative to using strong encryption for sensitive data.

## Installation

You can install the package via composer:

```bash
composer require elegasoft/cipher
```

## Basic Usage

```php
// It only encodes characters in its character base
$cipher = new Base62Cipher(config('cipher.keys.base62'));
$cipher->encipher('hide-this-string-1111');
// returns 39O8-RBeX-4ZyGD6-o8pR
$cipher->decipher('39O8-RBeX-4ZyGD6-o8pR');
// returns hide-this-message


// It can encipher symbols in its character base
$cipher = new Base96Cipher(config('cipher.keys.base96'));
$cipher->encipher('hide-this-string-1111');
// returns (3]QC+2}SsoHzRz14I<~L
$cipher->decipher('(3]QC+2}SsoHzRz14I<~L');
// returns hide-this-message
```

### Using Padding to Extend the Minimum Output Length

```php
$cipher = new Base96Cipher(config('cipher.keys.base96'));
// It can pad the enciphered text to a minimal output length of 6
$cipher->paddedEncipher(string: 1, minOutputLength: 6, paddingCharacter: 'a');
// returns q3sp14
$cipher->paddedDecipher(encipheredString: 'q3sp14', paddingCharacter: 'a');
// return 1
```

**Note:** Using different cipher keys will produce different enciphered text outputs than what you see here.

### How does it work? (simplified example)

A cipher uses a substitute alphabet to replace plain text characters with a replacement character from the cipher
alphabet. This cipher can use a different cipher alphabet for each character in the plain text string.

Using `Base62Cipher::encifer('Hello!')` and only 3 cipher keys the following would occur:

1) plain text "H" is the 32nd character in the `Base62` character set and it would be replaced with the 32nd character
   in the `first cipher key`
2) plain text "e" is the 5th character in the `Base62` character set and it would be replaced with the 5th character in
   the `second cipher key`
3) plain text "l" is the 12th character in the `Base62` character set and it would be replaced with the 12th character
   in the `third cipher key`
4) plain text "l" is the 12th character in the `Base62` character set and it would be replaced with the 12th character
   in the `second cipher key`
5) plain text "o" is the 15th character in the `Base62` character set and it would be replaced with the 15th character
   in the `third cipher key`
5) plain text "!" would not be replaced as it is not found in the `Base62` character set, however if it were a
   replaceable character the replacement would occur in the `first cipher key`

### How does it really work?

If you take the simplified example above the first character would be enciphered exactly as provided, however, going
forward to the "e" character and forward from there. Before replacing the "e" with the character in the 5th position,
the cipher key would be rotated sequentially until the "H" character is at the 0 index position. Then the character at
the 5th position would be chosen. The cipher would continue switching key stream and rotating them until the previous
character is at the 0 index position before selecting the next character until reaching the end of the string.

This behavior increases the complexity of deciphering with little computational effort as strings starting with
different characters will yield wildly different results.

```php
// For example
$cipher = new Base62Cipher(config('cipher.keys.base62'));
$cipher->encipher('bat'); // Outputs eaO
$cipher->encipher('cat'); // Outputs koB
$cipher->encipher('hat'); // Outputs 3A9
$cipher->encipher('mat'); // Outputs PX2
```

If the cipher keys were not rotated based on the previous character, then the enciphered output of all four strings
would have the same final two characters.

### Uniqueness and Dealing with Sequential Inputs

As this is simply a cipher and not a hashing algorithm, each input should produce a singular output. I have run a number
of simulations of up to a million different inputs and have not found any repetition. However, there are noticeable
trends which may result if you send it sequential inputs which will result in similar outputs.

For example:

```php
$cipher = new Base62Cipher(config('cipher.keys.base62'))
$cipher->encipher('aaaaaaaa') // Outputs tW7vz1pT
$cipher->encipher('aaaaaaab') // Outputs tW7vz1pu
$cipher->encipher('aaaaaaac') // Outputs tW7vz1pn
$cipher->encipher('aaaaaaad') // Outputs tW7vz1p7

```

If you expect to have lots of sequential inputs, I would suggest that you use the `reverseEncipher`
and `reverseDecipher` to reduce sequential similarities.

For example:

```php
$cipher = new Base62Cipher(config('cipher.keys.base62'))
$cipher->reverseEncipher('aaaaaaaa') // Outputs tW7vz1pT
$cipher->reverseEncipher('aaaaaaab') // Outputs ea5H4Kt2
$cipher->reverseEncipher('aaaaaaac') // Outputs kouIgSfE
$cipher->reverseEncipher('aaaaaaad') // Outputs r4m7jswy
```

Obviously there will certainly be additional patterns which will certainly occur appear due to the nature of this cipher
algorithm, so please use it to obfuscate information and not for encryption.

### Collisions/Limitations

Collisions are an issue with Hashing algorithms as they take a variable length string as input and produce a fixed
length string as output. This `Elegasoft\Cipher` does not have a fixed output length, nor does it hash the input.
Instead it performs a cipher substitution, so think of it as changing the alphabet from a language your used to reading
to a language that you've never seen before and one which does not behave as a normal language. When
using `Elegasoft\Cipher` the output length is equal to the input length.

### Chance of decoding/deciphering?

I'm not a cryptographer and this hasn't been vetted by a cryptographer therefore I cannot calculate the difficulty of
breaking the cipher, but based on information available you wouldn't want to try to brute force this cipher.

Essentially, no one can really answer the question as to how difficult it is to decipher as there are unknown factors we
would need to know to make a more informed decision.

1) How long is the string that is being enciphered?
2) How many cipher keys did you use to encipher the string?
3) Which cipher did you use?
4) How random are your cipher keys?

To increase the difficulty of deciphering your strings, use longer strings (even if you have to pad them) and increase
the randomness and number of cipher keys. But above all else do not share your cipher keys.

After having coded this cipher algorithm, I then tried to find out which type of cipher it is. And the closest ciphers
in my limited research that I could find would be some form of Polyalphabetic Cipher which doesn't quite near a
One-Time-Pad, depending on how you share the encoded content and if you keep the keys a secret.

If you are a cryptographer, feel free to drop me a line on this, I'd be interested to know more.

### What use case does this solve?

I was looking for a way to shorten urls without having worry about collisions and thought it would be a fun project.
After several days of hacking away without any guidance or research it turns out I just may have come up my very own
enigma machine.

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email jason@elegasoft.com instead of using the issue tracker.

## Credits

- [Jason Cook](https://github.com/elegasoft)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
