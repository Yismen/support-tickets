<?php

namespace Dainsys\Support;

use Livewire\Livewire;
use Dainsys\Support\Models\Ticket;
use Illuminate\Foundation\Auth\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event;
use Dainsys\Support\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\Scheduling\Schedule;
use Dainsys\Support\Events\ReplyCreatedEvent;
use Dainsys\Support\Policies\DepartmentPolicy;
use Dainsys\Support\Console\Commands\InstallCommand;
use Dainsys\Support\Console\Commands\CreateSuperUser;
use Dainsys\Support\Console\Commands\UpdateTicketStatus;
use Dainsys\Support\Listeners\SendReplyCreatedNotification;
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

        $this->bootEvents();
        $this->bootPublishableAssets();
        $this->bootLoads();
        $this->bootLivewireComponents();
        $this->bootGates();
        $this->boostCommandsAndSchedules();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/support.php',
            'support'
        );
    }

    protected function bootGates()
    {
        Gate::before(function ($user, $ability) {
            return $user->isSupportSuperAdmin() ? true : null;
        });

        Gate::define('own-ticket', function (User $user, Ticket $ticket) {
            return $ticket->created_by === $user->id;
        });

        Gate::define('grab-ticket', function (User $user, Ticket $ticket) {
            $department = $ticket->department;

            return $user->isDepartmentAdmin($department) || $user->isDepartmentAgent($department);
        });

        Gate::define('assign-ticket', function (User $user, Ticket $ticket) {
            $department = $ticket->department;

            return $user->isDepartmentAdmin($department);
        });

        Gate::define('close-ticket', function (User $user, Ticket $ticket) {
            $department = $ticket->department;

            if (!$department) {
                return false;
            }

            return $user->id === $ticket->created_by || $user->isDepartmentAdmin($department) || $user->isDepartmentAgent($department);
        });

        Gate::define('view-dashboards', function (User $user) {
            return $user->isSupportSuperAdmin()
                || $user->hasAnyDepartmentRole()
                ;
        });
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

    protected function boostCommandsAndSchedules()
    {
        if ($this->app->runningInConsole() && !app()->isProduction()) {
            $this->commands([
                InstallCommand::class,
                CreateSuperUser::class,
                UpdateTicketStatus::class,
            ]);
        }

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command(UpdateTicketStatus::class)
               ->timezone('America/New_York')
               ->everyThirtyMinutes();
        });
    }

    protected function bootEvents()
    {
        // Event::listen(ReplyCreatedEvent::class,  SendReplyCreatedNotification::class);
        Event::listen(ReplyCreatedEvent::class, SendReplyCreatedNotification::class);
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

        Livewire::component('support::support_super_admin.index', \Dainsys\Support\Http\Livewire\SupportSuperAdmin\Index::class);

        Livewire::component('support::department_role.index', \Dainsys\Support\Http\Livewire\DepartmentRole\Index::class);
        Livewire::component('support::department_role.table', \Dainsys\Support\Http\Livewire\DepartmentRole\Table::class);
        Livewire::component('support::department_role.form', \Dainsys\Support\Http\Livewire\DepartmentRole\Form::class);

        Livewire::component('support::ticket.user.table', \Dainsys\Support\Http\Livewire\Ticket\User\Table::class);
        Livewire::component('support::ticket.user.index', \Dainsys\Support\Http\Livewire\Ticket\User\Index::class);
        Livewire::component('support::ticket.user.detail', \Dainsys\Support\Http\Livewire\Ticket\User\Detail::class);
        Livewire::component('support::ticket.user.form', \Dainsys\Support\Http\Livewire\Ticket\User\Form::class);

        Livewire::component('support::ticket.department.index', \Dainsys\Support\Http\Livewire\Ticket\Department\Index::class);
        Livewire::component('support::ticket.department.table', \Dainsys\Support\Http\Livewire\Ticket\Department\Table::class);

        Livewire::component('support::ticket.close', \Dainsys\Support\Http\Livewire\Ticket\CloseTicket::class);

        Livewire::component('support::reply.form', \Dainsys\Support\Http\Livewire\Reply\Form::class);
    }
}
