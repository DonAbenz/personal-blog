<?php

namespace App\Domain\Article\Http;

use App\Core\Routing\Router;
use App\Core\TwigService;

class AdminController
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
         'success' => $_SESSION['success'] ?? null,
      ]);

      unset($_SESSION['success']);
   }

   public function editArticle()
   {
      if (!isset($_SESSION['user_id'])) {
         redirect('/');
      }

      if ($_SESSION['role'] !== 'admin') {
         redirect('/home');
      }

      $parameters = $this->router->current()->parameters();

      $data = json_decode(file_get_contents(__DIR__ . '/../../../../public/assets/data.json'), true);

      $articles = array_filter($data['articles'], function ($article) use ($parameters) {
         return $article['id'] == $parameters['id'];
      });

      $articles = array_values($articles);

      echo $this->twig->render('edit.twig', [
         'loggedInUser' => $_SESSION['user_id'],
         'article' => $articles[0],
         'success' => $_SESSION['success'] ?? null,
      ]);

      unset($_SESSION['success']);
   }

   public function updateArticle()
   {
      if (!isset($_SESSION['user_id'])) {
         redirect('/');
      }

      if ($_SESSION['role'] !== 'admin') {
         redirect('/home');
      }

      $parameters = $this->router->current()->parameters();

      $data = json_decode(file_get_contents(__DIR__ . '/../../../../public/assets/data.json'), true);

      $articles = array_filter($data['articles'], function ($article) use ($parameters) {
         return $article['id'] == $parameters['id'];
      });

      $articles = array_values($articles);

      $article = $articles[0];

      $article['title'] = $_POST['title'];
      $article['content'] = $_POST['content'];
      $article['date'] = date('Y-m-d');

      foreach ($data['articles'] as &$a) {
         if ($a['id'] == $article['id']) {
            $a = $article;
         }
      }

      file_put_contents(__DIR__ . '/../../../../public/assets/data.json', json_encode($data, JSON_PRETTY_PRINT));

      //redirect back to edit page with success message
      $_SESSION['success'] = 'Article updated successfully!';
      header('Location: /edit/' . $article['id']);
      exit;
   }

   public function destroyArticle()
   {
      if (!isset($_SESSION['user_id'])) {
         redirect('/');
      }

      if ($_SESSION['role'] !== 'admin') {
         redirect('/home');
      }

      $parameters = $this->router->current()->parameters();

      $data = json_decode(file_get_contents(__DIR__ . '/../../../../public/assets/data.json'), true);

      $articles = array_filter($data['articles'], function ($article) use ($parameters) {
         return $article['id'] == $parameters['id'];
      });

      $articles = array_values($articles);

      foreach ($data['articles'] as $key => $a) {
         if ($a['id'] == $articles[0]['id']) {
            unset($data['articles'][$key]);
         }
      }

      file_put_contents(__DIR__ . '/../../../../public/assets/data.json', json_encode($data, JSON_PRETTY_PRINT));

      //redirect back to admin page with success message
      $_SESSION['success'] = 'Article deleted successfully!';
      header('Location: /admin');
      exit;
   }
}
