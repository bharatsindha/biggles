<?php

namespace App\Modules\Trip\Providers;

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
        $this->loadTranslationsFrom(module_path('trip', 'Resources/Lang', 'app'), 'trip');
        $this->loadViewsFrom(module_path('trip', 'Resources/Views', 'app'), 'trip');
        $this->loadMigrationsFrom(module_path('trip', 'Database/Migrations', 'app'));
        if(!$this->app->configurationIsCached()) {
            $this->loadConfigsFrom(module_path('trip', 'Config', 'app'));
        }
        $this->loadFactoriesFrom(module_path('trip', 'Database/Factories', 'app'));
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
