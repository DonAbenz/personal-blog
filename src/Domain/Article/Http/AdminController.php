<?php

namespace App\Domain\Article\Http;

use App\Core\Routing\Router;
use App\Core\TwigService;

class AdminController
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

      if ($_SESSION['role'] !== 'admin') {
         redirect('/home');
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
   
   private function saveFile($data)
   {
      file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT));
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

      echo $this->twig->render('admin.twig', [
         'loggedInUser' => $_SESSION['user_id'],
         'articles' => $articles,
         'success' => $_SESSION['success'] ?? null,
      ]);

      unset($_SESSION['success']);
   }

   public function createArticle()
   {
      $this->checkAuth();

      echo $this->twig->render('create.twig', [
         'loggedInUser' => $_SESSION['user_id'],
         'success' => $_SESSION['success'] ?? null,
      ]);

      unset($_SESSION['success']);
   }

   public function storeArticle()
   {
      $this->checkAuth();

      $data = $this->loadData();

      $id = count($data['articles']) > 0 ? end($data['articles'])['id'] + 1 : 1;

      $article = [
         'id' => $id,
         'title' => $_POST['title'],
         'content' => $_POST['content'],
         'date' => $_POST['date'] ?? date('Y-m-d'),
      ];

      $data['articles'][] = $article;

      $this->saveFile($data);

      //redirect back to admin page with success message
      $_SESSION['success'] = 'Article created successfully!';
      header('Location: /admin');
      exit;
   }

   public function editArticle()
   {
      $this->checkAuth();

      $parameters = $this->router->current()->parameters();

      $data = $this->loadData();

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
      $this->checkAuth();

      $parameters = $this->router->current()->parameters();

      $data = $this->loadData();

      $articles = array_filter($data['articles'], function ($article) use ($parameters) {
         return $article['id'] == $parameters['id'];
      });

      $articles = array_values($articles);

      $article = $articles[0];

      $article['title'] = $_POST['title'];
      $article['content'] = $_POST['content'];
      $article['date'] = $_POST['date'] ?? date('Y-m-d');

      foreach ($data['articles'] as &$a) {
         if ($a['id'] == $article['id']) {
            $a = $article;
         }
      }

      $this->saveFile($data);

      //redirect back to edit page with success message
      $_SESSION['success'] = 'Article updated successfully!';
      header('Location: /edit/' . $article['id']);
      exit;
   }

   public function destroyArticle()
   {
      $this->checkAuth();

      $parameters = $this->router->current()->parameters();

      $data = $this->loadData();

      $articles = array_filter($data['articles'], function ($article) use ($parameters) {
         return $article['id'] == $parameters['id'];
      });

      $articles = array_values($articles);

      foreach ($data['articles'] as $key => $a) {
         if ($a['id'] == $articles[0]['id']) {
            unset($data['articles'][$key]);
         }
      }

      $this->saveFile($data);

      //redirect back to admin page with success message
      $_SESSION['success'] = 'Article deleted successfully!';
      header('Location: /admin');
      exit;
   }
}
