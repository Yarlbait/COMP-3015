<?php
function is_authenticated() : bool
{
    return isset($_SESSION['user_email']);
}

function auth_require(): void
{
    if(!is_authenticated()) redirect('login.php');
}

function guest_require(): void
{
    if(is_authenticated()) redirect('dashboard.php');
}

function auth_login(string $email): void
{
    $_SESSION['user_email'] = $email;
    old_clear();
}

function auth_logout(): void
{
    $_SESSION = [];
    if(ini_get('session.use_cookies'))
    {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 36000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
    session_start();
}