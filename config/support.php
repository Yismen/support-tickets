<?php

return [
    /**
    * Here you can specify a list of middleware to apply to
    * all routes. use "," or "|" to separate the list.
    */
    'middlewares' => [
        'api' => 'api',
        'web' => [
            'web',
            'auth',
            // 'verified',
        ]
    ],
    /**
     * All database tables will be prefixed with this
     * value. Use before running migrations.
     */
    'db_prefix' => env('SUPPORT_DB_PREFIX', 'support_'),
    /**
     * Define a disk where package immages will
     * be saved to and retrieved from.
     */
    'images_disk' => env('SUPPORT_IMAGES_DISK', 'public'),
    /**
     * The amount of time to wait before refreshing components. Please refer to
     * https://laravel-livewire.com/docs/2.x/polling. Adjust wiselly.
     */
    'polling_miliseconds' => 60000, // 2 minutes
    // email.include_current_user
    'email' => [
        /**
         * If set to true, the person creating the action that fires the email will also be copied.
         */
        'include_current_user' => true
    ],

    /**
     * Configurations to use in the dashboard
     */
    'dashboard' => [
        // The max amount of weeks to show
        'weeks' => 12,
        'context' => [
            'good' => 0.9,
            'regular' => 0.75
        ]
    ]
];
