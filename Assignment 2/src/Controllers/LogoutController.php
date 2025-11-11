<?php

namespace src\Controllers;

use core\Request;

class LogoutController extends Controller
{
    /**
     * Handle POST /logout 
     */
    public function destroy(Request $request): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

        
        if (!verify_csrf($_POST['_token'] ?? null)) 
        {
            http_response_code(419);
            exit('CSRF failed');
        }


        $_SESSION = [];
        if (ini_get('session.use_cookies'))
        {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();

        header('Location: /login');
        exit;
    }
}
