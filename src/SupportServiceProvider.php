<?php

namespace Dainsys\Support;

use Livewire\Livewire;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Dainsys\Support\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\Scheduling\Schedule;
use Dainsys\Support\Policies\DepartmentPolicy;
use Dainsys\Support\Console\Commands\InstallCommand;
use Dainsys\Support\Console\Commands\CreateSuperUser;
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

        // Gate::before(function ($user, $ability) {
        //     return $user->isSuperAdmin() ? true : null;
        // });

        if ($this->app->runningInConsole() && !app()->isProduction()) {
            $this->commands([
                InstallCommand::class,
                CreateSuperUser::class,
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
            __DIR__ . '/../resources/lang' => $this->app->langPath('vendor/support'),
        ], 'support:translations');
    }

    protected function bootLoads()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'support');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'support');
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

        Livewire::component('support::reason.table', \Dainsys\Support\Http\Livewire\Reason\Table::class);
        Livewire::component('support::reason.index', \Dainsys\Support\Http\Livewire\Reason\Index::class);
        Livewire::component('support::reason.detail', \Dainsys\Support\Http\Livewire\Reason\Detail::class);
        Livewire::component('support::reason.form', \Dainsys\Support\Http\Livewire\Reason\Form::class);

        Livewire::component('support::super_admin.index', \Dainsys\Support\Http\Livewire\SuperAdmin\Index::class);

        Livewire::component('support::department_role.index', \Dainsys\Support\Http\Livewire\DepartmentRole\Index::class);
        Livewire::component('support::department_role.table', \Dainsys\Support\Http\Livewire\DepartmentRole\Table::class);
        Livewire::component('support::department_role.form', \Dainsys\Support\Http\Livewire\DepartmentRole\Form::class);

        Livewire::component('support::ticket.user.table', \Dainsys\Support\Http\Livewire\Ticket\User\Table::class);
        Livewire::component('support::ticket.user.index', \Dainsys\Support\Http\Livewire\Ticket\User\Index::class);
        Livewire::component('support::ticket.user.detail', \Dainsys\Support\Http\Livewire\Ticket\User\Detail::class);
        Livewire::component('support::ticket.user.form', \Dainsys\Support\Http\Livewire\Ticket\User\Form::class);
    }
}
