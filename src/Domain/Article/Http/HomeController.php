<?php

namespace App\Domain\Article\Http;

use App\Core\Routing\Router;
use App\Core\TwigService;

class HomeController
{
   private $twig;
   private $filePath = __DIR__ . '/../../../../public/assets/data.json';

   public function __construct(protected Router $router)
   {
      $this->twig = TwigService::getTwig();
   }

   private function checkAuth()
   {
      if (!isset($_SESSION['user_id'])) {
         redirect('/');
      }

      if ($_SESSION['role'] !== 'guest') {
         redirect('/admin');
      }
   }
   
   private function loadData()
   {
      $data = json_decode(file_get_contents($this->filePath), true);

      if (empty($data)) {
         $data = [
            'articles' => [],
         ];
      }

      return $data;
   }

   public function index()
   {
      $this->checkAuth();
      
      $data = $this->loadData();

      $articles = $data['articles'];

      if (!empty($articles)) {
         usort($articles, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
         });
      }

      echo $this->twig->render('home.twig', [
         'loggedInUser' => $_SESSION['user_id'],
         'articles' => $articles,
      ]);
   }

   public function showArticle()
   {
      $this->checkAuth();

      $parameters = $this->router->current()->parameters();

      $data = $this->loadData();
      
      $articles = array_filter($data['articles'], function ($article) use ($parameters) {
         return $article['id'] == $parameters['id'];
      });

      $articles = array_values($articles);

      echo $this->twig->render('article.twig', [
         'loggedInUser' => $_SESSION['user_id'],
         'article' => $articles[0],
      ]);
   }
}
