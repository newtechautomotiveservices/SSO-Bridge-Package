<?php
namespace Newtech\SSOBridge;

use Illuminate\Support\ServiceProvider;

class SSOBridgeProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Bootstrap code here.
        include __DIR__.'/routes/web.php';
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->commands([
            \Newtech\SSOBridge\App\Console\Commands\SSOSetup::class,
        ]);
        $this->app->make('Newtech\SSOBridge\App\Http\Controllers\SSOController');
        $this->publishes([__DIR__ . '/config' => config_path('ssobridge')], 'config');

        $this->publishes([
            __DIR__ . '/database/migrations' => $this->app->databasePath() . '/migrations'
        ], 'migrations');

        $this->app['router']->aliasMiddleware('ssobridge' , \Newtech\SSOBridge\App\Http\Middleware\SSOAuth::class);
        $this->app['router']->aliasMiddleware('ssoroutecheck' , \Newtech\SSOBridge\App\Http\Middleware\SSORoute::class);
    }
}
