<?php

use DougSisk\CountryState\CountryStateServiceProvider;
use DougSisk\CountryState\Exceptions\CountryNotFoundException;
use DougSisk\CountryState\Exceptions\StateNotFoundException;

class CountryStateLaravelTest extends Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [CountryStateServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('countrystate.limitCountries', ['CA']);
        $app['config']->set('countrystate.preloadCountryStates', ['CA']);
    }

    public function testGetCountries()
    {
        $countries = CountryState::getCountries();

        $this->assertMatchesRegularExpression("/([A-Z]{2})/", key($countries));
        $this->assertEquals('Canada', $countries['CA']);
    }

    public function testGetCountryStates()
    {
        $states = CountryState::getStates('CA');

        $this->assertMatchesRegularExpression("/([A-Z]{2})/", key($states));
        $this->assertEquals('Manitoba', $states['MB']);
    }

    public function testGetStateCode()
    {
        $stateCode = CountryState::getStateCode('Manitoba', 'CA');

        $this->assertEquals('MB', $stateCode);
    }

    public function testGetStateName()
    {
        $stateName = CountryState::getStateName('MB', 'CA');

        $this->assertEquals('Manitoba', $stateName);
    }

    public function testCountryNotFound()
    {
        $this->expectException(CountryNotFoundException::class);

        CountryState::getStates('CAN');
    }

    public function testStateNotFound()
    {
        $this->expectException(StateNotFoundException::class);

        CountryState::getStateName('AY', 'CA');
    }

    public function testGetTranslatedCountries()
    {
        $translatedCountries = CountryState::getCountries('spa');

        $this->assertEquals('Canadá', $translatedCountries['CA']);
    }
}
