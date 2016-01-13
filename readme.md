# Doctrine

[![Build Status](https://img.shields.io/travis/iPublikuj/doctrine.svg?style=flat-square)](https://travis-ci.org/iPublikuj/doctrine)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/iPublikuj/doctrine.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/doctrine/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/iPublikuj/doctrine.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/doctrine/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/ipub/doctrine.svg?style=flat-square)](https://packagist.org/packages/ipub/doctrine)
[![Composer Downloads](https://img.shields.io/packagist/dt/ipub/doctrine.svg?style=flat-square)](https://packagist.org/packages/ipub/doctrine)
[![License](https://img.shields.io/packagist/l/ipub/doctrine.svg?style=flat-square)](https://packagist.org/packages/ipub/doctrine)
[![Dependency Status](https://img.shields.io/versioneye/d/user/projects/5696d0cbaf789b0027001cb5.svg?style=flat-square)](https://www.versioneye.com/user/projects/5696d0cbaf789b0027001cb5)

Doctrine extension is here to extend [Kdyby/Doctrine](https://github.com/Kdyby/Doctrine) with CRUD system.

## Installation

The best way to install ipub/doctrine is using [Composer](http://getcomposer.org/):

```sh
$ composer require ipub/doctrine
```

After that you have to register extension in config.neon.

```neon
extensions:
	doctrine: IPub\Doctrine\DI\OrmExtension
```

## Documentation

Learn how to register and work with blameable behavior in [documentation](https://github.com/iPublikuj/doctrine/blob/master/docs/en/index.md).

***
Homepage [http://www.ipublikuj.eu](http://www.ipublikuj.eu) and repository [http://github.com/iPublikuj/doctrine](http://github.com/iPublikuj/doctrine).
