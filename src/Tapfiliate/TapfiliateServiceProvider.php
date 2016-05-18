<?php

namespace Gentor\Tapfiliate;

use Illuminate\Support\ServiceProvider;

class TapfiliateServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('gentor/tapfiliate');

        // Temp to use in closure.
        $app = $this->app;
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register providers.
        $this->app['tapfiliate'] = $this->app->share(function ($app) {
            return new Tapfiliate($app['config']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('tapfiliate');
    }

}