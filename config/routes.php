<?php

use App\Core\Routing\Router;
use App\Domain\Article\Http\HomeController;
use App\Domain\User\Http\AuthController;

return function (Router $router) {
    $router->addRoute('GET', '/', [new AuthController(), 'index']);
    $router->addRoute('POST', '/', [new AuthController(), 'login']);
    $router->addRoute('POST', '/logout', [new AuthController(), 'logout']);
    
    $router->addRoute('GET', '/home', [new HomeController(), 'index']);
};