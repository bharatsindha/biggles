<?php

namespace App\Modules\Depot\Providers;

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
        $this->loadTranslationsFrom(module_path('depot', 'Resources/Lang', 'app'), 'depot');
        $this->loadViewsFrom(module_path('depot', 'Resources/Views', 'app'), 'depot');
        $this->loadMigrationsFrom(module_path('depot', 'Database/Migrations', 'app'));
        if(!$this->app->configurationIsCached()) {
            $this->loadConfigsFrom(module_path('depot', 'Config', 'app'));
        }
        $this->loadFactoriesFrom(module_path('depot', 'Database/Factories', 'app'));
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
