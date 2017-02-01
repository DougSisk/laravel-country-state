<?php

namespace DougSisk\CountryState;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class CountryStateServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('CountryState', function () {
            return new CountryState(config('countrystate.limitCountries'), config('countrystate.preloadCountryStates'), config('countrystate.language'));
        });
        $this->mergeConfigFrom(__DIR__ . '/config/countrystate.php', 'countrystate');
    }

    /**
     * Publish the plugin configuration.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/countrystate.php' => config_path('countrystate.php'),
        ], 'config');

        AliasLoader::getInstance()->alias('CountryState', 'DougSisk\CountryState\CountryStateFacade');
    }
}
