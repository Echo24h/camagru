<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Image;
use App\Models\User;
use Core\Session;

class ImageController extends Controller {

    public function save() {
        if (isset($_POST['image']) && !empty($_POST['image'])) {

            $imageData = $_POST['image'];

            if (preg_match('/^data:image\/(png|jpeg);base64,/', $imageData)) {
                // Enregistrer l'image en base64
                $user = User::findById(Session::get('user_id'));
                $imageId = Image::create($user, $imageData);
                $image = Image::findById($imageId);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'image' => [
                        'id' => $image['id'],
                        'data' => $image['data']  // Si vous voulez renvoyer l'image en base64
                    ]
                ]);
                exit;
            } else {
                http_response_code(400);
                echo "L'image doit être au format base64.";
                exit;
            }
        } else {
            http_response_code(400);
            echo "Veuillez sélectionner une image.";
            return;
        }
    }

    public function delete() {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $image = Image::findById($_POST['id']);
            if ($image && $image['user_id'] == Session::get('user_id')) {
                Image::delete($_POST['id']);
                http_response_code(200);
                echo "success";
            }
            else {
                http_response_code(403);
                echo "Vous n'avez pas le droit de supprimer cette image.";
            }
        }
    }

}