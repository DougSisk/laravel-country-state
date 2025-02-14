Laravel Country & State Helper
==============================
[![Latest Stable Version](https://poser.pugx.org/dougsisk/laravel-country-state/version)](https://packagist.org/packages/dougsisk/laravel-country-state)
[![Total Downloads](https://poser.pugx.org/dougsisk/laravel-country-state/downloads)](https://packagist.org/packages/dougsisk/laravel-country-state)
[![License](https://poser.pugx.org/dougsisk/laravel-country-state/license)](https://packagist.org/packages/dougsisk/laravel-country-state)

A helper to list countries & states in English in **Laravel 11.0+**.

What's Changed in 5.0
-----------------

* **PHP 8.2+ required**

_I'm aware the underlying country/state data package I utilize has not been actively maintained. I've looked into other packages, but have yet to find one that can easily be swapped in. Please feel free to submit a PR if you find one you think is a suitable replacement._

Installation
------------

Require this package with composer:

```
composer require dougsisk/laravel-country-state
```

This package will automatically be discovered by Laravel, if enabled. If you don't have auto package discovery on, you'll need to add the following service provider to your config/app.php:

```
DougSisk\CountryState\CountryStateServiceProvider::class,
```

Copy the package config to your local config with the publish command:

```
php artisan vendor:publish --provider="DougSisk\CountryState\CountryStateServiceProvider" --tag="config"
```

Configuration
-------------

By default, the helper will preload states for the US. You can change this via the `preloadCountryStates` config option:

```
'preloadCountryStates' => ['CA', 'MX', 'US']
```

If you don't want every country to be returned, you can define countries using the `limitCountries` config option:

```
'limitCountries' => ['CA', 'MX', 'US']
```

Usage
-----

You may now use the `CountryState` facade to access countries and states.

**Remember to import the namespace to access the facade in your files:**

```
use CountryState;
```

To get an array of countries:

```
$countries = CountryState::getCountries();
```

The array keys will be the countries' 2 letter ISO code and the values will be the countries' English name. You may also set the 3 letter ISO key as the argument to receive translations of the countries' names (limited support).


To get an array of a country's states, simply pass the country's 2 letter ISO code:

```
$states = CountryState::getStates('US');
```

The array keys will be the states' 2 letter ISO code and the values will be the states' English name.

License
-------

This library is available under the [MIT license](LICENSE).
