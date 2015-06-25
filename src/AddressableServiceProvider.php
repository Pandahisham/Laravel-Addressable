<?php

namespace DraperStudio\Addressable;

use Illuminate\Support\ServiceProvider;

class AddressableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $config = __DIR__.'/../config/addressable.php';

        $this->publishes([
            $config => config_path('draperstudio.addressable.php'),
        ], 'config');

        $this->mergeConfigFrom($config, 'draperstudio.addressable');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('/migrations'),
        ], 'migrations');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register('DraperStudio\Countries\CountriesServiceProvider');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['DraperStudio\Countries\CountriesServiceProvider'];
    }
}
