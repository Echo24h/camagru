<?php

namespace Core;

class Router {
    private $routes = [];

    public function __construct() {
        $this->routes = require_once __DIR__ . '/../config/routes.php';
    }

    public function run() {
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $uri = explode('?', $uri)[0];
        foreach ($this->routes as $route => $action) {
            if ($uri === $route) {
                [$controllerName, $methodName] = explode('@', $action);
                $controllerClass = "\\App\\Controllers\\" . $controllerName;
                $controller = new $controllerClass();
                call_user_func([$controller, $methodName]);
                return;
            }
        }
        $controller = new \App\Controllers\ErrorController();
        $controller->notFound();
    }
}