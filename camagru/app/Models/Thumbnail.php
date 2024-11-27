<?php

namespace App\Models;

use Core\Model;

class Thumbnail extends Model {

    public static function create($userId, $imageId, $data, $type) {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO thumbnails (user_id, image_id, data, type) VALUES (:user_id, :image_id, :data, :type)");
        $stmt->execute([
            'user_id' => $userId,
            'image_id' => $imageId,
            'data' => $data,
            'type' => $type
        ]);
        return $db->lastInsertId();
    }

    public static function getImageId($thumbnailId) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT image_id FROM thumbnails WHERE id = :id");
        $stmt->execute(['id' => $thumbnailId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function findByUserId($userId) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM thumbnails WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function findByImageId($imageId) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM thumbnails WHERE image_id = :image_id");
        $stmt->execute(['image_id' => $imageId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function delete($imageId) {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM thumbnails WHERE image_id = :image_id");
        return $stmt->execute(['image_id' => $imageId]);
    }
}