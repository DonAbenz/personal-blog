<?php

use App\Core\Routing\Router;
use App\Domain\Article\Http\AdminController;
use App\Domain\Article\Http\HomeController;
use App\Domain\User\Http\AuthController;

return function (Router $router) {
    $router->add('GET', '/', [new AuthController(), 'index']);
    $router->add('POST', '/', [new AuthController(), 'login']);
    $router->add('POST', '/logout', [new AuthController(), 'logout']);

    $router->add('GET', '/home', [new HomeController($router), 'index']);
    $router->add('GET', '/article/{id}', [new HomeController($router), 'showArticle']);

    $router->add('GET', '/admin', [new AdminController($router), 'index']);
};