<?php

namespace Mathiasd88\Proceduribility;

use Illuminate\Support\ServiceProvider;

class ProceduribilityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /*$this->publishes([
            __DIR__.'/../../config/procedure.php' => config_path('procedure.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/procedure.php', 'procedure'
        );*/
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Mathiasd88\Proceduribility\StoredProcedure');
    }
}
