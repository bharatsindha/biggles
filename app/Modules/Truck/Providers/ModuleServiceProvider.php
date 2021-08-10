<?php

namespace App\Modules\Truck\Providers;

use Caffeinated\Modules\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(module_path('truck', 'Resources/Lang', 'app'), 'truck');
        $this->loadViewsFrom(module_path('truck', 'Resources/Views', 'app'), 'truck');
        $this->loadMigrationsFrom(module_path('truck', 'Database/Migrations', 'app'));
        if(!$this->app->configurationIsCached()) {
            $this->loadConfigsFrom(module_path('truck', 'Config', 'app'));
        }
        $this->loadFactoriesFrom(module_path('truck', 'Database/Factories', 'app'));
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
