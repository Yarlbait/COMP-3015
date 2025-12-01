<?php
declare(strict_types=1);

// Secure session
$secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => $secure,
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Helpers
require_once __DIR__ . '/../src/Support/helpers.php';

// Controllers
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Controllers/Controller.php';
require_once __DIR__ . '/../src/Controllers/LoginController.php';
require_once __DIR__ . '/../src/Controllers/ArticleController.php';
require_once __DIR__ . '/../src/Controllers/SettingsController.php';

use src\Controllers\LoginController;
use src\Controllers\ArticleController;
use src\Controllers\SettingsController;

$uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$uri    = preg_replace('#/{2,}#', '/', $uri);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

//Avoid error spam
if ($uri === '/favicon.ico') {
    http_response_code(204);
    exit;
}

//Instantiate controllers
$auth     = new LoginController();
$articles = new ArticleController();
$profile  = new SettingsController();


// Home
if ($uri === '/' && $method === 'GET') {
    $articles->index();
    exit;
}

// Registration
if ($uri === '/register' && $method === 'GET') {
    $auth->showRegister();
    exit;
}
if ($uri === '/register' && $method === 'POST') {
    if (!verify_csrf($_POST['_token'] ?? null)) { http_response_code(419); exit('CSRF failed'); }
    $auth->register();
    exit;
}

// Login
if ($uri === '/login' && $method === 'GET') {
    $auth->showLogin();
    exit;
}
if ($uri === '/login' && $method === 'POST') {
    if (!verify_csrf($_POST['_token'] ?? null)) { http_response_code(419); exit('CSRF failed'); }
    $auth->login();
    exit;
}

// Logout
if ($uri === '/logout' && $method === 'POST') {
    if (!verify_csrf($_POST['_token'] ?? null)) { http_response_code(419); exit('CSRF failed'); }
    $auth->logout();
    exit;
}

// Articles
if ($uri === '/articles/create' && $method === 'GET') {
    require_auth();
    $articles->create();
    exit;
}
if ($uri === '/articles' && $method === 'POST') {
    require_auth();
    if (!verify_csrf($_POST['_token'] ?? null)) { http_response_code(419); exit('CSRF failed'); }
    $articles->store();
    exit;
}

// /articles/{id}
if (preg_match('#^/articles/(\d+)$#', $uri, $m)) {
    $id = (int)$m[1];
    if ($method === 'GET') {
        $articles->show($id);
        exit;
    }
}

// /articles/{id}/edit
if (preg_match('#^/articles/(\d+)/edit$#', $uri, $m)) {
    require_auth();
    $id = (int)$m[1];
    if ($method === 'GET') {
        $articles->edit($id);
        exit;
    }
}

// /articles/{id}/update
if (preg_match('#^/articles/(\d+)/update$#', $uri, $m)) {
    require_auth();
    $id = (int)$m[1];
    if ($method === 'POST') {
        if (!verify_csrf($_POST['_token'] ?? null)) { http_response_code(419); exit('CSRF failed'); }
        $articles->update($id);
        exit;
    }
}

// /articles/{id}/delete
if (preg_match('#^/articles/(\d+)/delete$#', $uri, $m)) {
    require_auth();
    $id = (int)$m[1];
    if ($method === 'POST') {
        if (!verify_csrf($_POST['_token'] ?? null)) { http_response_code(419); exit('CSRF failed'); }
        $articles->destroy($id);
        exit;
    }
}

// Settings (profile)
if ($uri === '/settings' && $method === 'GET') {
    require_auth();
    $profile->show();
    exit;
}
if ($uri === '/settings' && $method === 'POST') {
    require_auth();
    if (!verify_csrf($_POST['_token'] ?? null)) { http_response_code(419); exit('CSRF failed'); }
    $profile->update();
    exit;
}

// 404 fallback
http_response_code(404);
require __DIR__ . '/../src/views/404.view.php';
