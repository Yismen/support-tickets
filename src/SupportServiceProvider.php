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
use Dainsys\Support\Policies\DepartmentPolicy;
use Dainsys\Support\Console\Commands\InstallCommand;
use Dainsys\Support\Console\Commands\CreateSuperUser;
use Dainsys\Support\Console\Commands\UpdateTicketStatus;
use Dainsys\Support\Console\Commands\SendTicketsExpiredReport;
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
            $path = str(request()->path());

            if (
                $path->startsWith('support') ||
                $path->beforeLast('::')->endsWith('support')
            ) {
                return $user->isSupportSuperAdmin() ? true : null;
            }
        });

        Gate::define('own-ticket', function (User $user, Ticket $ticket) {
            return $ticket->created_by === $user->id;
        });

        Gate::define('grab-ticket', function (User $user, Ticket $ticket) {
            return !$ticket->department
                ? false
                : $user->isDepartmentAdmin($ticket->department) || $user->isDepartmentAgent($ticket->department);
        });

        Gate::define('assign-ticket', function (User $user, Ticket $ticket) {
            return !$ticket->department
                ? false
                : $user->isDepartmentAdmin($ticket->department);
        });

        Gate::define('rate-ticket', function (User $user, Ticket $ticket) {
            return $ticket->created_by === $user->id;
        });

        Gate::define('reopen-ticket', function (User $user, Ticket $ticket) {
            return !$ticket->department
                ? false
                : $user->isDepartmentAdmin($ticket->department) || $ticket->created_by === auth()->user()->id;
        });

        Gate::define('close-ticket', function (User $user, Ticket $ticket) {
            return !$ticket->department
                ? false
                : $user->id === $ticket->created_by || $user->isDepartmentAdmin($ticket->department) || $user->isDepartmentAgent($ticket->department);
        });

        Gate::define('view-dashboards', function (User $user) {
            return $user->hasAnyDepartmentRole() || $user->isSupportSuperAdmin()
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
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                CreateSuperUser::class,
                UpdateTicketStatus::class,
                SendTicketsExpiredReport::class
            ]);
        }

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command(UpdateTicketStatus::class)
               ->timezone('America/New_York')
               ->everyThirtyMinutes();

            $schedule->command(SendTicketsExpiredReport::class)
                ->timezone('America/New_York')
                ->dailyAt('08');
        });
    }

    protected function bootEvents()
    {
        Event::listen(\Dainsys\Support\Events\ReplyCreatedEvent::class, \Dainsys\Support\Listeners\SendReplyCreatedMail::class);
        Event::listen(\Dainsys\Support\Events\RatingCreatedEvent::class, \Dainsys\Support\Listeners\SendRatingCreatedMail::class);
        Event::listen(\Dainsys\Support\Events\TicketCreatedEvent::class, \Dainsys\Support\Listeners\SendTicketCreatedMail::class);
        Event::listen(\Dainsys\Support\Events\TicketAssignedEvent::class, \Dainsys\Support\Listeners\SendTicketAssignedMail::class);
        Event::listen(\Dainsys\Support\Events\TicketCompletedEvent::class, \Dainsys\Support\Listeners\SendTicketCompletedMail::class);
        Event::listen(\Dainsys\Support\Events\TicketDeletedEvent::class, \Dainsys\Support\Listeners\SendTicketDeletedMail::class);
        Event::listen(\Dainsys\Support\Events\TicketReopenedEvent::class, \Dainsys\Support\Listeners\SendTicketReopenedMail::class);

        Event::listen(\Illuminate\Mail\Events\MessageSent::class, \Dainsys\Support\Listeners\RemoveMailAttachment::class);
    }

    protected function bootLivewireComponents()
    {
        Livewire::component('support::dashboard', \Dainsys\Support\Http\Livewire\Admin\Dashboard::class);

        Livewire::component('support::department.table', \Dainsys\Support\Http\Livewire\Department\Table::class);
        Livewire::component('support::department.index', \Dainsys\Support\Http\Livewire\Department\Index::class);
        Livewire::component('support::department.detail', \Dainsys\Support\Http\Livewire\Department\Detail::class);
        Livewire::component('support::department.form', \Dainsys\Support\Http\Livewire\Department\Form::class);

        Livewire::component('support::subject.table', \Dainsys\Support\Http\Livewire\Subject\Table::class);
        Livewire::component('support::subject.index', \Dainsys\Support\Http\Livewire\Subject\Index::class);
        Livewire::component('support::subject.detail', \Dainsys\Support\Http\Livewire\Subject\Detail::class);
        Livewire::component('support::subject.form', \Dainsys\Support\Http\Livewire\Subject\Form::class);

        Livewire::component('support::support_super_admin.index', \Dainsys\Support\Http\Livewire\SupportSuperAdmin\Index::class);

        Livewire::component('support::department_role.index', \Dainsys\Support\Http\Livewire\DepartmentRole\Index::class);
        Livewire::component('support::department_role.table', \Dainsys\Support\Http\Livewire\DepartmentRole\Table::class);
        Livewire::component('support::department_role.form', \Dainsys\Support\Http\Livewire\DepartmentRole\Form::class);

        Livewire::component('support::ticket.table', \Dainsys\Support\Http\Livewire\Ticket\Table::class);
        Livewire::component('support::ticket.index', \Dainsys\Support\Http\Livewire\Ticket\Index::class);
        Livewire::component('support::ticket.detail', \Dainsys\Support\Http\Livewire\Ticket\Detail::class);
        Livewire::component('support::ticket.form', \Dainsys\Support\Http\Livewire\Ticket\Form::class);

        Livewire::component('support::dashboard.index', \Dainsys\Support\Http\Livewire\Dashboard\Index::class);
        Livewire::component('support::dashboard.table', \Dainsys\Support\Http\Livewire\Dashboard\Table::class);

        Livewire::component('support::ticket.close', \Dainsys\Support\Http\Livewire\Ticket\CloseTicket::class);
        Livewire::component('support::ticket.rating', \Dainsys\Support\Http\Livewire\Ticket\RateTicket::class);

        Livewire::component('support::reply.form', \Dainsys\Support\Http\Livewire\Reply\Form::class);

        Livewire::component('support::charts.weekly-tickets-count', \Dainsys\Support\Http\Livewire\Charts\WeeklyTicketsCountChart::class);
        Livewire::component('support::charts.weekly-tickets-count-by-subject', \Dainsys\Support\Http\Livewire\Charts\WeeklyTicketsCountBySubjectChart::class);
        Livewire::component('support::charts.weekly-tickets-completion-rate', \Dainsys\Support\Http\Livewire\Charts\WeeklyTicketsCompletionRateChart::class);
        Livewire::component('support::charts.weekly-tickets-compliance-rate', \Dainsys\Support\Http\Livewire\Charts\WeeklyTicketsComplianceRateChart::class);
        Livewire::component('support::charts.weekly-tickets-satisfaction-rate', \Dainsys\Support\Http\Livewire\Charts\WeeklyTicketsSatisfactionRateChart::class);
    }
}
