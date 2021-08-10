<?php

namespace App\Modules\Move\Providers;

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
        $this->loadTranslationsFrom(module_path('move', 'Resources/Lang', 'app'), 'move');
        $this->loadViewsFrom(module_path('move', 'Resources/Views', 'app'), 'move');
        $this->loadMigrationsFrom(module_path('move', 'Database/Migrations', 'app'));
        if(!$this->app->configurationIsCached()) {
            $this->loadConfigsFrom(module_path('move', 'Config', 'app'));
        }
        $this->loadFactoriesFrom(module_path('move', 'Database/Factories', 'app'));
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
