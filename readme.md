Laravel Country & State Helper
==============================
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/ea4f379e-b76f-46b0-942c-6aa0ff457100/mini.png)](https://insight.sensiolabs.com/projects/ea4f379e-b76f-46b0-942c-6aa0ff457100)
[![Latest Stable Version](https://poser.pugx.org/dougsisk/laravel-country-state/version)](https://packagist.org/packages/dougsisk/laravel-country-state)
[![Total Downloads](https://poser.pugx.org/dougsisk/laravel-country-state/downloads)](https://packagist.org/packages/dougsisk/laravel-country-state)
[![License](https://poser.pugx.org/dougsisk/laravel-country-state/license)](https://packagist.org/packages/dougsisk/laravel-country-state)

A helper to list countries & states in English in **Laravel 6.0+**. *Laravel 5.1-5.8 supported in version 2 (see below).*

What's Changed in 4.0
-----------------

* **PHP 7.4+ required**

Installation
------------

Require this package with composer:

```
composer require dougsisk/laravel-country-state
```

After updating composer, add the CountryStateServiceProvider to the providers array in config/app.php (not required for Laravel 5.5+ thanks to package auto-discovery)

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
