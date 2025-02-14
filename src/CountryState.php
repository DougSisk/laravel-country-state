<?php

namespace DougSisk\CountryState;

use Exception;
use Rinvex\Country\Country;
use Rinvex\Country\CountryLoader;

class CountryState
{
    protected array $countries = [];

    protected array $countriesTranslated = [];

    protected string $language;

    protected array $states = [];

    /**
     * Create a new country state helper instance.
     */
    public function __construct(?array $limitCountries = null, ?array $preloadCountryStates = null, string $language = 'eng')
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
     */
    public function getCountries(?string $language = null): array
    {
        if ($language) {
            $this->setLanguage($language);
        }

        return $this->countriesTranslated;
    }

    /**
     * Get the information of a country by passing its two character code
     */
    public function getCountry(string $lookFor): Country
    {
        return $this->loadCountry($lookFor);
    }

    /**
     * Get the name of a country by passing its two character code
     */
    public function getCountryName(string $lookFor): string
    {
        return $this->getCountry($lookFor)->getName();
    }

    /**
     * Get a list of states for a given country.
     * The country's two character ISO code
     */
    public function getStates(string $country): array
    {
        return $this->findCountryStates($country);
    }

    /**
     * Get the name of a state by passing its two character code
     * Specifying a two character ISO country code will limit the search to a specific country
     */
    public function getStateName(string $lookFor, ?string $country = null): string
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
     * If $countries is null all countries will be searched, which can be slow.
     */
    public function getStateCode(string $lookFor, null|string|array $countries = null): ?string
    {
        $lookFor = mb_strtoupper($lookFor);

        if (is_null($countries)) {
            $countries = array_keys($this->countries);
        } elseif (is_string($countries)) {
            $countries = [$countries];
        }

        foreach ($countries as $countryCode) {
            $states = array_map('mb_strtoupper', $this->findCountryStates($countryCode));

            if ($code = array_search($lookFor, $states)) {
                return $code;
            }
        }

        return null;
    }

    /**
     * Change the default translation language using the three character ISO 639-3 code of the desired language.
     * Country name translations will be reloaded.
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;

        foreach ($this->countries as $country) {
            $this->countriesTranslated[$country->getIsoAlpha2()] = $country->getTranslation($this->language)['common'];
        }

        return $this;
    }

    protected function loadCountry($code): Country
    {
        try {
            return CountryLoader::country(mb_strtolower($code));
        } catch (Exception $e) {
            throw new Exceptions\CountryNotFoundException;
        }
    }

    protected function findCountryStates($country): array
    {
        if (! array_key_exists($country, $this->states)) {
            $this->addCountryStates($country);
        }

        return $this->states[$country];
    }

    protected function addCountryStates($country): void
    {
        if (! $country instanceof Country) {
            $country = $this->loadCountry($country);
        }

        $countryCode = $country->getIsoAlpha2();

        $this->states[$countryCode] = [];
        $states = $country->getDivisions();

        if (! is_null($states)) {
            foreach ($states as $code => $division) {
                $code = preg_replace('/([A-Z]{2}-)/', '', $code);
                $this->states[$countryCode][$code] = $division['name'];
            }
        }
    }
}
