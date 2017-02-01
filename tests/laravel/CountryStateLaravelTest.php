<?php

class CountryStateLaravelTest extends Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return ['DougSisk\CountryState\CountryStateServiceProvider'];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('countrystate.limitCountries', ['CA']);
        $app['config']->set('countrystate.preloadCountryStates', ['CA']);
    }

    public function testGetCountries()
    {
        $countries = CountryState::getCountries();

        $this->assertRegExp("/([A-Z]{2})/", key($countries));
        $this->assertEquals('Canada', $countries['CA']);
    }

    public function testGetCountryStates()
    {
        $states = CountryState::getStates('CA');

        $this->assertRegExp("/([A-Z]{2})/", key($states));
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

    public function testGetTranslatedCountries()
    {
        $translatedCountries = CountryState::getCountries('spa');

        $this->assertEquals('Canadá', $translatedCountries['CA']);
    }
}
