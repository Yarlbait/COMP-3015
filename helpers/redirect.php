<?php
declare(strict_types=1);

function redirect(string $url, int $status = 303) : void
{
    header("Location: {$url}", true, $status);
    exit;
}

