<?php

use App\Core\Router;
use App\Core\App;
use App\Core\ClassFinder;
use App\Core\Container;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new Container();
$router = new Router($container);
$app = new App($container, $router);

$router->registerRoutesFromControllerAttributes(
    ClassFinder::getClassesByPath(__DIR__ . "/../app/Controllers")
);

$app->boot()->run();
