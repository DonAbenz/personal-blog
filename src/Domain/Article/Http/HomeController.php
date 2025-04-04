<?php

namespace App\Domain\Article\Http;

use App\Core\TwigService;

class HomeController
{
   public function index()
   {
      if (!isset($_SESSION['user_id'])) {
         redirect('/');
      }

      $twig = TwigService::getTwig();

      echo $twig->render('dashboard.twig', [
         'loggedInUser' => $_SESSION['user_id'],
      ]);
   }
}
