<?php

namespace App\Domain\Article\Http;

use App\Core\Routing\Router;
use App\Core\TwigService;

class AdminController
{
   private $twig;
   
   public function __construct(protected Router $router) {
      $this->twig = TwigService::getTwig();
   }
   
   public function index()
   {
      if (!isset($_SESSION['user_id'])) {
         redirect('/');
      }

      if ($_SESSION['role'] !== 'admin') {
         redirect('/home');
      }

      $data = json_decode(file_get_contents(__DIR__ . '/../../../../public/assets/data.json'), true);

      $articles = $data['articles'];

      if (!empty($articles)) {
         usort($articles, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
         });
      }

      echo $this->twig->render('admin.twig', [
         'loggedInUser' => $_SESSION['user_id'],
         'articles' => $articles,
      ]);
   }
}
