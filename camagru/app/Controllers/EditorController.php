<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Image;
use App\Models\Thumbnail;
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

    public function save() {
        $userId = Session::get('user_id');
        if (!$userId) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Utilisateur non authentifié']);
            return;
        }
    
        // Récupérer les données JSON de la requête POST
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
    
        if (!isset($data['image']) || !isset($data['stickers'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Données manquantes']);
            return;
        }
    
        $imageData = str_replace('data:image/png;base64,', '', $data['image']);
        $imageData = base64_decode($imageData);
        $baseImage = imagecreatefromstring($imageData);
    
        if ($baseImage === false) {
            http_response_code(400);
            header('Content-Type: application/json');
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
    
        // Recréer l'image avec transparence
        ob_start();
        imagepng($baseImage);
        $imageData = ob_get_clean();
        $base64Thumbnail = base64_encode($this->createThumbnail($imageData));
        $base64Image = base64_encode($imageData);

        // Enregistrer l'image éditée
        $imageId = Image::create($userId, $base64Image, 'png');
        $thumbnailId = Thumbnail::create($userId, $imageId, $base64Thumbnail, 'png');
        $image = Image::findById($imageId);
    
        imagedestroy($baseImage);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'token_csrf' => Session::getCsrfToken(),
            'image' => [
                'id' => $image['id'],
                'data' => $image['data'],
            ]
        ]);
    }

    private function createThumbnail($imageData) {
        $image = imagecreatefromstring($imageData);

        // Redimensionner l'image à 200px max de largeur ou hauteur
        $width = imagesx($image);
        $height = imagesy($image);
        $width = $width > $height ? 200 : intval(200 * $width / $height);
        $thumbnail = imagescale($image, $width);

        // laisse la transparence
        imagesavealpha($thumbnail, true);
        $transparentColor = imagecolorallocatealpha($thumbnail, 0, 0, 0, 127);
        imagefill($thumbnail, 0, 0, $transparentColor);

        ob_start();
        imagepng($thumbnail);
        $thumbnailData = ob_get_clean();
        imagedestroy($image);
        imagedestroy($thumbnail);
        return $thumbnailData;
    }
}