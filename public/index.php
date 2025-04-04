<?php

const BASE_PATH = __DIR__ . '/../';

require BASE_PATH . 'vendor/autoload.php';

$router = new App\Core\Routing\Router();

$routes = require BASE_PATH . 'config/routes.php';
$routes($router);

$router->dispatch();
