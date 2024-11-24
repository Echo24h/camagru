<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Image;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use App\Utils\Mail;
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


    public function like() {
        if (!Session::isAuthenticated()) {
            // Dire a AJAX de rediriger vers la page de connexion
            http_response_code(401);
            echo "Veuillez vous connecter pour aimer une image.";
            return;
        }
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $image = Image::findById($_POST['id']);
            if ($image) {
                $like = Like::isLiked(Session::get('user_id'), $_POST['id']);
                if ($like) {
                    Like::delete(Session::get('user_id'), $_POST['id']);
                    Image::updateLikes($_POST['id'], -1);
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'action' => 'unlike',
                        'count' => Like::count($_POST['id'])
                    ]);
                } else {
                    Image::updateLikes($_POST['id'], 1);
                    Like::create(Session::get('user_id'), $_POST['id']);
                    echo json_encode([
                        'success' => true,
                        'action' => 'like',
                        'count' => Like::count($_POST['id'])
                    ]);
                }
            } else {
                http_response_code(404);
                echo "Image introuvable.";
            }
        } else {
            http_response_code(400);
            echo "Veuillez sélectionner une image.";
        }
    }

    public function comment() {
        if (!Session::isAuthenticated()) {
            $this->render('auth/login', ['error' => 'Veuillez vous connecter pour commenter une image.']);
            return;
        }
        if (isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['comment']) && !empty($_POST['comment'])) {
            $image = Image::findById($_POST['id']);
            if ($image) {
                $commentId = Comment::create(Session::get('user_id'), $_POST['id'], $_POST['comment']);
                Image::updateComments($_POST['id'], 1);
                $comments = Comment::findByImageId($_POST['id']);
                if ($image['user_id'] != Session::get('user_id')) {
                    $imageUser = User::findById($image['user_id']);
                    if ($imageUser['notifications']) {
                        Mail::sendCommentNotification($image['user_id'], $_POST['id'], $commentId);
                    }
                }
                header('Location: /gallery/show?id=' . $_POST['id']);
            } else {
                http_response_code(404);
                echo "Image introuvable.";
            }
        }
        else {
            http_response_code(400);
            echo "Veuillez sélectionner une image et entrer un commentaire.";
        }
    }
}