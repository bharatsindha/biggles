<?php

namespace App\Modules\Ancillaryservice\Providers;

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
        $this->loadTranslationsFrom(module_path('ancillaryservice', 'Resources/Lang', 'app'), 'ancillaryservice');
        $this->loadViewsFrom(module_path('ancillaryservice', 'Resources/Views', 'app'), 'ancillaryservice');
        $this->loadMigrationsFrom(module_path('ancillaryservice', 'Database/Migrations', 'app'));
        if(!$this->app->configurationIsCached()) {
            $this->loadConfigsFrom(module_path('ancillaryservice', 'Config', 'app'));
        }
        $this->loadFactoriesFrom(module_path('ancillaryservice', 'Database/Factories', 'app'));
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
