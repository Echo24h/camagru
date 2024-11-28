<?php

namespace Core;

class Session {

    private static $routes_without_auth = [
        '/login',
        '/register',
        '/verify-email',
        '/forgot-password',
        '/reset-password',
        '/gallery',
        '/image',
        '/gallery/show',
        '/profil',
        '/image/like',
        '/image/comment',
        '/404',
    ];

    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 3600, // Durée de vie de 1 heure
                'path' => '/',
                'domain' => null,
                'secure' => true, // Obligatoire pour HTTPS
                'httponly' => true, // Empêche l'accès au cookie via JavaScript
                'samesite' => 'Strict', // Empêche les envois croisés de cookies
            ]);
            session_start();
        }
    }

    public static function control() {
        self::start();
        self::preventSessionHijacking();

        $uri = $_SERVER['REQUEST_URI'];
        $uri = explode('?', $uri)[0];

        // Vérifie si c'est une requête POST et valide le token CSRF
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
                $data = json_decode(file_get_contents('php://input'), true);
                if (!isset($data['csrf_token']) || !Session::validateCSRF($data['csrf_token'])) {
                    http_response_code(403);
                    echo "CSRF Token invalide";
                    exit;
                }
            }
            else {
                if (!isset($_POST['csrf_token']) || !Session::validateCSRF($_POST['csrf_token'])) {
                    http_response_code(403);
                    echo "CSRF Token invalide";
                    exit;
                }
            }
        }

        // Redirection vers login si non authentifié
        if (!self::isAuthenticated() && !in_array($uri, self::$routes_without_auth)) {
            header("Location: /login");
            exit;
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public static function unset($key) {
        unset($_SESSION[$key]);
    }

    public static function destroy() {
        session_destroy();
        $_SESSION = [];
    }

    public static function isAuthenticated() {
        return isset($_SESSION['user_id']) && isset($_SESSION['csrf_token']);
    }

    public static function validateCSRF($token) {
        $isValid = hash_equals(self::get('csrf_token'), $token);
        if ($isValid) {
            // Regénérer un nouveau token après validation pour éviter les attaques par relecture
            self::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return $isValid;
    }

    public static function getCsrfToken() {
        return self::get('csrf_token');
    }

    // Vérification de l'activité récente, de l'adresse IP et de l'User-Agent
    public static function preventSessionHijacking() {
        // Initialisation de la session si elle est inexistante
        if (!isset($_SESSION['last_activity'])) {
            self::set('last_activity', time());
            self::set('ip_address', $_SERVER['REMOTE_ADDR']);
            self::set('user_agent', $_SERVER['HTTP_USER_AGENT']);
            self::set('csrf_token', bin2hex(random_bytes(32))); // Génération d'un token CSRF unique
            session_regenerate_id(true); // Régénération initiale
        }

        // Vérification de l'inactivité (30 minutes)
        if (time() - self::get('last_activity') > 1800) {
            // Session expirée, destruction
            self::destroy();
            header("Location: /login");
            exit;
        } else {
            // Mise à jour de l'activité récente
            self::set('last_activity', time());
        }

        // Régénération périodique de l'ID de session (toutes les 5 minutes)
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
        }
        if (time() - $_SESSION['last_regeneration'] > 300) { // Régénère toutes les 5 minutes
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }

        // Vérification de l'adresse IP et du User-Agent
        if (self::get('ip_address') !== $_SERVER['REMOTE_ADDR'] || self::get('user_agent') !== $_SERVER['HTTP_USER_AGENT']) {
            // Si une incohérence est détectée, détruire la session et rediriger
            self::destroy();
            header("Location: /login");
            exit;
        }
    }
}