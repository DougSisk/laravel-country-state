Laravel Country & State Helper
==============================
[![Latest Stable Version](https://poser.pugx.org/dougsisk/laravel-country-state/version)](https://packagist.org/packages/dougsisk/laravel-country-state)
[![Total Downloads](https://poser.pugx.org/dougsisk/laravel-country-state/downloads)](https://packagist.org/packages/dougsisk/laravel-country-state)
[![License](https://poser.pugx.org/dougsisk/laravel-country-state/license)](https://packagist.org/packages/dougsisk/laravel-country-state)

A helper to list countries & states in English in **Laravel 5**.

Installation
------------

Require this package with composer:

```
composer require dougsisk/laravel-country-state
```

After updating composer, add the CountryStateServiceProvider to the providers array in config/app.php

```
DougSisk\CountryState\CountryStateServiceProvider::class,
```

Copy the package config to your local config with the publish command:

```
php artisan vendor:publish
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

To get an array of countries:

```
$countries = CountryState::getCountries();
```

The array keys will be the countries' 2 letter ISO code and the values will be the countries' English name.


To get an array of a country's states, simply pass the country's 2 letter ISO code:

```
$states = CountryState::getStates('US');
```

The array keys will be the states' 2 letter ISO code and the values will be the states' English name.

HTTP JSON State Fetcher
-----------------------

Included is a route that you can use with JavaScript to get a list of a country's states. You could use this to update a state select box on a country select box change.

To use, simply send a request to `/country-state/get-states/{code}`, and replace `{code}` with the 2 letter ISO code of the country you wish to fetch. It will return a JSON array if states for the country found and null if none are.

You may update the `country-state` prefix with the `routePrefix` configuration option.

License
-------

This library is available under the [MIT license](LICENSE).