<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Image;

class GaleryController extends Controller {

    public function index() {

        $images = Image::getAll();

        // Décode les images en base64
        foreach ($images as $image) {
            $image['data'] = base64_decode($image['data']);
        }

        $this->render('galery/index',
            [
                'images' => $images
            ]
        );
    }
}