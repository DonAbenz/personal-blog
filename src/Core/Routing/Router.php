<?php

namespace App\Core\Routing;

class Router {
      private array $routes = [];
   
      public function addRoute(string $method, string $path, callable $handler): void
      {
         $this->routes[] = new Route($method, $path, $handler);
      }
   
      public function getRoutes(): array
      {
         return $this->routes;
      }
   
      public function dispatch(): void
      {
         $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
         $requestPath = $_SERVER['REQUEST_URI'] ?? '/';

         foreach ($this->routes as $route) {
               if ($route->matches($requestMethod, $requestPath)) {
                  call_user_func_array($route->handler, $route->parameters);
                  return;
               }
         }
         
         http_response_code(404);
         echo 'Not Found';
      }
}