<?php

namespace App\Models;

use Core\Model;

class Image extends Model {

    public static function create($userId, $data, $type) {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO images (name, user_id, data, type) VALUES (:name, :user_id, :data, :type)");
        $stmt->execute([
            'name' => 'Image',
            'user_id' => $userId,
            'data' => $data,
            'type' => $type
        ]);
        return $db->lastInsertId();
    }

    public static function getAllPage($page = 1, $limit = 10) {
        $db = self::getDB();
    
        // Calculer l'offset en fonction de la page courante et de la limite
        $offset = ($page - 1) * $limit;
    
        $stmt = $db->prepare("SELECT * FROM images ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getUserPage($userId, $page = 1, $limit = 10) {
        $db = self::getDB();
    
        // Calculer l'offset en fonction de la page courante et de la limite
        $offset = ($page - 1) * $limit;
    
        $stmt = $db->prepare("SELECT * FROM images WHERE user_id = :user_id ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getTotalImages() {
        $db = self::getDB();
        $stmt = $db->query("SELECT COUNT(*) FROM images");
        return $stmt->fetchColumn();
    }

    public static function findByUserId($userId) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM images WHERE user_id = :user_id ORDER BY id DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function findById($id) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM images WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function delete($id) {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM images WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public static function comment($id, $comment) {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE images SET comments = JSON_ARRAY_APPEND(comments, '$', :comment) WHERE id = :id");
        return $stmt->execute(['id' => $id, 'comment' => $comment]);
    }

    public static function getLikes($id) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT likes FROM images WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function updateLikes($id, $total_likes) {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE images SET total_likes = total_likes + :total_likes WHERE id = :id");
        return $stmt->execute(['id' => $id, 'total_likes' => $total_likes]);
    }

    public static function updateComments($id, $total_comments) {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE images SET total_comments = total_comments + :total_comments WHERE id = :id");
        return $stmt->execute(['id' => $id, 'total_comments' => $total_comments]);
    }
}