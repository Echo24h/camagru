<?php

namespace App\Models;

use Core\Model;

class Comment extends Model {

    public static function create($userId, $imageId, $content) {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO comments (user_id, image_id, content) VALUES (:user_id, :image_id, :content)");
        $stmt->execute([
            'user_id' => $userId,
            'image_id' => $imageId,
            'content' => $content
        ]);
        return $db->lastInsertId();
    }

    public static function delete($id) {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM comments WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public static function findByImageId($imageId) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM comments WHERE image_id = :image_id");
        $stmt->execute(['image_id' => $imageId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function count($imageId) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM comments WHERE image_id = :image_id");
        $stmt->execute(['image_id' => $imageId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}