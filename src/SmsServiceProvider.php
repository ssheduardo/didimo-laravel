<?php


namespace Ssheduardo\Didimo;


use Didimo\Sms\Sms;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes(
            [
                __DIR__ . '/config/didimo.php' => config_path('didimo.php'),
            ], 'didimo'
        );
    }

    /**
     * Register the application services.
     *
     *
     */
    public function register()
    {
        $this->mergeConfigFrom( __DIR__ . '/config/didimo.php', 'didimo');
        $this->app->singleton('sms', function ($app) {
            $config = $app->make('config');
            $user = $config->get('didimo.user');
            $password =$config->get('didimo.password');
            return new Sms($user,$password);
        });

    }
}