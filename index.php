<?php

use Pagekit\Application;
use Spqr\Markoldcontent\Plugin\MarkoldcontentPlugin;


return [
    'name' => 'spqr/markoldcontent',
    'type' => 'extension',
    'main' => function (Application $app) {
    
    },
    
    'autoload' => [
        'Spqr\\Markoldcontent\\' => 'src',
    ],
    
    'routes' => [],
    
    'widgets' => [],
    
    'menu' => [],
    
    'permissions' => [],
    
    'settings' => 'markoldcontent-settings',
    
    'resources' => [
        'spqr/markoldcontent:' => '',
    ],
    
    'config' => [
        'autoinsert' => true,
        'period'     => 365,
        'position'   => 'top',
        'message'       => '<div>This article has not been updated for a long time and may be outdated</div>',
    ],
    
    'events' => [
        'boot'         => function ($event, $app) {
            $app->subscribe(new MarkoldcontentPlugin);
        },
        'site'         => function ($event, $app) {
        },
        'view.scripts' => function ($event, $scripts) use ($app) {
            $scripts->register('markoldcontent-settings',
                'spqr/markoldcontent:app/bundle/markoldcontent-settings.js',
                ['~extensions', 'editor']);
        },
    ],
];