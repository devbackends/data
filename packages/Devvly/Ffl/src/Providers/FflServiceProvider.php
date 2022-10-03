<?php

namespace Devvly\Ffl\Providers;



use Devvly\Ffl\Services\NutShell\Api;
use Devvly\Ffl\Services\NutShell\DiscoverUrl;
use DOMDocument;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use PHPHtmlParser\Dom;
use function foo\func;
use Devvly\Ffl\Console\Commands\EmailsAboutToExpireReminder;
use Devvly\Ffl\Console\Commands\SetFflsDailyCallsAllowedNumberToZero;
/**
 * FflServiceProvider service provider
 *
 * @author    2AData <moe@2acommerce.com>
 * @copyright 2022 devvly (http://www.devvly.com)
 */
class FflServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../Http/routes.php';
        $this->publishes([
          __DIR__ . '/../../publishable/assets' => public_path('vendor/devvly/ffl/assets'),
          ], 'public');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'ffl');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'ffl');
        $this->register();
      // Register console command
      $this->commands([
        EmailsAboutToExpireReminder::class,
        SetFflsDailyCallsAllowedNumberToZero::class
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
