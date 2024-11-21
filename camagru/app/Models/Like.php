<?php

namespace App\Models;

use Core\Model;

class Like extends Model {

    public static function create($userId, $imageId) {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO likes (user_id, image_id) VALUES (:user_id, :image_id)");
        $stmt->execute([
            'user_id' => $userId,
            'image_id' => $imageId
        ]);
        return $db->lastInsertId();
    }

    public static function delete($userId, $imageId) {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM likes WHERE user_id = :user_id AND image_id = :image_id");
        return $stmt->execute([
            'user_id' => $userId,
            'image_id' => $imageId
        ]);
    }

    public static function isLiked($userId, $imageId) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM likes WHERE user_id = :user_id AND image_id = :image_id");
        $stmt->execute([
            'user_id' => $userId,
            'image_id' => $imageId
        ]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function count($imageId) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM likes WHERE image_id = :image_id");
        $stmt->execute(['image_id' => $imageId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}