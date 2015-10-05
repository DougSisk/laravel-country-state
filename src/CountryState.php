<?php

namespace DougSisk\CountryState;

use Phine\Country\Loader\Loader;

class CountryState
{
    protected $countries;
    protected $loader;
    protected $states = [];

    public function __construct()
    {
        $this->loader = new Loader;

        if ($countries = config('countrystate.limitCountries')) {
            foreach ($countries as $code) {
                $this->countries[$code] = $this->loader->loadCountry($code)->getShortName();
            }
        } else {
            $countries = $this->loader->loadCountries();

            foreach ($countries as $country) {
                $this->countries[$country->getAlpha2Code()] = $country->getShortName();
            }
        }
        
        if ($preLoad = config('countrystate.preloadCountryStates')) {
            foreach ($preLoad as $country) {
                $this->addCountryStates($country);
            }
        }
    }

    public function getCountries()
    {
        return $this->countries;
    }

    public function getStates($country)
    {
        return $this->findCountryStates($country);
    }

    protected function findCountryStates($country)
    {
        if (!array_key_exists($country, $this->states)) {
            $this->addCountryStates($country);
        }

        return $this->states[$country];
    }

    protected function addCountryStates($country)
    {
        $this->states[$country] = [];
        $states = $this->loader->loadSubdivisions($country);

        foreach ($states as $code => $subdivision) {
            $code = preg_replace("/([A-Z]{2}-)/", '', $code);
            $this->states[$country][$code] = $subdivision->getName();
        }
    }
}
