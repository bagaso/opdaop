<?php

namespace App\Providers;

use App\Setting;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('settings', function ($app) {
            //return $app['cache']->remember('site.settings', 60, function () {
            $settings = Setting::where('id', 1);
            if($settings->exists()) {
                return json_decode($settings->first());
            }
            //});
        });
    }
}
