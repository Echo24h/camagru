<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Image;
use App\Models\User;

class ProfilController extends Controller {

    public function index() {

        if (!isset($_GET['id'])) {
            $this->render('error/404');
            return;
        }

        $id = $_GET['id'];

        $user = User::findById($id);

        if (!$user) {
            $this->render('error/404');
            return;
        }

        $images = Image::findByUserId($user['id']);

        $likes_received = 0;

        foreach ($images as $image) {
            $likes_received += $image['total_likes'];
        }

        $this->render('profil/index', [
            'user' => $user,
            'images' => $images,
            'likes_received' => $likes_received
        ]);
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
        } else {
            $this->render('profil/settings', ['user' => $user, 'error' => 'Erreur lors de la mise à jour de l\'email.']);
        }
    }

    private function updateUserPassword($user) {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $user['password'])) {
            $this->render('profil/settings', ['user' => $user, 'error' => 'Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule et un chiffre']);
            return;
        }
        if (User::updatePassword($user['email'], $user['password'])) {
            $this->render('profil/settings', ['user' => $user, 'success' => 'Mot de passe mis à jour.']);
        } else {
            $this->render('profil/settings', ['user' => $user, 'error' => 'Erreur lors de la mise à jour du mot de passe.']);
        }
    }

}