<?php
declare(strict_types=1);
session_start();

function redirect(string $to): never {
    header("Location: {$to}");
    exit;
}
function e(?string $v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
