<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->group(function () {
        Route::as('support.')
            ->prefix('support')
            ->group(function () {
                Route::get('', \Dainsys\Support\Http\Livewire\Dashboard::class)->name('home');
                Route::get('admin', \Dainsys\Support\Http\Livewire\Dashboard::class)->name('admin');
                Route::get('tickets', \Dainsys\Support\Http\Livewire\Dashboard::class)->name('tickets');

                Route::as('admin.')
                    ->group(function () {
                        Route::get('departments', \Dainsys\Support\Http\Livewire\Department\Index::class)->name('departments.index');
                        Route::get('reasons', \Dainsys\Support\Http\Livewire\Reason\Index::class)->name('reasons.index');

                        Route::get('super_admins', \Dainsys\Support\Http\Livewire\SuperAdmin\Index::class)->name('super_admins.index');

                        Route::get('department_roles', \Dainsys\Support\Http\Livewire\DepartmentRole\Index::class)->name('department_roles.index');
                    });
            });
    });
