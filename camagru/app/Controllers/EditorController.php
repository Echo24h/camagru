<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Image;
use Core\Session;

class EditorController extends Controller {
    public function index() {

        $images = Image::findByUserId(Session::get('user_id'));

        // Décode les images en base64
        foreach ($images as $image) {
            $image['data'] = base64_decode($image['data']);
        }

        $this->render('editor/index', [
            'message' => 'Bienvenue dans MVC',
            'users' => User::getAll(),
            'images' => $images
        ]);
    }
}