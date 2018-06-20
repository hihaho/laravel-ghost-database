Laravel Ghost Database
=========================

Easily import your existing database in a ghost database, which could be useful for UnitTesting or backups etc.

# :warning: Package in development :warning:

This package is not yet stable, you can test it by installing it, but the package may be subject to large changes before a final release

# :camera: Photoware

This package is free to use, but inspired by [Spaties' Poscardware](https://spatie.be/en/opensource/postcards) we'd love to see where 
where this package is being developed. A photo of an important landmark in your area would be highly appreciated.

Our email address is [photoware@hihaho.com](mailto:photoware@hihaho.com)

# Installation

Simply add the following line to your ```composer.json``` and run ```composer update```
```
"hihaho/laravel-ghost-database": "v0.1.*"
```
Or use composer to add it with the following command
```bash
composer require hihaho/laravel-ghost-database
```

## Laravel Auto-Discovery

This package is automatically discovered with Laravel Auto-Discovery, if you wish to register the package yourself you
can add this package to your ```composer.json``` file:
```
"extra": {
    "laravel": {
        "dont-discover": [
            "hihaho/laravel-ghost-database"
        ]
    }
}
``` 

# Usage
Three commands will be available with this package to easily export your current database to a "ghost" database.

- ```php artisan ghost-db:export``` - Exports your current DB to a SQL file
- ```php artisan ghost-db:import``` - Imports the last exported SQL file into the ghost DB
- ```php artisan ghost-db:flush``` - Flushes the ghost DB (also happens before an import)

# Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

# Testing

``` bash
$ composer test
```

# Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

# Maintainers

- [Robert Boes](https://github.com/robertboes)

# License

MIT. Please see the [license file](license.md) for more information.
