<?php
declare(strict_types=1);

namespace src\Controllers;

use src\Controllers\Controller;
use Database;

final class LoginController extends Controller
{
    public function showLogin(): void
    {
        $this->render('login');
    }

    public function showRegister(): void
    {
        $this->render('register');
    }

    public function login(): void
    {
        if (!verify_csrf($_POST['_token'] ?? null)) { http_response_code(419); exit('CSRF failed'); }

        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Invalid email or password.');
            redirect('/login');
        }

        $pdo = Database::pdo();
        $stmt = $pdo->prepare('SELECT id, password FROM users WHERE email = :e LIMIT 1');
        $stmt->execute([':e' => $email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            flash('error', 'Invalid email or password.');
            redirect('/login');
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        flash('success', 'Logged in successfully.');
        redirect($_SESSION['intended_url'] ?? '/');
    }

    public function register(): void
    {
        if (!verify_csrf($_POST['_token'] ?? null)) { http_response_code(419); exit('CSRF failed'); }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        $errors = [];
        if ($name === '') { $errors['name'] = 'Name is required.'; }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors['email'] = 'Please enter a valid email.'; }

        // Password at least 8 chars AND at least one symbol
        if (strlen($password) < 8 || !preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors['password'] = 'Password must be at least 8 characters and contain one symbol.';
        }

        if ($errors) {
            flash('error', reset($errors));
            redirect('/register');
        }

        $pdo = Database::pdo();

        // email
        $exists = $pdo->prepare('SELECT id FROM users WHERE email = :e LIMIT 1');
        $exists->execute([':e' => $email]);
        if ($exists->fetch()) {
            flash('error', 'Email already registered.');
            redirect('/register');
        }

        $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (:n, :e, :p)');
        $stmt->execute([
            ':n' => $name,
            ':e' => $email,
            ':p' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
        ]);

        // Auto-login
        $uid = (int)$pdo->lastInsertId();
        session_regenerate_id(true);
        $_SESSION['user_id'] = $uid;

        flash('success', 'Welcome! Your account has been created.');
        redirect($_SESSION['intended_url'] ?? '/');
    }

    public function logout(): void
    {
        if (!verify_csrf($_POST['_token'] ?? null)) { http_response_code(419); exit('CSRF failed'); }
        session_destroy();
        session_start();
        flash('success', 'You have been logged out.');
        redirect('/');
    }
}
