<?php

namespace Selfreliance\fixroles;

use Illuminate\Support\ServiceProvider;

class RolesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/roles.php' => config_path('roles.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/seeds/CreateOrAttachAdmin.php' => database_path('seeds/CreateOrAttachAdmin.php')
        ], 'seed');

        $this->loadMigrationsFrom(__DIR__.'/migrations');
        
        $this->registerBladeExtensions();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/roles.php', 'roles');
    }

    /**
     * Register Blade extensions.
     *
     * @return void
     */
    protected function registerBladeExtensions()
    {
        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();
        
        $blade->directive('checkrole', function ($prefix) {
            return "<?php if (Auth::check() && Auth::user()->checkRole($prefix, false)): ?>";
        });

        $blade->directive('endcheck', function () {
            return "<?php endif; ?>";
        });
    }
}
