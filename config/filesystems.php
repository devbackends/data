<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'public'),
    'cloud' => 'wassabi',
    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],
        'public_image' => [
          'driver'     => 'local',
          'root'       => public_path(''),
          'url'        => env('APP_URL') . '/public',
          'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL')
        ],
        'wassabi'    => [
          'driver'   => 's3',
          'key'      => env('WAS_ACCESS_KEY_ID'),
          'secret'   => env('WAS_SECRET_ACCESS'),
          'region'   => env('WAS_DEFAULT_REGION'),
          'bucket'   => env('WAS_BUCKET'),
          'endpoint' => env('WAS_URL'),
        ],

        'distributor_import' => [
          'driver'   => 'ftp',
          'host'     => env('DISTRIBUTOR_IMPORT_URL'),
          'username' => env('DISTRIBUTOR_IMPORT_USER'),
          'password' => env('DISTRIBUTOR_IMPORT_PASSWORD'),
        ],
        'zanders_import' => [
          'driver'   => 'ftp',
          'host'     => env('ZANDERS_IMPORT_URL'),
          'username' => env('ZANDERS_IMPORT_USER'),
          'password' => env('ZANDERS_IMPORT_PASSWORD'),
        ]

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
