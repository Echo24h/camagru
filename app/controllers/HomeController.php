<?php

require_once './models/User.php';

class HomeController {

    // public function __construct() {
        
    //     if (session_status() === PHP_SESSION_NONE) {
    //         session_start(); // Démarrer la session si elle n'est pas déjà active
    //     }
    //     if (!isset($_SESSION['user_id'])) {
    //         // Vérifie l'id de session

    //         header("Location: /login");
    //         exit;
    //     }
    // }

    public function index() {
        $userModel = new User();
        $users = $userModel->getAllUsers();

        require_once './views/home.php';
    }
}   