<?php

declare(strict_types=1);

namespace App\Facades;

use App\Modules\Content\Domain\DataContext;
use Illuminate\Support\Facades\Facade;

class DataContextFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DataContext::class;
    }
}
