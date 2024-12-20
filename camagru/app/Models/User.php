<?php

namespace App\Models;

use Core\Model;
use App\Utils\Utils;

class User extends Model {

    public static function create($username, $email, $password) {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO users (username, email, password, email_verification_token) VALUES (:username, :email, :password, :email_verification_token)");
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'email_verification_token' => Utils::generateToken()
        ]);
        return $db->lastInsertId();
    }

    public static function getAll() {
        $db = self::getDB();
        $stmt = $db->query("SELECT * FROM users");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function findByEmail($email) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function verifyEmail($userId) {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE users SET email_verified = 1, email_verification_token = NULL WHERE id = :id");
        return $stmt->execute(['id' => $userId]);
    }

    public static function isEmailVerified($email) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT email_verified FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user['email_verified'];
    }

    public static function findById($id) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function userNameExist($username) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function update($id, $username, $email) {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE users SET username = :username, email = :email WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'username' => $username,
            'email' => $email
        ]);
    }

    public static function setNotification($id, $notifications) {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE users SET notifications = :notifications WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'notifications' => $notifications
        ]);
    }

    public static function setEmailVisibility($id, $email_visibility) {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE users SET email_visibility = :email_visibility WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'email_visibility' => $email_visibility
        ]);
    }

    public static function updatePassword($userId, $password) {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE users SET password = :password, password_reset_token = NULL WHERE id = :userId");
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        return $stmt->execute([
            'userId' => $userId,
            'password' => $hashedPassword
        ]);
    }

    public static function createPasswordResetToken($userId) {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE users SET password_reset_token = :password_reset_token WHERE id = :userId");
        return $stmt->execute([
            'userId' => $userId, // Correction ici
            'password_reset_token' => Utils::generateToken()
        ]);
    }

    public static function getPasswordResetToken($userId) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT password_reset_token FROM users WHERE id = :userId");
        $stmt->execute(['userId' => $userId]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user['password_reset_token'];
    }

    public static function delete($id) {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public static function authenticate($email, $password) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

}