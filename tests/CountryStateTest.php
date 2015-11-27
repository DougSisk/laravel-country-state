<?php

use DougSisk\CountryState\CountryState;

class CountryStateTest extends PHPUnit_Framework_TestCase
{
    public function testGetCountries()
    {
        $countryState = new CountryState;
        $countries = $countryState->getCountries();

        $this->assertRegExp("/([A-Z]{2})/", key($countries));
        $this->assertEquals("United States", $countries['US']);
    }

    public function testGetCountryStates()
    {
        $countryState = new CountryState;
        $states = $countryState->getStates('US');

        $this->assertRegExp("/([A-Z]{2})/", key($states));
        $this->assertEquals("Hawaii", $states['HI']);
    }

    public function testGetStateName()
    {
        $countryState = new CountryState;
        $stateName = $countryState->getStateName('HI', 'US');

        $this->assertEquals("Hawaii", $stateName);
    }
}
