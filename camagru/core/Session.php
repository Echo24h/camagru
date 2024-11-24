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
        '/gallery/show',
        '/image/like',
        '/image/comment',
        '/404',
    ];
    
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 3600,
                'path' => '/',
                'domain' => null,
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict',
            ]);
            session_start();
        }
    }

    
    public static function control() {
        self::start();
        self::preventSessionHijacking();
        $uri = $_SERVER['REQUEST_URI'];
        $uri = explode('?', $uri)[0];
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
        return isset($_SESSION['user_id']);
    }

    public static function preventSessionHijacking() {
        if (!isset($_SESSION['last_activity'])) {
            self::set('last_activity', time());
        } elseif (time() - self::get('last_activity') < 1800) {
            session_regenerate_id(true);
            self::set('last_activity', time());
        } else {
            self::destroy();
            header("Location: /login");
            exit;
        }
    }
}