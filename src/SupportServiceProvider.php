<?php

namespace Dainsys\Support;

use Livewire\Livewire;
use Illuminate\Pagination\Paginator;
use Dainsys\Support\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\Scheduling\Schedule;
use Dainsys\Support\Policies\DepartmentPolicy;
use Dainsys\Support\Console\Commands\InstallCommand;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class SupportServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        Department::class => DepartmentPolicy::class
    ];

    public function boot()
    {
        Model::preventLazyLoading(true);
        Paginator::useBootstrap();

        $this->registerPolicies();
        $this->registerEvents();
        $this->bootPublishableAssets();
        $this->bootLoads();
        $this->bootLivewireComponents();

        if ($this->app->runningInConsole() && !app()->isProduction()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }

        // $this->registerSchedulledCommands();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/support.php',
            'support'
        );
    }

    protected function bootPublishableAssets()
    {
        $this->publishes([
            __DIR__ . '/../config/support.php' => config_path('support.php')
        ], 'support:config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/dainsys/support')
        ], 'support:views');

        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/dainsys/support'),
        ], 'support:assets');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/support'),
        ], 'support:translations');
    }

    protected function bootLoads()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'support');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'support');
    }

    protected function registerSchedulledCommands()
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
        });
    }

    protected function registerEvents()
    {
    }

    protected function bootLivewireComponents()
    {
        Livewire::component('support::dashboard', \Dainsys\Support\Http\Livewire\Admin\Dashboard::class);

        Livewire::component('support::department.table', \Dainsys\Support\Http\Livewire\Department\Table::class);
        Livewire::component('support::department.index', \Dainsys\Support\Http\Livewire\Department\Index::class);
        Livewire::component('support::department.detail', \Dainsys\Support\Http\Livewire\Department\Detail::class);
        Livewire::component('support::department.form', \Dainsys\Support\Http\Livewire\Department\Form::class);
    }
}
