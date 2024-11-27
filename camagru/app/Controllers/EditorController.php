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

        $this->render('editor/index', [
            'user_id' => $userId,
            'images' => $images
        ]);
    }

    public function save() {
        $userId = Session::get('user_id');
        if (!$userId) {
            http_response_code(403);
            echo json_encode(['error' => 'Utilisateur non authentifié']);
            return;
        }
    
        // Récupérer les données JSON de la requête POST
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
    
        if (!isset($data['image']) || !isset($data['stickers'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Données manquantes']);
            return;
        }

        // Décoder et créer l'image de base (supporte PNG et GIF)
        if (preg_match('/^data:image\/(png|gif);base64,/', $data['image'], $matches)) {
            $imageType = $matches[1];
            $imageData = base64_decode(preg_replace('/^data:image\/(png|gif);base64,/', '', $data['image']));
            if ($imageType === 'png') {
                $baseImage = imagecreatefromstring($imageData);
            } elseif ($imageType === 'gif') {
                $baseImage = imagecreatefromstring($imageData);
            } else {
                $baseImage = false;
            }
        } else {
            $baseImage = false;
        }
    
        if ($baseImage === false) {
            http_response_code(400);
            echo json_encode(['error' => 'Image invalide']);
            return;
        }

        // Activer la transparence sur l'image de base
        imagesavealpha($baseImage, true);
        $transparentColor = imagecolorallocatealpha($baseImage, 0, 0, 0, 127);
        imagefill($baseImage, 0, 0, $transparentColor);
    
        // Application des autocollants
        foreach ($data['stickers'] as $sticker) {
            if (!isset($sticker['src'], $sticker['x'], $sticker['y'], $sticker['width'], $sticker['height'])) {
                continue;
            }
    
            $baseURL = $_SERVER['DOCUMENT_ROOT'] . $sticker['src'];
            if (!file_exists($baseURL)) {
                continue;
            }
    
            $stickerImage = imagecreatefrompng($baseURL);
            if ($stickerImage === false) {
                continue;
            }
    
            // Activer la transparence sur l'autocollant
            imagesavealpha($stickerImage, true);
    
            // Copier et redimensionner l'autocollant
            imagecopyresampled(
                $baseImage,
                $stickerImage,
                intval(round($sticker['x'])),
                intval(round($sticker['y'])),
                0,
                0,
                intval(round($sticker['width'])),
                intval(round($sticker['height'])),
                imagesx($stickerImage),
                imagesy($stickerImage)
            );
    
            imagedestroy($stickerImage);
        }

        $base64Image = '';
    
        if ($imageType === 'png') {
            // Recréer l'image avec transparence
            ob_start();
            imagepng($baseImage);
            $imageData = ob_get_clean();
            $base64Image = base64_encode($imageData);
        } elseif ($imageType === 'gif') {
            // Recréer l'image avec transparence
            ob_start();
            imagegif($baseImage);
            $imageData = ob_get_clean();
            $base64Image = base64_encode($imageData);
        }
        // Enregistrer l'image éditée
        $imageId = Image::create($userId, $base64Image, $imageType);
        $image = Image::findById($imageId);
    
        imagedestroy($baseImage);
    
        echo json_encode([
            'success' => true,
            'image' => [
                'id' => $image['id'],
            ]
        ]);
    }
}