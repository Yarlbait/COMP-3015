<?php

function flash_set(string $key, mixed $value): void
{
    $_SESSION['flash'][$key] = $value;
}

function flash_get(string $key, mixed $default = null): mixed
{
    if (!isset($_SESSION['flash'][$key]))
    {
        return $default;
    }

    $value = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]); // consume once

    return $value;
}

function old_set(array $data, array $exclude = []): void
{
    foreach ($exclude as $k)
    {
        unset($data[$k]);
    }

    $_SESSION['old'] = $data;
}

function old(string $key, string $default = ''): string
{
    if (isset($_SESSION['old'][$key]))
    {
        return e($_SESSION['old'][$key]);
    }

    return $default;
}

function old_clear(): void
{
    unset($_SESSION['old']);
}

