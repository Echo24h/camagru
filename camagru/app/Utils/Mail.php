<?php

namespace App\Utils;

use App\Models\User;
use App\Models\Comment;

class Mail {

    private static function sendEmail($to, $subject, $content) {
        $apiKey = $_ENV['MAILJET_API_KEY'];
        $apiSecret = $_ENV['MAILJET_SECRET_KEY'];
        $url = 'https://api.mailjet.com/v3.1/send';
        $data = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $_ENV['MAILJET_FROM_EMAIL'],
                        'Name' => 'Camagru (42 Project)'
                    ],
                    'To' => [
                        [
                            'Email' => $to,
                            'Name' => $to
                        ]
                    ],
                    'Subject' => $subject,
                    'TextPart' => '',
                    'HTMLPart' => $content,
                    'CustomID' => 'AppGettingStartedTest'
                ]
            ]
        ];
        // Initialiser cURL
        $ch = curl_init($url);
    
        // Paramètres pour la requête cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . base64_encode("$apiKey:$apiSecret"),
            'Content-Type: application/json'
        ]);
    
        // Exécuter la requête
        $response = curl_exec($ch);
    
        // Vérifier les erreurs cURL
        if(curl_errno($ch)) {
            echo 'Erreur cURL : ' . curl_error($ch);
        } else {
            // Décoder la réponse JSON
            $responseData = json_decode($response, true);
    
            // Vérifier si l'email a été envoyé avec succès
            if (isset($responseData['Messages'][0]['Status']) && $responseData['Messages'][0]['Status'] == 'success') {
                curl_close($ch);
                return true;
            } else {
                echo 'Erreur lors de l\'envoi de l\'email : ' . $responseData['Messages'][0]['Errors'][0]['ErrorMessage'];
            }
        }
        curl_close($ch);
        return false;
    }

    public static function sendVerificationEmail($userId) {

        // Remplace par tes propres clés API
        
        $subject = 'Confirmation de votre adresse e-mail';
        $user = User::findById($userId);
        $token = $user['email_verification_token'];
        $link = $_ENV['DOMAIN'] . "/verify-email?token=$token&id=$userId";

        $content = "
            <p>Bonjour " . htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') . ",</p>
            <p>Merci de vous être inscrit sur Camagru. Veuillez cliquer sur le lien ci-dessous pour confirmer votre adresse e-mail :</p>
            <a href=\"" . htmlspecialchars($link, ENT_QUOTES, 'UTF-8') . "\">" . htmlspecialchars($link, ENT_QUOTES, 'UTF-8') . "</a>
            <p>Si vous n'êtes pas à l'origine de cette inscription, veuillez ignorer cet e-mail.</p>
        ";
        return self::sendEmail($user['email'], $subject, $content);
    }

    public static function sendResetPassword($userId) {

        // Remplace par tes propres clés API
        $subject = 'Réinitialisation de votre mot de passe';
        $user = User::findById($userId);
        $token = $user['password_reset_token'];
        $link = $_ENV['DOMAIN'] . "/reset-password?token=$token&id=$userId";

        $content = "
            <p>Bonjour " . htmlspecialchars($user['username']) . ",</p>
            <p>Vous avez demandé une réinitialisation de votre mot de passe. Veuillez cliquer sur le lien ci-dessous pour choisir un nouveau mot de passe :</p>
            <a href=" . htmlspecialchars($link) . ">" . htmlspecialchars($link) . "</a>
            <p>Si vous n'êtes pas à l'origine de cette demande, veuillez ignorer cet e-mail.</p>
        ";
        return self::sendEmail($user['email'], $subject, $content);
    }

    public static function sendCommentNotification($userId, $imageId, $commentId) {
        $subject = 'Nouveau commentaire sur votre photo';
        $comment = Comment::findById($commentId);
        $user = User::findById($userId);
        $sender = User::findById($comment['user_id']);
        $link = $_ENV['DOMAIN'] . "/gallery/show?id=" . $imageId;

        $content = "
            <p>Bonjour " . htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') . ",</p>
            <p>Vous avez reçu un nouveau commentaire sur votre photo :</p>
            <p><strong>" . htmlspecialchars($sender['username'], ENT_QUOTES, 'UTF-8') . ": </strong> " . htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8') . "</p>
            <a href=\"" . htmlspecialchars($link, ENT_QUOTES, 'UTF-8') . "\">Voir la photo</a>
        ";
        return self::sendEmail($user['email'], $subject, $content);
    }
}