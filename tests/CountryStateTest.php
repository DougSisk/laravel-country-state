<?php

use DougSisk\CountryState\CountryState;
use PHPUnit\Framework\TestCase;

class CountryStateTest extends TestCase
{
    public function testGetCountries()
    {
        $countries = (new CountryState())->getCountries();

        $this->assertMatchesRegularExpression('/([A-Z]{2})/', key($countries));
        $this->assertEquals('United States', $countries['US']);
    }

    public function testGetCountry()
    {
        $this->assertInstanceOf('Rinvex\Country\Country', (new CountryState())->getCountry('ca'));
    }

    public function testGetCountryName()
    {
        $this->assertEquals('Canada', (new CountryState())->getCountryName('ca'));
    }

    public function testGetCountryStates()
    {
        $states = (new CountryState())->getStates('US');

        $this->assertMatchesRegularExpression('/([A-Z]{2})/', key($states));
        $this->assertEquals('Hawaii', $states['HI']);
    }

    public function testGetCountryStatesForCountriesWithoutStates()
    {
        $states = (new CountryState())->getStates('AW');

        $this->assertEmpty($states);
    }

    public function testGetStateCode()
    {
        $stateCode = (new CountryState())->getStateCode('Hawaii', 'US');

        $this->assertEquals('HI', $stateCode);
    }

    public function testGetStateName()
    {
        $stateName = (new CountryState())->getStateName('HI', 'US');

        $this->assertEquals('Hawaii', $stateName);
    }

    public function testCountryNotFound()
    {
        $this->expectException(\DougSisk\CountryState\Exceptions\CountryNotFoundException::class);

        (new CountryState())->getStates('USA');
    }

    public function testStateNotFound()
    {
        $this->expectException(\DougSisk\CountryState\Exceptions\StateNotFoundException::class);

        (new CountryState())->getStateName('AY', 'US');
    }

    public function testGetTranslatedCountries()
    {
        $translatedCountries = (new CountryState())->getCountries('spa');

        $this->assertEquals('Estados Unidos', $translatedCountries['US']);
    }
}
