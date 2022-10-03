<?php

namespace Devvly\Core\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Devvly\Core\Models\Country;
use Devvly\Core\Models\CountryState;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Country::class,
        CountryState::class
    ];
}