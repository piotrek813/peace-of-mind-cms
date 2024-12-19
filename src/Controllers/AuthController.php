<?php

namespace App\Controllers;

use App\Controller;
use App\Models\User;

class AuthController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function registerIndex()
    {
        $this->render('auth/register');
    }

    public function loginIndex()
    {
        $this->render('auth/login');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                header('Location: /login?error=missing_fields');
                exit;
            }

            $user = $this->user->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                header('Location: /dashboard');
                exit;
            }

            header('Location: /login?error=invalid_credentials');
            exit;
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                header('Location: /register?error=missing_fields');
                exit;
            }

            if ($this->user->findByUsername($username)) {
                header('Location: /register?error=username_exists');
                exit;
            }

            if ($this->user->create($username, $password)) {
                header('Location: /login?success=registered');
                exit;
            } else {
                header('Location: /register?error=registration_failed');
                exit;
            }
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
} 