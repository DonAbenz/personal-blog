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
    $router->add('GET', '/new', [new AdminController($router), 'createArticle']);
    $router->add('POST', '/new', [new AdminController($router), 'storeArticle']);
    $router->add('GET', '/edit/{id}', [new AdminController($router), 'editArticle']);
    $router->add('POST', '/edit/{id}', [new AdminController($router), 'updateArticle']);
    $router->add('POST', '/delete/{id}', [new AdminController($router), 'destroyArticle']);
};