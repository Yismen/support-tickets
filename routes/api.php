<?php

use Dainsys\Support\Models\Form;
use Dainsys\Support\Models\Entry;
use Dainsys\Support\Models\Response;
use Illuminate\Support\Facades\Route;
use Dainsys\Support\Http\Resources\FormResource;
use Dainsys\Support\Http\Resources\EntryResource;

Route::middleware(['api'])->group(function () {
    // Auth Routes
    Route::as('support.api.')
        ->prefix('support/api')
        ->middleware(
            preg_split('/[,|]+/', config('support.midlewares.api'), -1, PREG_SPLIT_NO_EMPTY)
        )->group(function () {
            // Route::get('form/{form}', function ($form) {
            //     return new FormResource(Form::with('responses')->find($form));
            // })->name('form.show');
            // Route::get('entries/{entry}', function ($entry) {
            //     return new EntryResource(Entry::with('form', 'responses')->findOrFail($entry));
            // })->name('entries.show');
            // Route::get('responses/entry/{entry}', ['data' => 'response by entry'])->name('responses.entry.show');
        });
});
