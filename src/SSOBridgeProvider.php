<?php
namespace Newtech\SSOBridge;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use Newtech\SSOBridge\SSOUserProvider;
use Newtech\SSOBridge\SSOGuard;
use Newtech\SSOBridge\SSOUser;

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

        $this->app->make('Newtech\SSOBridge\SSOController');
        $this->publishes([__DIR__ . '/config/sso.php' => config_path('sso.php')], 'config');
        $this->publishes([__DIR__.'/resources/views/errors' => resource_path('views/errors'),]);

        $this->app['router']->aliasMiddleware('sso' , \Newtech\SSOBridge\SSORouteAccess::class);
        $this->app['router']->aliasMiddleware('auth' , \Newtech\SSOBridge\SSOAuthenticate::class);
        if(!$this->app['config']->has("auth.guards.sso")){
            $this->app['config']->set("auth.guards.sso", Array('driver' => 'sso', 'provider' => 'sso'));
        }
        if(!$this->app['config']->has("auth.providers.sso")){
            $this->app['config']->set("auth.providers.sso", Array('driver' => 'sso'));
        }

        Gate::define('sso', function(SSOUser $user, ...$action){
            if(is_array($action)){
               return ! empty(array_intersect($user->permissions, $action));
            }else if(is_string($action)){
                return in_array($action, $user->permissions);
            }else{
                throw new \Exception("Invalid parameter passed to SSO gate");
            }

        });



        Auth::provider('sso', function($app, array $config) {
            return new SSOUserProvider();
        });

        Auth::extend('sso', function ($app, $name, array $config) {
            return new SSOGuard(Auth::createUserProvider($config['provider']), $this->app['request']);
        });
    }
}
