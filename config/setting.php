<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */

    'name' => env('APP_NAME', ''),
    'while_ip'=>env('WHILE_IP', ['127.0.0.1','192.168.1.33','205.147.102.6','111.93.195.78','192.168.1.31']),
    'pagination_limit' => env('PAGINATION_LIMIT', '10'),
    'app_name'=> env('APP_NAME', 'map ghana'),
    'app_env'=> env('APP_ENV', 'local'),
    'app_debug'=> env('APP_DEBUG', 'true'),
    'app_log_level'=> env('APP_LOG_LEVEL', 'debug'),
    'app_url'=> env('APP_URL', 'http://localhost/mapghana/public'),
    'mail_driver'=> env('MAIL_DRIVER', ''),
    'mail_host'=> env('MAIL_HOST', ''),
    'mail_port'=> env('MAIL_PORT', ''),
    'mail_username'=> env('MAIL_USERNAME', ''),
    'mail_password'=> env('MAIL_PASSWORD', ''),
    'mail_encryption'=> env('MAIL_ENCRYPTION', ''),
    'mail_from_address'=> env('MAIL_FROM_ADDRESS', ''),
    'mail_from_name'=> env('MAIL_FROM_NAME', ''),
    'app_url_android'=> env('APP_URL_ANDROID', ''),
    'app_url_ios'=> env('APP_URL_IOS', ''),
    'under_maintenance'=> env('UNDER_MAINTENANCE', 'false'),
    'app_logo'=> env('APP_LOGO', ''),
    'email'=> env('email', 'info@mapghana.com'),
    'mobile'=> env('mobile', '0302542364'),
    'phone'=> env('phone', '0244658978'),
    'address'=> env('address', '<p>First Floor</p> <p>Queensland Building</p> <p>Haatso, Accra</p>'),



];
