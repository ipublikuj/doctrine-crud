# Doctrine CRUD

[![Build Status](https://badgen.net/github/checks/ipublikuj/doctrine-crud/master?cache=300&style=flast-square)](https://github.com/ipublikuj/doctrine-crud/actions)
[![Licence](https://badgen.net/github/license/ipublikuj/doctrine-crud?cache=300&style=flast-square)](https://github.com/ipublikuj/doctrine-crud/blob/master/LICENSE.md)
[![Code coverage](https://badgen.net/coveralls/c/github/ipublikuj/doctrine-crud?cache=300&style=flast-square)](https://coveralls.io/r/ipublikuj/doctrine-crud)

![PHP](https://badgen.net/packagist/php/ipub/doctrine-crud?cache=300&style=flast-square)
[![Latest stable](https://badgen.net/packagist/v/ipub/doctrine-crud/latest?cache=300&style=flast-square)](https://packagist.org/packages/ipublikuj/doctrine-crud)
[![Downloads total](https://badgen.net/packagist/dt/ipub/doctrine-crud?cache=300&style=flast-square)](https://packagist.org/packages/ipublikuj/doctrine-crud)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat-square)](https://github.com/phpstan/phpstan)

Implementation of CRUD system into [Doctrine3](https://github.com/doctrine/orm) with CRUD system.

## Installation

The best way to install **ipub/doctrine-crud** is using [Composer](http://getcomposer.org/):

```sh
composer require ipub/doctrine-crud
```

After that, you have to register extension in config.neon.

```neon
extensions:
    doctrineCrud: IPub\DoctrineCrud\DI\DoctrineCrudExtension
```

## Documentation

Learn how to register and work with blameable behavior in [documentation](https://github.com/iPublikuj/doctrine-crud/blob/master/docs/en/index.md).

***
Homepage [https://www.ipublikuj.eu](https://www.ipublikuj.eu) and repository [http://github.com/iPublikuj/doctrine-crud](http://github.com/iPublikuj/doctrine-crud).
