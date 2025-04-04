<?php

namespace App\Domain\Article\Http;

use App\Core\Routing\Router;
use App\Core\TwigService;

class HomeController
{
   private $twig;

   public function __construct(protected Router $router)
   {
      $this->twig = TwigService::getTwig();
   }

   public function index()
   {
      if (!isset($_SESSION['user_id'])) {
         redirect('/');
      }

      if ($_SESSION['role'] !== 'guest') {
         redirect('/admin');
      }
      
      $data = json_decode(file_get_contents(__DIR__ . '/../../../../public/assets/data.json'), true);

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
      if (!isset($_SESSION['user_id'])) {
         redirect('/');
      }

      if ($_SESSION['role'] !== 'guest') {
         redirect('/admin');
      }

      $parameters = $this->router->current()->parameters();

      $data = json_decode(file_get_contents(__DIR__ . '/../../../../public/assets/data.json'), true);
      
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
