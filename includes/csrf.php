<?php
/**
 * CSRF helpers
 */

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function generate_csrf_token(): string
{
    $token = bin2hex(random_bytes(32));
    // Store single token per session (rotate)
    $_SESSION['csrf_token'] = $token;
    return $token;
}

function get_csrf_token(): ?string
{
    return $_SESSION['csrf_token'] ?? null;
}

function validate_csrf_token(?string $token): bool
{
    if (!$token) return false;
    $stored = $_SESSION['csrf_token'] ?? null;
    if (!$stored) return false;
    // Use hash_equals for timing-safe comparison
    return hash_equals($stored, $token);
}

?>