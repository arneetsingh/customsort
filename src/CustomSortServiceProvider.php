<?php

namespace Arni\CustomSort;

use Illuminate\Support\ServiceProvider;

class CustomSortServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'arneetsingh');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'arneetsingh');
         $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
//        $this->mergeConfigFrom(__DIR__.'/../config/customsort.php', 'customsort');
//
//        // Register the service the package provides.
//        $this->app->singleton('customsort', function ($app) {
//            return new customsort;
//        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['customsort'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/customsort.php' => config_path('customsort.php'),
        ], 'customsort.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/arneetsingh'),
        ], 'customsort.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/arneetsingh'),
        ], 'customsort.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/arneetsingh'),
        ], 'customsort.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
