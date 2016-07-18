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
            return new CountryState(config('countrystate.limitCountries'), config('countrystate.preloadCountryStates'));
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

        $routeConfig = [
            'namespace' => 'DougSisk\CountryState',
            'prefix' => $this->app['config']->get('countrystate.routePrefix'),
        ];

        $this->getRouter()->group($routeConfig, function ($router) {
            $router->get('get-states/{countryCode}', [
                'uses' => 'CountryStateController@getCountryStates',
                'as' => 'countrystate.country.states',
                'where' => [
                    'countryCode' => '[A-Za-z]{2}',
                ],
            ]);
        });

        AliasLoader::getInstance()->alias('CountryState', 'DougSisk\CountryState\CountryStateFacade');
    }

    /**
     * Get the active router.
     *
     * @return Router
     */
    protected function getRouter()
    {
        return $this->app['router'];
    }
}
