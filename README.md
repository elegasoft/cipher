# A Vigenere Cipher Package for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elegasoft/cipher.svg?style=flat-square)](https://packagist.org/packages/elegasoft/cipher)
[![Total Downloads](https://img.shields.io/packagist/dt/elegasoft/cipher.svg?style=flat-square)](https://packagist.org/packages/elegasoft/cipher)
![GitHub Actions](https://github.com/elegasoft/cipher/actions/workflows/main.yml/badge.svg)

This is package a simple Vigenere Cipher package to obfuscate plaint text items from on lookers. This is <u>not an
alternative</u> to strong encryption.

## Installation

You can install the package via composer:

```bash
composer require elegasoft/cipher
```

## Usage

```php
// It only encodes characters in its character base
$cipher = new Base62Cipher(config('ciphers.keys.base62'));
$cipher->encipher('hide-this-number-1111');
// returns 39O8-RBeX-4ZyGD6-o8pR
$cipher->decipher('39O8-RBeX-4ZyGD6-o8pR');
// returns hide-this-message


// It can encipher symbols in its character base
$cipher = new Base96Cipher(config('ciphers.keys.base96'));
$cipher->encipher('hide-this-number-1111');
// returns (3]QC+2}SsoHzRz14I<~L
$cipher->decipher('(3]QC+2}SsoHzRz14I<~L');
// returns hide-this-message
```

**Note:** Using different cipher keys will produce different enciphered text outputs than what you see here.

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
