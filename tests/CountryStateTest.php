<?php

use DougSisk\CountryState\CountryState;
use DougSisk\CountryState\Exceptions\CountryNotFoundException;
use DougSisk\CountryState\Exceptions\StateNotFoundException;

class CountryStateTest extends PHPUnit_Framework_TestCase
{
    private $countryState;

    public function __construct()
    {
        parent::__construct();

        $this->countryState = new CountryState;
    }

    public function testGetCountries()
    {
        $countries = $this->countryState->getCountries();

        $this->assertRegExp("/([A-Z]{2})/", key($countries));
        $this->assertEquals('United States', $countries['US']);
    }

    public function testGetCountry()
    {
        $this->assertInstanceOf('Rinvex\Country\Country', $this->countryState->getCountry('ca'));
    }

    public function testGetCountryName()
    {
        $this->assertEquals('Canada', $this->countryState->getCountryName('ca'));
    }

    public function testGetCountryStates()
    {
        $states = $this->countryState->getStates('US');

        $this->assertRegExp("/([A-Z]{2})/", key($states));
        $this->assertEquals('Hawaii', $states['HI']);
    }

    public function testGetCountryStatesForCountriesWithoutStates()
    {
        $states = $this->countryState->getStates('AW');

        $this->assertEmpty($states);
    }

    public function testGetStateCode()
    {
        $stateCode = $this->countryState->getStateCode('Hawaii', 'US');

        $this->assertEquals('HI', $stateCode);
    }

    public function testGetStateName()
    {
        $stateName = $this->countryState->getStateName('HI', 'US');

        $this->assertEquals('Hawaii', $stateName);
    }

    /**
     * @expectedException DougSisk\CountryState\Exceptions\CountryNotFoundException
     */
    public function testCountryNotFound()
    {
        $this->countryState->getStates('USA');
    }

    /**
     * @expectedException DougSisk\CountryState\Exceptions\StateNotFoundException
     */
    public function testStateNotFound()
    {
        $this->countryState->getStateName('AY', 'US');
    }

    public function testGetTranslatedCountries()
    {
        $translatedCountries = $this->countryState->getCountries('spa');

        $this->assertEquals('Estados Unidos', $translatedCountries['US']);
    }
}
