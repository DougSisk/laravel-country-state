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

    /**
     * Pass a country code to search a single country or an array of codes to search several countries in the order given
     * If $country is null all countries will be searched, which can be slow.
     *
     * @param string $lookFor
     * @param mixed $country
     * @return string|null
     */
    public function getStateCode($lookFor, $country = null)
    {
        $lookFor = mb_strtoupper($lookFor);
        $countries = is_null($country) ? array_keys($this->countries) : (array)$country;

        foreach ($countries as $countryCode) {
            $states = array_map('mb_strtoupper', $this->findCountryStates($countryCode));

            if ($code = array_search($lookFor, $states)) {
                return $code;
            }
        }

        return null;
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
