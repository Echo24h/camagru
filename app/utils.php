<?php

function generateVerificationToken() {
    return bin2hex(random_bytes(32 / 2)); // Génère un token de $length caractères
}

// function sendVerificationEmail($email, $verificationToken) {
//     $subject = "Validez votre compte";
//     $message = "Bonjour,\n\nCliquez sur le lien suivant pour valider votre compte :\n\n";
//     $message .= "http://tonsite.com/verify.php?token=$verificationToken\n\n";
//     $message .= "Si vous n'avez pas demandé cette inscription, ignorez ce message.";
//     $headers = "From: test@test.com";
//     if (mail($email, $subject, $message, $headers)) {
//         return true;
//     } else {
//         echo "Erreur lors de l'envoi de l'e-mail de vérification.";
//         return false;
//     }
// }