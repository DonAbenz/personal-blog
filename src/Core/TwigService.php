<?php

namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigService
{
   private static ?Environment $twig = null;

   private function __construct() {} // Prevent instantiation

   public static function getTwig(): Environment
   {
      if (self::$twig === null) {
         // Set up the Twig environment
         $loader = new FilesystemLoader(__DIR__ . '/../../templates');
         self::$twig = new Environment($loader);
      }

      return self::$twig;
   }
}
