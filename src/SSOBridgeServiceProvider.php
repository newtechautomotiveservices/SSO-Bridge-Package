<?php

namespace Newtech\SSOBridge;

use Illuminate\Support\ServiceProvider;

class SSOBridgeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // register our controller
        $this->app->make('Newtech\SSOBridge\SSOBridgeController');
        $this->loadViewsFrom(__DIR__.'/views', 'ssobridge');
        $this->publishes([
            __DIR__ . '/config' => config_path('ssobridge')
        ], 'config');
        $this->publishes([
            __DIR__ . '/migrations' => $this->app->databasePath() . '/migrations'
        ], 'migrations');
        $this->app['router']->aliasMiddleware('ssobridge' , \Newtech\SSOBridge\Middleware\SSOAuthCheck::class);
        $this->app['router']->aliasMiddleware('ssoroutecheck' , \Newtech\SSOBridge\Middleware\SSORouteCheck::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes.php';
    }
}
