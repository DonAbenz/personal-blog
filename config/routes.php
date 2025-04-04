<?php

use App\Core\Routing\Router;

return function (Router $router) {
    $router->addRoute('GET', '/', function () {
        echo 'Welcome to the homepage!';
    });
};