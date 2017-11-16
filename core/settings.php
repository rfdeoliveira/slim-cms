<?php

return [
    'settings' => [
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,
        'db' => [
            'driver' => 'pdo_sqlite',
            'path'   => __DIR__ . '/../db.sqlite',
        ],
    ],
];
