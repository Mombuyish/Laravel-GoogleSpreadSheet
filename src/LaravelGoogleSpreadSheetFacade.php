<?php

namespace Yish\LaravelGoogleSpreadSheet;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Yish\LaravelGoogleSpreadSheet\Skeleton\SkeletonClass
 */
class LaravelGoogleSpreadSheetFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-googlespreadsheet';
    }
}
