<?php

namespace App\Core\Routing;

class Router
{
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
      $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

      foreach ($this->routes as $route) {
         if ($route->matches($requestMethod, $requestPath)) {
            $route->dispatch();
            return;
         }
      }

      http_response_code(404);
      echo 'Not Found';
   }
}
