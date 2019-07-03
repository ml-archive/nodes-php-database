## Database

⚠️**This package is deprecated**⚠️

Most of this is build in Laravel now. No reason to use this anymore


A collection of our most used methods in pretty much every project we create at [Nodes](http://nodesagency.com)

[![Total downloads](https://img.shields.io/packagist/dt/nodes/database.svg)](https://packagist.org/packages/nodes/database)
[![Monthly downloads](https://img.shields.io/packagist/dm/nodes/database.svg)](https://packagist.org/packages/nodes/database)
[![Latest release](https://img.shields.io/packagist/v/nodes/database.svg)](https://packagist.org/packages/nodes/database)
[![Open issues](https://img.shields.io/github/issues/nodes-php/database.svg)](https://github.com/nodes-php/database/issues)
[![License](https://img.shields.io/packagist/l/nodes/database.svg)](https://packagist.org/packages/nodes/database)
[![Star repository on GitHub](https://img.shields.io/github/stars/nodes-php/database.svg?style=social&label=Star)](https://github.com/nodes-php/database/stargazers)
[![Watch repository on GitHub](https://img.shields.io/github/watchers/nodes-php/database.svg?style=social&label=Watch)](https://github.com/nodes-php/database/watchers)
[![Fork repository on GitHub](https://img.shields.io/github/forks/nodes-php/database.svg?style=social&label=Fork)](https://github.com/nodes-php/database/network)
[![StyleCI](https://styleci.io/repos/49194368/shield)](https://styleci.io/repos/49194368)

## 📝 Introduction

There is a saying;

> Do not re-invent the wheel.

Therefore we've created a collection of all the methods we use in almost every project. We've also added some very cool ones which automatically throws
an exception if a record could not be found and some other quite neat things.

## 📦 Installation
To install this package you will need:

* Laravel 5.1+
* PHP 5.5.9+

You must then modify your `composer.json` file and run `composer update` to include the latest version of the package in your project.

```json
"require": {
    "nodes/database": "^1.0"
}
```

Or you can run the composer require command from your terminal.

```bash
composer require nodes/database:^1.0
```

## ⚙ Usage

Create a new class and make it extend `Nodes\Database\Eloquent\Repository`.

At a later time, we'll create a more in-depth documentation of each method in the repository,
but for now, we'll recommend you to look through the source and read the DocBlock for each method.

Check out all the available methods in the [src/Eloquent/Repository.php](https://github.com/nodes-php/database/blob/master/src/Eloquent/Repository.php) file.

### Global methods

```php
function render_sql(\Illuminate\Database\Query\Builder $query)
```

## 🏆 Credits

This package is developed and maintained by the PHP team at [Nodes](http://nodesagency.com)

[![Follow Nodes PHP on Twitter](https://img.shields.io/twitter/follow/nodesphp.svg?style=social)](https://twitter.com/nodesphp) [![Tweet Nodes PHP](https://img.shields.io/twitter/url/http/nodesphp.svg?style=social)](https://twitter.com/nodesphp)

## 📄 License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

