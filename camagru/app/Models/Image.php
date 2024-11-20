<?php

namespace App\Models;

use Core\Model;

class Image extends Model {

    public static function create($userId, $data) {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO images (name, user_id, data) VALUES (:name, :user_id, :data)");
        $stmt->execute([
            'name' => 'default',
            'user_id' => $userId,
            'data' => $data
        ]);
        return $db->lastInsertId();
    }

    public static function getAll() {
        $db = self::getDB();
        $stmt = $db->query("SELECT * FROM images");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function findByUserId($userId) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM images WHERE user_id = :user_id");
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
}