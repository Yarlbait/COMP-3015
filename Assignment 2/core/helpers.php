<?php

if (!function_exists('image')) {
    function image(string $filename): string
    {
        return "/images/$filename";
    }
}

if (!function_exists('getSessionData')) {
    function getSessionData(string $key)
    {
        return $_SESSION[$key] ?? null;
    }
}

if (!function_exists('popSessionData')) {
    function popSessionData(string $key)
    {
        $data = $_SESSION[$key] ?? null;
        unset($_SESSION[$key]);
        return $data;
    }
}

if (!function_exists('old')) {
    function old(string $key)
    {
        $data = $_SESSION[$key] ?? null;
        unset($_SESSION[$key]);
        return $data;
    }
}
