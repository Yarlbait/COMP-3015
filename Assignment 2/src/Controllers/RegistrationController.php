<?php

namespace src\Controllers;

use core\Request;
use src\Repositories\UserRepository;

class RegistrationController extends Controller
{
    public function index(Request $request): void
    {
        $this->render('login', ['message' => 'Use this form to register for now.']);
    }

    /**
     * Handle POST /register
     */
    public function store(Request $request): void
    {
        $name = trim($request->input('name') ?? '');
        $email = trim($request->input('email') ?? '');
        $password = $request->input('password') ?? '';

        $errors = [];

    
        if (!verify_csrf($_POST['_token'] ?? null)) 
        {
            http_response_code(419);
            exit('CSRF failed');
        }


        if ($name === '')
        {
            $errors['name'] = 'Name is required.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $errors['email'] = 'Please enter a valid email.';
        }
        $hasSymbol = preg_match('/\W/', $password) === 1;
        if (strlen($password) < 8 || !$hasSymbol)
        {
            $errors['password'] = 'Password must be at least 8 characters and include a symbol.';
        }

        if (!empty($errors))
        {
            $this->render('login', ['errors' => $errors, 'old' => ['name' => $name, 'email' => $email]]);
            return;
        }

        $digest = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $users = new UserRepository();
        $id = $users->create($name, $email, $digest);
        if (!$id)
        {
            $this->render('login', ['errors' => ['email' => 'Email is already registered.'], 'old' => ['name' => $name, 'email' => $email]]);
            return;
        }

        // Auto-login after registration
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
        session_regenerate_id(true);

        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $name;

        header('Location: /');
        exit;
    }
}

