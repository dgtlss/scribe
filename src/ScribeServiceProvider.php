<?php

namespace Dgtlss\Scribe;

use Illuminate\Support\ServiceProvider;

class ScribeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'scribe');
        
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/scribe'),
        ], 'scribe-views');

        $this->publishes([
            __DIR__.'/../config/scribe.php' => config_path('scribe.php'),
        ], 'scribe-config');

        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/scribe'),
        ], 'scribe-assets');

        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/scribe.php', 'scribe'
        );
    }
}