<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    // Guest Routes
    Route::as('support.')
    ->prefix('support')
    ->group(function () {
        Route::get('', \Dainsys\Support\Http\Controllers\AboutController::class);
        Route::get('admin', \Dainsys\Support\Http\Controllers\AboutController::class);
        Route::get('about', \Dainsys\Support\Http\Controllers\AboutController::class)->name('about');
    });
    // Auth Routes
    Route::as('support.admin.')
        ->prefix(config('support.routes_prefix.admin'))
        ->middleware(
            preg_split('/[,|]+/', config('support.midlewares.web'), -1, PREG_SPLIT_NO_EMPTY)
        )->group(function () {
            Route::get('dashboard', \Dainsys\Support\Http\Livewire\Dashboard::class)->name('dashboard');

            // Route::get('super_admins', \Dainsys\Support\Http\Livewire\SuperAdmin\Index::class)
            //         ->name('super_admins.index');

                    Route::get('departments', \Dainsys\Support\Http\Livewire\Department\Index::class)
                            ->name('departments.index');
        });
});
