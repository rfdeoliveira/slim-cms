<?php

use League\Fractal;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Respect\Validation\Validator as v;

$container = $cms->getContainer();

// Service factory for Doctrine ORM
$container['em'] = function ($c) {
    $paths = array(__DIR__ . '/../src/Domain');
    $isDevMode = true;

    // the connection configuration
    $dbParams = $c->get('settings')['db'];

    $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
    $em = EntityManager::create($dbParams, $config);

    return $em;
};

// Fractal Serializer for simple response body transformations
$container['fractal'] = function ($c) {
    return new Fractal\Manager();
};

$container['validationRules'] = [
    'title' => v::notEmpty(),
    'body'  => v::notEmpty(),
    'path'  => v::notEmpty()->slug(),
];
