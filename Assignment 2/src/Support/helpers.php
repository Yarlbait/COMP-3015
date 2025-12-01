<?php
declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path): void
{
    header("Location: {$path}");
    exit;
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null)
    {
        $_SESSION['flash'][$key] = $message;
        return null;
    }
    if (!empty($_SESSION['flash'][$key]))
    {
        $m = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $m;
    }
    return null;
}

function csrf_token(): string
{
    if (empty($_SESSION['_token']))
    {
        $_SESSION['_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_token'];
}

function verify_csrf(?string $token): bool
{
    return isset($_SESSION['_token'])
        && is_string($token)
        && hash_equals($_SESSION['_token'], $token);
}

function auth_id(): ?int
{
    return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
}

function require_auth(): void
{
    if (!auth_id())
    {
        $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '/';
        redirect('/login');
    }
}

function guest_only(): void
{
    if (auth_id())
    {
        redirect('/');
    }
}
