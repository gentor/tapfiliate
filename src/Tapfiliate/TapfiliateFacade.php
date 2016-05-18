<?php

namespace Gentor\Tapfiliate;

use Illuminate\Support\Facades\Facade;

class TapfiliateFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'tapfiliate';
    }

}