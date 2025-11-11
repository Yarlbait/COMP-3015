<?php
declare(strict_types=1);

namespace src\Controllers;

class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        include dirname(__DIR__, 2) . '/views/' . $view . '.view.php';
        exit;
    }

    protected function redirect(string $to, bool $exit = true): void
    {
        header("Location: $to");
        if ($exit) exit;
    }

    public function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setSessionData(string $key, array|string $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function getSessionData(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public function destroySession(): bool
    {
        return session_destroy();
    }
}


