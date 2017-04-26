<?php

namespace DougSisk\CountryState;

use Exception;
use Rinvex\Country\Country;
use Rinvex\Country\CountryLoader;

class CountryState
{
    protected $countries = [];
    protected $countriesTranslated = [];
    protected $language;
    protected $states = [];

    /**
     * Create a new country state helper instance.
     *
     * @param  array $limitCountries
     * @param  array $preloadCountryStates
     * @param  string $language
     * @return void
     */
    public function __construct($limitCountries = null, $preloadCountryStates = null, $language = 'eng')
    {
        if ($limitCountries) {
            foreach ($limitCountries as $code) {
                $country = $this->loadCountry($code);

                $this->countries[$country->getIsoAlpha2()] = $country;
            }
        } else {
            $countries = CountryLoader::countries(true, true);

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

    /**
     * Get a list of countries. If class has been constructed to limit countries, only those countries will be returned.
     * The array returned will be countries' names in the class' set language.
     * If a different language is desired, pass the three character ISO 639-3 code of the desired language
     *
     * @param string $language
     * @return array
     */
    public function getCountries($language = null)
    {
        if ($language) {
            $this->setLanguage($language);
        }

        return $this->countriesTranslated;
    }

    /**
     * Get a list of states for a given country.
     * The country's two character ISO code
     *
     * @param string $country
     * @return array
     */
    public function getStates($country)
    {
        return $this->findCountryStates($country);
    }

    /**
     * Get the name of a state by passing its two character code
     * Specifying a two character ISO country code will limit the search to a specific country
     *
     * @param string $lookFor
     * @param string $country
     * @return string
     */
    public function getStateName($lookFor, $country = null)
    {
        if ($country) {
            if (! isset($this->states[$country])) {
                $this->findCountryStates($country);
            }

            if (isset($this->states[$country][$lookFor])) {
                return $this->states[$country][$lookFor];
            }

            throw new Exceptions\StateNotFoundException;
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
     * @return string|void
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

    /**
     * Change the default translation language using the three character ISO 639-3 code of the desired language.
     * Country name translations will be reloaded.
     *
     * @param string $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        foreach ($this->countries as $country) {
            $this->countriesTranslated[$country->getIsoAlpha2()] = $country->getTranslation($this->language)['common'];
        }

        return $this;
    }

    protected function loadCountry($code)
    {
        try {
            return CountryLoader::country(mb_strtolower($code));
        } catch (Exception $e) {
            throw new Exceptions\CountryNotFoundException;
        }
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
            $country = $this->loadCountry($country);
        }

        $countryCode = $country->getIsoAlpha2();

        $this->states[$countryCode] = [];
        $states = $country->getDivisions();

        if( !is_null($states) ) {
            foreach ($states as $code => $division) {
                $code = preg_replace("/([A-Z]{2}-)/", '', $code);
                $this->states[$countryCode][$code] = $division['name'];
            }
        }
    }
}
