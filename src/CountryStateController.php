<?php

namespace DougSisk\CountryState;

use Illuminate\Routing\Controller;

class CountryStateController extends Controller
{
    public function getCountryStates($countryCode)
    {
        if ($states = \CountryState::getStates(strtoupper($countryCode))) {
            $return = [
                'states' => $states
            ];
        } else {
            $return = [
                'states' => null
            ];
        }
        return response()->json($return);
    }
}
