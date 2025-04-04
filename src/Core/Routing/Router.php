<?php

namespace App\Core\Routing;

class Router
{
   private array $routes = [];
   protected Route $current;

   public function add(string $method, string $path, $handler): void
   {
      $this->routes[] = new Route($method, $path, $handler);
   }

   public function current(): ?Route
   {
      return $this->current;
   }

   public function dispatch(): void
   {
      $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
      $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

      foreach ($this->routes as $route) {
         if ($route->matches($requestMethod, $requestPath)) {
            $this->current = $route;
            $route->dispatch();
            return;
         }
      }

      http_response_code(404);
      echo 'Not Found';
   }
}
