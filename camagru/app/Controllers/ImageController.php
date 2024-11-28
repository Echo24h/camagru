<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Image;
use App\Models\Thumbnail;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use App\Utils\Mail;
use Core\Session;

class ImageController extends Controller {

    public function get() {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $image = Image::findById($_GET['id']);
            if ($image) {
                if ($image['type'] == 'gif') {
                    header('Content-Type: image/gif');
                } else {
                    header('Content-Type: image/png');
                }
                $decodedImage = base64_decode($image['data']);
                echo $decodedImage;
                
                
            } else {
                http_response_code(404);
                echo "Image introuvable.";
            }
        } else {
            http_response_code(400);
            echo "Veuillez sélectionner une image.";
        }
    }

    public function thumbnail() {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $thumbnail = Thumbnail::findByImageId($_GET['id']);
            if ($thumbnail) {
                if ($thumbnail['type'] == 'gif') {
                    header('Content-Type: image/gif');
                } else {
                    header('Content-Type: image/png');
                }
                $decodedThumbnail = base64_decode($thumbnail['data']);
                echo $decodedThumbnail;
            } else {
                http_response_code(404);
                echo "Thumbnail introuvable.";
            }
        } else {
            http_response_code(400);
            echo "Veuillez sélectionner une thumbnail.";
        }
    }

    public function delete() {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $image = Image::findById($_POST['id']);
            if ($image && $image['user_id'] == Session::get('user_id')) {
                Image::delete($_POST['id']);
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'token_csrf' => Session::getCsrfToken()
                ]);
            }
            else {
                http_response_code(403);
                echo "Vous n'avez pas le droit de supprimer cette image.";
            }
        }
    }


    public function like() {
        if (!Session::isAuthenticated()) {
            // Dire a Javascript de rediriger vers la page de connexion
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
                        'token_csrf' => Session::getCsrfToken(),
                        'action' => 'unlike',
                        'count' => Like::count($_POST['id'])
                    ]);
                } else {
                    Image::updateLikes($_POST['id'], 1);
                    Like::create(Session::get('user_id'), $_POST['id']);
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'token_csrf' => Session::getCsrfToken(),
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