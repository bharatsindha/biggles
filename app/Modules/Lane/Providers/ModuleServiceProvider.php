<?php

namespace App\Modules\Lane\Providers;

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
        $this->loadTranslationsFrom(module_path('lane', 'Resources/Lang', 'app'), 'lane');
        $this->loadViewsFrom(module_path('lane', 'Resources/Views', 'app'), 'lane');
        $this->loadMigrationsFrom(module_path('lane', 'Database/Migrations', 'app'));
        if(!$this->app->configurationIsCached()) {
            $this->loadConfigsFrom(module_path('lane', 'Config', 'app'));
        }
        $this->loadFactoriesFrom(module_path('lane', 'Database/Factories', 'app'));
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
