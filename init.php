<?php

// Import Composer's autoload for third party libs and Domain classes
require_once  __DIR__ . '/vendor/autoload.php';

// Returns app's settings array
$settings = require_once __DIR__ . '/core/settings.php';

// Creates app with correct settings
$cms = new \Slim\App($settings);

// Loads app's dependencies using its dependency injector
require_once __DIR__ . '/core/dependencies.php';

// configures cms' endpoints
require_once __DIR__ . '/core/routes.php';
