<?php

namespace DougSisk\CountryState;

use Rinvex\Country\Country;
use Rinvex\Country\Loader;

class CountryState
{
    protected $countries = [];
    protected $countriesTranslated = [];
    protected $language;
    protected $states = [];

    public function __construct($limitCountries = null, $preloadCountryStates = null, $language = 'eng')
    {
        if ($limitCountries) {
            foreach ($limitCountries as $code) {
                $country = Loader::country($code);
                $this->countries[$country->getIsoAlpha2()] = $country;
            }
        } else {
            $countries = Loader::countries(true, true);

            foreach ($countries as $country) {
                $this->countries[$country->getIsoAlpha2()] = $country;
            }
        }

        $this->setLanguage($language);
        
        if ($preloadCountryStates) {
            foreach ($preloadCountryStates as $country) {
                $this->addCountryStates($country);
            }
        }
    }

    public function getCountries($language = null)
    {
        if ($language) {
            $this->setLanguage($language);
        }

        return $this->countriesTranslated;
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
        $countries = is_null($country) ? array_keys($this->countries) : (array) $country;

        foreach ($countries as $countryCode) {
            $states = array_map('mb_strtoupper', $this->findCountryStates($countryCode));

            if ($code = array_search($lookFor, $states)) {
                return $code;
            }
        }
    }

    public function setLanguage($language)
    {
        $this->language = $language;

        foreach ($this->countries as $country) {
            $this->countriesTranslated[$country->getIsoAlpha2()] = $country->getTranslation($this->language)['common'];
        }

        return $this;
    }

    protected function findCountryStates($country)
    {
        if (! array_key_exists($country, $this->states)) {
            $this->addCountryStates($country);
        }

        return $this->states[$country];
    }

    protected function addCountryStates($country)
    {
        if (! $country instanceof Country) {
            $country = Loader::country($country);
        }

        $countryCode = $country->getIsoAlpha2();

        $this->states[$countryCode] = [];
        $states = $country->getDivisions();

        foreach ($states as $code => $division) {
            $code = preg_replace("/([A-Z]{2}-)/", '', $code);
            $this->states[$countryCode][$code] = $division['name'];
        }
    }
}
