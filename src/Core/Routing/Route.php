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

   public function dispatch(): mixed
   {
      if (is_array($this->handler)) {
         [$class, $method] = $this->handler;

         if (is_string($class)) {
            return (new $class)->{$method}();
         }

         return $class->{$method}();
      }

      return call_user_func($this->handler);
   }


   public function matches(string $method, string $path): bool
   {
      if ($this->method !== $method) {
         return false;
      }

      // First, try exact match
      if ($this->path === $path) {
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

   public function getPath(): string
   {
      return $this->path;
   }
}
