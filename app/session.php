<?php

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_secure', '1');  // Envoie uniquement les cookies sur HTTPS
    ini_set('session.cookie_httponly', '1'); // Empêche l'accès des cookies via JavaScript
    ini_set('session.use_strict_mode', '1'); // Rejette les cookies de session invalides
    session_start();
}

$page = $_SERVER['REQUEST_URI'];

if (!isset($_SESSION['username']) && $page !== '/login' && $page !== '/register') {
    header("Location: /login");
    exit;
}

// Prévenir le vol de session
if (!isset($_SESSION['last_activity'])) {
    $_SESSION['last_activity'] = time();
} elseif (time() - $_SESSION['last_activity'] < 1800) {
    // Regénère l'ID de session si l'utilisateur est actif
    session_regenerate_id(true);
    $_SESSION['last_activity'] = time();
} else {
    session_destroy();
    header("Location: /login");
    exit;
}

?>