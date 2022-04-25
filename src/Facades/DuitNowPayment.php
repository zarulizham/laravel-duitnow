<?php

namespace ZarulIzham\DuitNowPayment\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ZarulIzham\DuitNowPayment\DuitNowPayment
 */
class DuitNowPayment extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \ZarulIzham\DuitNowPayment\DuitNowPayment::class;
    }
}
