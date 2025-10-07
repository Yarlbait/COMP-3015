<?php

function validate_email(string $email): ?string
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        return null;
    }
    else
    {
        return 'Please enter a valid email address.';
    }
}

function validate_password(string $password): ?string
{
    if (strlen($password) <= 8)
    {
        return 'Password must be longer than 8 characters.';
    }

    if (!preg_match('/[^A-Za-z0-9]/', $password))
    {
        return 'Password must include a special character.';
    }

    return null;
}

function validate_telephone(string $tel): ?string
{
    $digits = preg_replace('/\D+/', '', $tel ?? '');

    if (strlen($digits) >= 10)
    {
        return null;
    }
    else
    {
        return 'Please enter a valid telephone number (at least 10 digits).';
    }
}

function is_bcit_email(string $email): bool
{
    return str_ends_with(strtolower(trim($email)), '@bcit.ca');
}
