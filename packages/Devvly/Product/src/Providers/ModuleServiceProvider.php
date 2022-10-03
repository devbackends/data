<?php

namespace Devvly\Product\Providers;

use Devvly\Product\Models\Product;
use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Product::class,
    ];
}
