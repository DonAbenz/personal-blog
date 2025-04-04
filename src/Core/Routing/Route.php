<?php

namespace App\Core\Routing;

class Route
{
   public string $method;
   public string $path;
   public $handler;
   public array $parameters = [];

   public function __construct(string $method, string $path, $handler)
   {
      $this->method = $method;
      $this->path = $path;
      $this->handler = $handler;
   }

   public function matches(string $method, string $path): bool
   {
      // Check for a literal match
      if ($this->method === $method && $this->path === $path) {
         return true;
      }

      // Handle simple parameterized paths like 'product/{id}'
      $pattern = preg_replace('#{[^}]+}#', '([^/]+)', $this->path);
      $pattern = "#^" . str_replace('/', '\/', $pattern) . "$#";

      if (preg_match($pattern, $path, $matches)) {
         array_shift($matches); // Remove the full match
         $this->parameters = $matches;
         return true;
      }

      return false;
   }
}
