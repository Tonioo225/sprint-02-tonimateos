<?php

declare(strict_types=1);

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'][] = [$type, $message];
}

function getFlashes(): array
{
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}
