<?php

namespace App\Domain\User\Http;

use App\Core\TwigService;

class AuthController
{
   public function index()
   {
      // Check if user is already logged in
      if (isset($_SESSION['user_id'])) {
         redirect('/home');
      }

      $twig = TwigService::getTwig();

      echo $twig->render('auth/login.twig', [
         'error' => $_SESSION['error'] ?? null
      ]);

      unset($_SESSION['error']);
   }

   public function login()
   {
      // Read JSON file
      $data = json_decode(file_get_contents(__DIR__. '/../../../../public/assets/data.json'), true);
      $users = $data['users'];

      // Example login attempt
      $username = $_POST['username'];
      $password = $_POST['password'];  // Remember, passwords should be hashed!

      // Find user
      $user = null;
      foreach ($users as $u) {
         if ($u['username'] === $username && password_verify($password, $u['password'])) {
            $user = $u;
            break;
         }
      }

      if ($user) {
         // User authenticated
         $_SESSION['user_id'] = $user['id'];
         $_SESSION['role'] = $user['role'];  // You can use this to check the user's role later

         redirect('/home');
      }

      $_SESSION['error'] = 'Invalid username or password';
      redirect('/');
   }

   public function logout()
   {
      // Clear session data
      session_unset();
      session_destroy();

      redirect('/');
   }
}
