<?php

namespace App\Modules\Company\Providers;

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
        $this->loadTranslationsFrom(module_path('company', 'Resources/Lang', 'app'), 'company');
        $this->loadViewsFrom(module_path('company', 'Resources/Views', 'app'), 'company');
        $this->loadMigrationsFrom(module_path('company', 'Database/Migrations', 'app'));
        if(!$this->app->configurationIsCached()) {
            $this->loadConfigsFrom(module_path('company', 'Config', 'app'));
        }
        $this->loadFactoriesFrom(module_path('company', 'Database/Factories', 'app'));
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
