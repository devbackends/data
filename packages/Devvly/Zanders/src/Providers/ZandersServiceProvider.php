<?php

namespace Devvly\Zanders\Providers;

use Devvly\Zanders\Console\Commands\Import;
use Devvly\Zanders\Console\Commands\TmpQueue;
use Devvly\Zanders\Console\Commands\Update;
use Devvly\Zanders\Console\Commands\UpdateDistribute;
use Devvly\Zanders\Console\Commands\UpdateInventory;
use Devvly\Zanders\Services\Zanders;
use Illuminate\Support\ServiceProvider;


class ZandersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Load package migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        // Publish configuration file
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('zandersimport.php'),
        ]);

        // Set up singeltons
        $this->app->singleton(Zanders::class, function () {
            return new Zanders(config('zandersimport.diskName'));
        });

        // Register console command
        $this->commands([
            Import::class,
            Update::class,
            UpdateDistribute::class,
            TmpQueue::class,
            UpdateInventory::class,
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
