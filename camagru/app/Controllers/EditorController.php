<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Image;
use Core\Session;

class EditorController extends Controller {
    public function index() {

        $userId = Session::get('user_id');
        $images = Image::findByUserId($userId);

        // Décode les images en base64
        foreach ($images as $image) {
            $image['data'] = base64_decode($image['data']);
        }

        $this->render('editor/index', [
            'user_id' => $userId,
            'images' => $images
        ]);
    }
}