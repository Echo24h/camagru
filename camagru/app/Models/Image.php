<?php

namespace App\Models;

use Core\Model;

class Image extends Model {

    public static function create($user, $data) {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO images (name, username, user_id, data) VALUES (:name, :username, :user_id, :data)");
        $stmt->execute([
            'name' => 'default',
            'username' => $user['username'],
            'user_id' => $user['id'],
            'data' => $data
        ]);
        return $db->lastInsertId();
    }

    public static function getAll() {
        $db = self::getDB();
        $stmt = $db->query("SELECT * FROM images ORDER BY id DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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