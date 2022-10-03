<?php

namespace Devvly\Product\Providers;

use Illuminate\Support\ServiceProvider;
use PHPHtmlParser\Dom;
use function foo\func;
use Devvly\Product\Console\Commands\CheckDistributorsProducts;
/**
 * ProductServiceProvider service provider
 *
 * @author    2AData <moe@2acommerce.com>
 * @copyright 2022 devvly (http://www.devvly.com)
 */
class ProductServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../Http/routes.php';

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'products');
        $this->register();

      // Register console command
      $this->commands([
        CheckDistributorsProducts::class
      ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }
}
