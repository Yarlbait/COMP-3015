<?php

use core\Router;
use src\Controllers\ArticleController;
use src\Controllers\LoginController;
use src\Controllers\RegistrationController;
use src\Controllers\LogoutController;
use src\Controllers\SettingsController;

/*
| Web Routes
|
| All your application routes are defined here. Routes map a URL path
| and HTTP method (GET/POST) to a specific controller action.
|--------------------------------------------------------------------------
*/

// Home (article index)
Router::get('/', [ArticleController::class, 'index']);

// Authentication

// Login routes
Router::get('/login', [LoginController::class, 'index']);
Router::post('/login', [LoginController::class, 'store']);

// Registration routes
Router::get('/register', [RegistrationController::class, 'index']);
Router::post('/register', [RegistrationController::class, 'store']);

// Logout route (POST only)
Router::post('/logout', [LogoutController::class, 'destroy']);

//User Settings
Router::get('/settings', [SettingsController::class, 'index']);
Router::post('/settings', [SettingsController::class, 'update']);

// Article CRUD
// Create new article
Router::get('/articles/create', [ArticleController::class, 'create']);
Router::post('/articles', [ArticleController::class, 'store']);

// View a single article
Router::get('/articles/{id}', [ArticleController::class, 'show']);

// Edit existing article
Router::get('/articles/{id}/edit', [ArticleController::class, 'edit']);
Router::post('/articles/{id}/update', [ArticleController::class, 'update']);

// Delete an article
Router::post('/articles/{id}/delete', [ArticleController::class, 'destroy']);

// Fallback
// Handles unknown routes with the existing 404 view
Router::fallback(function() {
    http_response_code(404);
    require base_path('views/404.view.php');
});

