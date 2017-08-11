<?php
return [

    'default' => 'eloquent',

    'drivers' => [
        'eloquent' => [
            'driver' => \CrCms\Repository\Drives\Eloquent\Eloquent::class,
            'query' => \CrCms\Repository\Drives\Eloquent\QueryRelate::class,
        ],
        'elasticsearch' => [
            'driver' => \CrCms\Repository\Drives\ElasticSearch\ElasticSearch::class,
            'query' => \CrCms\Repository\Drives\ElasticSearch\QueryRelate::class,
        ],
    ],

    'repository_namespace' => 'App\Repositories',

    'repository_path' => app_path('Repositories'),

    'magic_namespace' => 'App\Repositories\Magic',

    'magic_path' => app_path('Repositories\Magic'),

    'listen' => [
        'creating' => [
            //\CrCms\Repository\Listeners\RepositoryListener::class . '@creating',
        ],
        'created' => [],
        'updating' => [],
        'updated' => [],
        'deleting' => [],
        'deleted' => [],
        'saving' => [],
        'saved' => [],
    ],
];