<?php

require_once './models/Database.php';
require_once './controllers/HomeController.php';
require_once './controllers/AuthController.php';
require_once './controllers/DropTableController.php';

if ($_SERVER['REQUEST_URI'] === '/droptables') {
    $controller = new DropTableController();
    $controller->dropTables();
    exit;
}


// Gestions des sessions
require_once './session.php';

// Route simple
$page = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($page === '/home' || $page === '/') {
    $controller = new HomeController();
    $controller->index();
} elseif ($page === '/login') {
    $controller = new AuthController();
    $controller->login();
} elseif ($page === '/register') {
    $controller = new AuthController();
    $controller->register();
} elseif ($page === '/logout') {
    $controller = new AuthController();
    $controller->logout();
} elseif ($page === '/verify') {
    $controller = new AuthController();
    $controller->verifyEmail();
} else {
    echo "Page not found.";
}