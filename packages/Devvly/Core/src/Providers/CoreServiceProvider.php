<?php

namespace Devvly\Core\Providers;

use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\AliasLoader;
use Devvly\Core\Facades\Core as CoreFacade;
use Devvly\Core\Repositories\CountryRepository;
use Devvly\Core\Repositories\CountryStateRepository;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../Http/helpers.php';
        include __DIR__ . '/../Http/routes.php';
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->registerEloquentFactoriesFrom(__DIR__ . '/../Database/Factories');


        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'core');

        $this->publishes([
            dirname(__DIR__) . '/Config/concord.php' => config_path('concord.php'),
        ]);

        $this->publishes([
        __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
      ], 'public');


    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFacades();
        $this->registerCommands();
    }

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('core', CoreFacade::class);

        $this->app->singleton(Core::class, function ($app) {
            return new Core(
                $app->make(CountryRepository::class),
                $app->make(CountryStateRepository::class),
            );
        });
    }

    /**
     * Register factories.
     *
     * @param string $path
     *
     * @return void
     */
    protected function registerEloquentFactoriesFrom($path): void
    {
        $this->app->make(EloquentFactory::class)->load($path);
    }

    /**
     * Register the console commands of this package
     *
     * @return void
     */
    protected function registerCommands(): void
    {

    }
}
