<?php namespace Mathiasd88\Proceduribility\Facades; 

use Illuminate\Support\Facades\Facade;

class Procedure extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'storedprocedure';
    }
}