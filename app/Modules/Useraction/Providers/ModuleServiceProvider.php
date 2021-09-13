<?php

namespace App\Modules\Useraction\Providers;

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
        $this->loadTranslationsFrom(module_path('useraction', 'Resources/Lang', 'app'), 'useraction');
        $this->loadViewsFrom(module_path('useraction', 'Resources/Views', 'app'), 'useraction');
        $this->loadMigrationsFrom(module_path('useraction', 'Database/Migrations', 'app'));
        if(!$this->app->configurationIsCached()) {
            $this->loadConfigsFrom(module_path('useraction', 'Config', 'app'));
        }
        $this->loadFactoriesFrom(module_path('useraction', 'Database/Factories', 'app'));
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
