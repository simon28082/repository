<?php
return [

    'repository_namespace' => 'App\Repositories',

    'repository_path' => app_path('Repositories'),

    'magic_namespace' => 'App\Repositories\Magic',

    'magic_path' => app_path('Repositories\Magic'),

    'listen'=>[
        'creating'=>[
            \CrCms\Repository\Listener\Listener::class.'@testCreate',
        ],
        'created'=>[

        ],
        'updating'=>[],
        'updated'=>[],
        'deleting'=>[],
        'deleted'=>[],
        'saving'=>[],
        'saved'=>[],
    ],

];