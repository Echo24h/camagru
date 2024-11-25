<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Image;
use Core\Session;

class EditorController extends Controller {
    public function index() {

        $userId = Session::get('user_id');
        $images = Image::findByUserId($userId);

        // Décode les images en base64
        foreach ($images as $image) {
            $image['data'] = base64_decode($image['data']);
        }

        $this->render('editor/index', [
            'user_id' => $userId,
            'images' => $images
        ]);
    }

    public function edit() {
        // Vérifie que les données nécessaires sont présentes
        if (!isset($_POST['image']) || !isset($_POST['stickers'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Image ou autocollants manquants']);
            return;
        }

        $userId = Session::get('user_id');
        if (!$userId) {
            http_response_code(403);
            echo json_encode(['error' => 'Utilisateur non authentifié']);
            return;
        }

        $imageData = $_POST['image']; // Image de base (base64)
        $stickers = $_POST['stickers']; // Liste des autocollants (position et chemin)

        // Décodage de l'image de base
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = base64_decode($imageData);
        $baseImage = imagecreatefromstring($imageData);

        if ($baseImage === false) {
            http_response_code(400);
            echo json_encode(['error' => 'Image invalide']);
            return;
        }

        // Application des autocollants sur l'image
        foreach ($stickers as $sticker) {
            if (!isset($sticker['src'], $sticker['x'], $sticker['y'], $sticker['width'], $sticker['height'])) {
                continue; // Ignorer les autocollants mal formés
            }

            $stickerImage = imagecreatefrompng($sticker['src']); // Charger l'image de l'autocollant
            if ($stickerImage === false) {
                continue; // Ignorer les autocollants invalides
            }

            // Copier et redimensionner l'autocollant sur l'image de base
            imagecopyresampled(
                $baseImage,
                $stickerImage,
                $sticker['x'], $sticker['y'], 0, 0,
                $sticker['width'], $sticker['height'],
                imagesx($stickerImage), imagesy($stickerImage)
            );

            imagedestroy($stickerImage); // Libérer la mémoire après utilisation
        }

        // Enregistrer l'image finale sur le serveur
        $filename = 'uploads/' . uniqid() . '.png';
        imagepng($baseImage, $filename);
        imagedestroy($baseImage); // Libérer la mémoire après traitement

        // Enregistrer dans la base de données
        $image = new Image();
        $image->user_id = $userId;
        $image->data = base64_encode(file_get_contents($filename)); // Stockage en base64 si nécessaire
        $image->filename = $filename; // Stockage du chemin de fichier
        $image->save();

        // Répondre au client
        echo json_encode(['success' => true, 'url' => $filename]);
    }
}