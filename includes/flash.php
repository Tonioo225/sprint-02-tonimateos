<?php

declare(strict_types=1);

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'][] = [$type, $message];
}

function renderFlashes(): void
{
    foreach ($_SESSION['flash'] ?? [] as [$type, $message]) {
        $class = $type === 'success' ? 'success' : 'danger';
        print '<div class="alert alert-' . $class . '">' . h($message) . '</div>';
    }
    unset($_SESSION['flash']);
}
