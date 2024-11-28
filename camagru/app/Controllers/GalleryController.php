<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Image;
use App\Models\Comment;
use App\Models\User;

class GalleryController extends Controller {

    private $imagesPerPage = 10;

    private function getImages($page) {
        $images = Image::getAllPage($page, $this->imagesPerPage);

        $new_images = [];
        foreach ($images as $image) {
            $image['username'] = User::findById($image['user_id'])['username'];
            $new_images[] = $image;
        }

        return $new_images;
    }

    public function index() {
        if (isset($_GET['page'])) {
            $page = (int)$_GET['page'];
            $images = $this->getImages($page);
            header('Content-Type: application/json');
            echo json_encode($images);
        } else {
            $this->render('gallery/index', ['images' => $this->getImages(1)]);
        }
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