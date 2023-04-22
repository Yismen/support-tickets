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
    ],
    'db_prefix' => 'support_',
];
