<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

const BASE_PATH = __DIR__ . '/../';

session_start();

require BASE_PATH . 'vendor/autoload.php';

$router = new App\Core\Routing\Router();

$routes = require BASE_PATH . 'config/routes.php';
$routes($router);

$router->dispatch();