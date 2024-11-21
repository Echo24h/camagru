<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Image;
use App\Models\Comment;
use App\Models\User;

class GalleryController extends Controller {

    public function index() {

        $images = Image::getAll();

        $new_images = [];
        // Décode les images en base64
        foreach ($images as $image) {
            //$image['data'] = base64_decode($image['data']);
            $image['username'] = User::findById($image['user_id'])['username'];
            $new_images[] = $image;
        }

        $this->render('gallery/index',
            [
                'images' => $new_images
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

        if ($image === false) {
            header('Location: /404');
            return;
        }

        $image['username'] = User::findById($image['user_id'])['username'];

        // Décode l'image en base64
        //$image['data'] = base64_decode($image['data']);

        if (!$image) {
            header('Location: /404');
            return;
        }

        $comments = Comment::findByImageId($id);
        $new_comments = [];

        foreach ($comments as $comment) {
            $comment['username'] = User::findById($comment['user_id'])['username'];
            $new_comments[] = $comment;
        }

        $this->render('/gallery/show',
            [   
                'image' => $image,
                'comments' => $new_comments
            ]
        );
    }
}