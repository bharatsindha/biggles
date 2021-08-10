<?php

namespace App\Modules\Configuration\Providers;

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
        $this->loadTranslationsFrom(module_path('configuration', 'Resources/Lang', 'app'), 'configuration');
        $this->loadViewsFrom(module_path('configuration', 'Resources/Views', 'app'), 'configuration');
        $this->loadMigrationsFrom(module_path('configuration', 'Database/Migrations', 'app'));
        if(!$this->app->configurationIsCached()) {
            $this->loadConfigsFrom(module_path('configuration', 'Config', 'app'));
        }
        $this->loadFactoriesFrom(module_path('configuration', 'Database/Factories', 'app'));
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
