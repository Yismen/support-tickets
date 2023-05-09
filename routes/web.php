<?php

use Illuminate\Support\Facades\Route;
use Dainsys\Support\Http\Controllers\HomeController;

Route::middleware(['web', 'auth'])
    ->group(function () {
        Route::as('support.')
            ->prefix('support')
            ->group(function () {
                Route::get('', HomeController::class)->name('home');
                // Route::get('', \Dainsys\Support\Http\Livewire\Dashboard::class)->name('home');
                Route::get('admin', HomeController::class)->name('admin');
                Route::get('my_tickets', \Dainsys\Support\Http\Livewire\Ticket\Index::class)->name('my_tickets');
                Route::get('dashboard', \Dainsys\Support\Http\Livewire\Dashboard::class)->name('dashboard');

                Route::as('admin.')
                    ->group(function () {
                        Route::get('departments', \Dainsys\Support\Http\Livewire\Department\Index::class)->name('departments.index');

                        Route::get('reasons', \Dainsys\Support\Http\Livewire\Reason\Index::class)->name('reasons.index');

                        Route::get('support_super_admins', \Dainsys\Support\Http\Livewire\SupportSuperAdmin\Index::class)->name('support_super_admins.index');

                        Route::get('department_roles', \Dainsys\Support\Http\Livewire\DepartmentRole\Index::class)->name('department_roles.index');
                    });
            });
    });
