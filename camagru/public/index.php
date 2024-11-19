<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/Router.php';

use Core\Router;
use Core\Session;

// Démarrage de la session et vérification de l'authentification, 
// redirige vers /login si non authentifié
Session::control();

$router = new Router();
$router->run();