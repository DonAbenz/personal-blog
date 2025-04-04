<?php

namespace App\Domain\Article\Http;

use App\Core\TwigService;

class AdminController
{
   public function index()
   {
      if (!isset($_SESSION['user_id'])) {
         redirect('/');
      }

      if ($_SESSION['role'] !== 'guest') {
         redirect('/home');
      }

      $twig = TwigService::getTwig();

      $data = json_decode(file_get_contents(__DIR__ . '/../../../../public/assets/data.json'), true);

      $articles = $data['articles'];

      if (!empty($articles)) {
         usort($articles, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
         });
      }

      echo $twig->render('admin.twig', [
         'loggedInUser' => $_SESSION['user_id'],
         'articles' => $articles,
      ]);
   }
}
