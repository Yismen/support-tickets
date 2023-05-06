<?php

return [
    /**
    * Here you can specify a list of middleware to apply to
    * all routes. use "," or "|" to separate the list.
    */
    'midlewares' => [
        'api' => 'api',
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
    'images_disk' => env('SUPPORT_IMAGES_DISK', 'public')
];
