<?php

namespace OCA\Usage_Amount\AppInfo;


$application = new Application();
$application->registerRoutes($this, [
    'routes' => [
        ['name' => 'page#index','url' => '/','verb' => 'GET'],
        ['name' => 'usage#exportCSV','url' => '/exportCSV/{type}','verb' => 'GET'],
        ['name' => 'usage#crontest','url' => '/crontest','verb' => 'GET'],
    ],
    'resources' => [
        'usage' => ['url' => '/usages']
    ],
]);