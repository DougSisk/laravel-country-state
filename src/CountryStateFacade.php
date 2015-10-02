<?php

namespace DougSisk\CountryState;

use Illuminate\Support\Facades\Facade;

class CountryStateFacade extends Facade
{
    /**
     * The name of the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'CountryState';
    }
}
