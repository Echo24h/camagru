<?php

require_once './utils.php';
require_once './mail.php';

class AuthController {

    public function verifyEmail() {

        if (isset($_GET['id']) && isset($_GET['token'])) {

            $userId = $_GET['id'];
            $token = $_GET['token'];

            $userModel = new User();
            $response = $userModel->verifyEmail($userId, $token);

            if ($response) {
                $success = "Votre adresse e-mail a été vérifiée avec succès. Vous pouvez maintenant vous connecter.";
                require_once './views/login.php';
            } else {
                $error = "Erreur lors de la vérification de l'adresse e-mail.";
                require_once './views/login.php';
            }
        } else {
            $error = "Erreur lors de la vérification de l'adresse e-mail.";
            require_once './views/login.php';
        }
    }

    public function login() {

        if (isset($_POST['username']) && isset($_POST['password'])) {

            $username = $_POST['username'];
            $password = $_POST['password'];

            $userModel = new User();
            $user = $userModel->authenticate($username, $password);

            if ($user !== null) {
                if ($userModel->isEmailVerified($user['id'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    header("Location: /home");
                } else {
                    $error = "Veuillez vérifier votre adresse e-mail pour activer votre compte.";
                    require_once './views/login.php';
                }
            } else {
                $error = "Nom d'utilisateur ou mot de passe incorrect.";
                require_once './views/login.php';
            }
        } else {
            require_once './views/login.php';
        }
    }

    public function register() {

        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {

            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new User();
            $verificationToken = generateVerificationToken();

            $user = $userModel->register($username, $email, $password, $verificationToken);
            if ($user) {
                sendVerificationEmail($user['id'], $email, $verificationToken);
                $success = "Inscription réussie. Veuillez vérifier votre adresse e-mail pour activer votre compte.";
                require_once './views/login.php';
            } else {
                $error = "Erreur lors de l'inscription.";
                require_once './views/register.php';
            }
        } else {
            require_once './views/register.php';
        }
    }

    public function logout() {
        session_destroy();
        header("Location: /login");
    }
}