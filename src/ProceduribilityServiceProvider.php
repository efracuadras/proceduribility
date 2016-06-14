<?php namespace Mathiasd88\Proceduribility;

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
        $configPath = __DIR__ . '/../config/procedure.php';

        $this->publishes([
            $configPath => config_path('procedure.php'),
        ]);

        $this->mergeConfigFrom($configPath, 'procedure');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['storedprocedure'] = $this->app->share(function($app) {
            return new StoredProcedure;
        });
    }
}
