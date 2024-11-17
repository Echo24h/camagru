<?php

require_once './models/Database.php';
require_once './controllers/HomeController.php';

// Route simple
$page = $_GET['page'] ?? 'home';

if ($page === 'home') {
    $controller = new HomeController();
    $controller->index();
} else {
    echo "Page not found.";
}