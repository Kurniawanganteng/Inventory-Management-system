<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

function require_login(): void {
    if (empty($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}
function require_admin(): void {
    require_login();
    if (($_SESSION['user']['role'] ?? '') !== 'admin') {
        http_response_code(403);
        exit('Akses ditolak. Halaman ini hanya untuk Admin.');
    }
}
function h($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function is_admin(): bool {
    return ($_SESSION['user']['role'] ?? '') === 'admin';
}
