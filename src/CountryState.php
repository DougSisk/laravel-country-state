<?php

namespace DougSisk\CountryState;

use Phine\Country\Loader\Loader;

class CountryState
{
    protected $countries;
    protected $loader;
    protected $states = [];

    public function __construct($limitCountries = null, $preloadCountryStates = null)
    {
        $this->loader = new Loader;

        if ($limitCountries) {
            foreach ($limitCountries as $code) {
                $this->countries[$code] = $this->loader->loadCountry($code)->getShortName();
            }
        } else {
            $countries = $this->loader->loadCountries();

            foreach ($countries as $country) {
                $this->countries[$country->getAlpha2Code()] = $country->getShortName();
            }
        }
        
        if ($preloadCountryStates) {
            foreach ($preloadCountryStates as $country) {
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

    public function getStateName($lookFor, $country = null)
    {
        if ($country) {
            if (!isset($this->states[$country])) {
                $this->findCountryStates($country);
            }

            if (isset($this->states[$country][$lookFor])) {
                return $this->states[$country][$lookFor];
            }

            return;
        }

        foreach ($this->countries as $countryCode => $countryName) {
            $this->findCountryStates($countryCode);

            if (isset($this->states[$countryCode][$lookFor])) {
                return $this->states[$countryCode][$lookFor];
            }
        }
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
