<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Image;
use App\Models\User;
use Core\Session;

class ProfilController extends Controller {

    private $imagesPerPage = 10;

    private function getUserImages($userId, $page) {
        $images = Image::getUserPage($userId, $page, $this->imagesPerPage);

        $new_images = [];
        foreach ($images as $image) {
            $image['username'] = User::findById($image['user_id'])['username'];
            $new_images[] = $image;
        }

        return $new_images;
    }

    public function index() {

        $id = $_GET['id'];

        $user = User::findById($id);

        if (!$user) {
            $this->render('error/404');
            return;
        }

        // TRES LENT, A OPTIMISER
        $images = Image::findByUserId($user['id']);

        $likes_received = 0;

        foreach ($images as $image) {
            $likes_received += $image['total_likes'];
        }
        // FIN TRES LENT

        if (isset($_GET['page'])) {
            $page = (int)$_GET['page'];
            $images = $this->getUserImages($user['id'], $page);
            header('Content-Type: application/json');
            echo json_encode($images);
        } else {
            $this->render('profil/index', [
                'user' => $user,
                'images' => $this->getUserImages($user['id'], 1),
                'likes_received' => $likes_received
            ]);
        }
    }

    public function settings() {
            
            if (!isset($_SESSION['user_id'])) {
                header('Location: /login');
                return;
            }

            $user = User::findById($_SESSION['user_id']);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['username'])) {
                    $user['username'] = $_POST['username'];
                    $this->updateUsername($user);
                }
                else if (isset($_POST['password'])) {
                    $user['password'] = $_POST['password'];
                    $this->updateUserPassword($user);
                }
                else if (isset($_POST['email'])) {
                    $email = $_POST['email'];
                    $user['email'] = $email;
                    $this->updateUserEmail($user);
                }
                else if (isset($_POST['notifications'])) {
                    $notifications = $_POST['notifications'];
                    $user['notifications'] = $notifications;
                    $this->updateUserNotifications($user);
                }
                else if (isset($_POST['email_visibility'])) {
                    $email_visibility = $_POST['email_visibility'];
                    $user['email_visibility'] = $email_visibility;
                    $this->updateUserEmailVisibility($user);
                }
                // else if (isset($_POST['delete'])) {
                //     User::delete($user['id']);
                //     header('Location: /logout');
                //     return;
                // }
                else {
                    $this->render('profil/settings', 
                        [
                            'user' => $user,
                            'error' => 'Erreur lors de la mise à jour de vos informations.'
                        ]
                    );
                    return;
                }
            }
    
            $this->render('profil/settings', 
                [
                    'user' => $user
                ]
            );
        
    }

    private function updateUserName($user) {
        if (User::userNameExist($user['username'])) {
            $this->render('profil/settings', ['user' => $user, 'error' => 'Nom d\'utilisateur déjà utilisé']);
            return;
        }
        if (User::update($user['id'], $user['username'], $user['email'])) {
            Session::set('username', $user['username']);
            $this->render('profil/settings', ['user' => $user, 'success' => 'Nom d\'utilisateur mis à jour.']);
        } else {
            $this->render('profil/settings', ['user' => $user, 'error' => 'Erreur lors de la mise à jour du nom d\'utilisateur.']);
        }
    }

    private function updateUserEmail($user) {
        if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
            $this->render('profil/settings', ['user' => $user, 'error' => 'Adresse email invalide']);
            return;
        }
        if (User::findByEmail($user['email'])) {
            $this->render('profil/settings', ['user' => $user, 'error' => 'Adresse email déjà utilisée']);
            return;
        }
        if (User::update($user['id'], $user['username'], $user['email'])) {
            $this->render('profil/settings', ['user' => $user, 'success' => 'Email mis à jour.']);
            Session::set('email', $user['email']);
        } else {
            $this->render('profil/settings', ['user' => $user, 'error' => 'Erreur lors de la mise à jour de l\'email.']);
        }
    }

    private function updateUserPassword($user) {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $user['password'])) {
            $this->render('profil/settings', ['user' => $user, 'error' => 'Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule et un chiffre']);
            return;
        }
        if (User::updatePassword($user['id'], $user['password'])) {
            $this->render('profil/settings', ['user' => $user, 'success' => 'Mot de passe mis à jour.']);
        } else {
            $this->render('profil/settings', ['user' => $user, 'error' => 'Erreur lors de la mise à jour du mot de passe.']);
        }
    }

    private function updateUserNotifications($user) {
        if ($_POST['notifications'] === "1" || $_POST['notifications'] === "0") {
            $status = $_POST['notifications'];
            if (User::setNotification($user['id'], $status)) {
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'notifications' => $status,
                    'token_csrf' => $_SESSION['csrf_token']
                ]);
                exit;
            }
        }
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error']);
        exit;
    }

    private function updateUserEmailVisibility($user) {
        if ($_POST['email_visibility'] === "1" || $_POST['email_visibility'] === "0") {
            $status = $_POST['email_visibility'];
            if (User::setEmailVisibility($user['id'], $status)) {
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'email_visibility' => $status,
                    'token_csrf' => $_SESSION['csrf_token']
                ]);
                exit;
            }
        }
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error']);
        exit;
    }

}