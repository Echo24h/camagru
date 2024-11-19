<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class HomeController extends Controller {
    public function index() {

        $this->render('home/index', [
            'message' => 'Bienvenue dans MVC',
            'users' => User::getAll()
        ]);
    }
}