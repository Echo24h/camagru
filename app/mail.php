<?php

function sendVerificationEmail($userId, $email, $token) {

    // Remplace par tes propres clés API
    $apiKey = $_ENV['MAILJET_API_KEY'];
    $apiSecret = $_ENV['MAILJET_SECRET_KEY'];

    // URL de l'API Mailjet
    $url = 'https://api.mailjet.com/v3.1/send';

    $link = 'http://localhost:8080/verify?token=' . $token . '&id=' . $userId;
    // Données de l'email
    $data = [
        'Messages' => [
            [
                'From' => [
                    'Email' => $_ENV['MAILJET_FROM_EMAIL'],
                    'Name' => 'Camagru (42 Project)'
                ],
                'To' => [
                    [
                        'Email' => $email,
                        'Name' => 'Gregory Borne'
                    ]
                ],
                'Subject' => 'Confirmation de votre adresse e-mail',
                'TextPart' => '',
                'HTMLPart' => '<p>Bonjour,</p><p>Cliquez sur le lien suivant pour valider votre adresse e-mail :</p><p><a href="' . $link .'">' . $link . '</a></p><p>Si vous n\'avez pas demandé cette inscription, ignorez ce message.</p>',
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
            echo 'Email envoyé avec succès !';
        } else {
            echo 'Erreur lors de l\'envoi de l\'email : ' . $responseData['Messages'][0]['Errors'][0]['ErrorMessage'];
        }
    }
    // Fermer cURL
    curl_close($ch);
}

?>