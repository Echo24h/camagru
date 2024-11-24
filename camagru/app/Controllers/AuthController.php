<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use Core\Session;
use App\Utils\Mail;

class AuthController extends Controller {
    
    public function login() {

        if (Session::get('user_id')) {
            header('Location: /');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            if (!$email || !$password) {
                $this->render('auth/login', ['error' => 'Veuillez remplir tous les champs']);
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->render('auth/login', ['error' => 'Adresse email invalide']);
                return;
            }

            $user = User::authenticate($email, $password);

            if ($user) {



                if (!User::isEmailVerified($email)) {
                    $this->render('auth/login', ['error' => 'Veuillez vérifier votre adresse e-mail pour activer votre compte']);
                    return;
                }
                Session::set('user_id', $user['id']);
                Session::set('username', $user['username']);
                Session::set('email', $user['email']);
                header('Location: /');
                return;
            } else {
                $this->render('auth/login', ['error' => 'Adresse email ou mot de passe incorrect']);
                return;
            }
        }
        $this->render('auth/login');
    }

    public function register() {

        if (Session::get('user_id')) {
            header('Location: /');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            if (!$username || !$email || !$password) {
                $this->render('auth/register', ['error' => 'Veuillez remplir tous les champs']);
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->render('auth/register', ['error' => 'Adresse email invalide']);
                return;
            }

            if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $this->render('auth/register', ['error' => 'Le nom d\'utilisateur ne doit contenir que des lettres, des chiffres et des underscores']);
                return;
            }

            $user = User::findByEmail($email);

            if ($user) {
                $this->render('auth/register', ['error' => 'Adresse email déjà utilisée']);
                return;
            }

            if (User::userNameExist($username)) {
                $this->render('auth/register', ['error' => 'Nom d\'utilisateur déjà utilisé']);
                return;
            }

            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
                $this->render('auth/register', ['error' => 'Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule et un chiffre']);
                return;
            }

            $userId = User::create($username, $email, $password);

            if ($userId) {
                if (Mail::sendVerificationEmail($userId)) {
                    $this->render('auth/login', ['success' => 'Inscription réussie. Veuillez vérifier votre adresse e-mail pour activer votre compte']);
                    return;
                } else {
                    User::delete($userId);
                    $this->render('auth/register', ['error' => 'Erreur lors de l\'inscription']);
                    return;
                }
            } else {
                $this->render('auth/register', ['error' => 'Erreur lors de l\'inscription']);
                return;
            }
        }
        $this->render('auth/register');
    }


    public function verifyEmail() {

        if (isset($_GET['id']) && isset($_GET['token'])) {
            $userId = $_GET['id'];
            $token = $_GET['token'];

            $user = User::findById($userId);

            if ($user && $user['email_verification_token'] === $token) {
                if (User::verifyEmail($userId)) {
                    $this->render('auth/login', ['success' => 'Adresse e-mail vérifiée avec succès']);
                    return;
                } else {
                    $this->render('auth/login', ['error' => 'Erreur lors de la vérification de l\'adresse e-mail']);
                    return;
                }
            } else {
                $this->render('error/404');
                return;
            }
        } else {
            $this->render('error/404');
            return;
        }
    }

    public function forgotPassword() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];

            if (!$email) {
                $this->render('auth/forgot-password', ['error' => 'Veuillez entrer votre adresse e-mail']);
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->render('auth/forgot-password', ['error' => 'Adresse email invalide']);
                return;
            }

            $user = User::findByEmail($email);

            if ($user) {
                if (!User::isEmailVerified($email)) {
                    $this->render('auth/forgot-password', ['error' => 'Veuillez vérifier votre adresse e-mail pour réinitialiser votre mot de passe']);
                    return;
                }
                if (!User::createPasswordResetToken($user['id'])) {
                    $this->render('auth/forgot-password', ['error' => 'Erreur lors de la réinitialisation du mot de passe']);
                    return;
                }
                if (Mail::sendResetPassword($user['id'])) {
                    $this->render('auth/login', ['success' => 'Un e-mail de réinitialisation de mot de passe a été envoyé']);
                    return;
                } else {
                    $this->render('auth/forgot-password', ['error' => 'Erreur lors de la réinitialisation du mot de passe']);
                    return;
                }
            } else {
                $this->render('auth/forgot-password', ['error' => 'Adresse email invalide']);
                return;
            }
        }
        $this->render('auth/forgot-password');
    }

    public function resetPassword() {
        if (isset($_GET['id']) && isset($_GET['token'])) {
            $userId = $_GET['id'];
            $token = $_GET['token'];
    
            $user = User::findById($userId);
    
            if ($user && $user['password_reset_token'] === $token) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $password = $_POST['password'];
    
                    if (!$password) {
                        $this->render('auth/reset-password', [
                            'error' => 'Veuillez entrer un nouveau mot de passe',
                            'id' => $userId,
                            'token' => $token
                        ]);
                        return;
                    }
                    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
                        $this->render('auth/reset-password', [
                            'error' => 'Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule et un chiffre',
                            'id' => $userId,
                            'token' => $token
                        ]);
                        return;
                    }
    
                    if (User::updatePassword($user['id'], $password)) {
                        $this->render('auth/login', ['success' => 'Mot de passe réinitialisé avec succès']);
                        return;
                    } else {
                        $this->render('auth/reset-password', [
                            'error' => 'Erreur lors de la réinitialisation du mot de passe',
                            'id' => $userId,
                            'token' => $token
                        ]);
                        return;
                    }
                }
                $this->render('auth/reset-password', [
                    'id' => $userId,
                    'token' => $token
                ]);
                return;
            }
        }
    
        $this->render('error/404');
    }


    public function logout() {
        Session::destroy();
        header('Location: /');
    }
}