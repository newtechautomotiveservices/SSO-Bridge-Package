<?php
namespace Newtech\NTAPIBridge;

use Illuminate\Support\ServiceProvider;

class NTAPIBridgeProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->publishes([__DIR__ . '/config/nt-api.php' => config_path('nt-api.php')], 'config');
    }
}
