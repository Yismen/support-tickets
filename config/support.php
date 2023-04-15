<?php
/**
 * Only super users have access to the admin panel. Provide a string of super user
 * emails separate by comma (,) or pipe(|).
 */
return [
    /**
    * Here you can specify a list of middleware to apply to
    * all routes. use "," or "|" to separate the list.
    */
    'midlewares' => [
        'api' => 'api',
        'web' => 'auth',
    ],
    'db_prefix' => 'support_',
    'routes_prefix' => [
        'guest' => 'support',
        'admin' => 'support/admin'
    ],
    'seeds' => [
        'termination_types' => [],
        'termination_reasons' => [],
        'suspension_types' => [],
        'citizenships' => [],
        'departments' => [],
        'payment_types' => [],
    ],
    'layout' => env('LAYOUT_VIEW', 'support::layouts.app')
];
