<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Image;
use Core\Session;

class ImageController extends Controller {

    public function save() {
        if (isset($_POST['image']) && !empty($_POST['image'])) {

            $imageData = $_POST['image'];

            if (preg_match('/^data:image\/(png|jpeg);base64,/', $imageData)) {
                // Enregistrer l'image en base64
                Image::create(Session::get('user_id'), $imageData);
                // Reponse HTTP 200 + text success
                echo "success";
                http_response_code(200);
            } else {
                echo "L'image doit être au format base64.";
                exit;
            }
        } else {
            $this->render('editor/index', ['error' => 'Veuillez prendre une photo']);
            return;
        }
    }

    public function delete() {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $image = Image::findById($_POST['id']);
            if ($image && $image['user_id'] == Session::get('user_id')) {
                Image::delete($_POST['id']);
                // Reponse HTTP 200 + text success
                echo "success";


            }
        }
    }

}