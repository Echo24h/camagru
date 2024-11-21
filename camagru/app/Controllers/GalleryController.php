<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Image;

class GalleryController extends Controller {

    public function index() {

        $images = Image::getAll();

        // Décode les images en base64
        foreach ($images as $image) {
            $image['data'] = base64_decode($image['data']);
        }

        $this->render('gallery/index',
            [
                'images' => $images
            ]
        );
    }

    public function show() {

        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header('Location: /404');
            return;
        }
        
        $id = $_GET['id'];

        $image = Image::findById($id);

        // Décode l'image en base64
        //$image['data'] = base64_decode($image['data']);

        if (!$image) {
            header('Location: /404');
            return;
        }

        $this->render('gallery/show',
            [   
                'image' => $image,
            ]
        );
    }
}