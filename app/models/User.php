<?php

require_once 'Database.php';

class User {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->createTableIfNotExists();
    }

    private function createTableIfNotExists() {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(100) NOT NULL,
                email_verified BOOLEAN DEFAULT 0,
                email_verification_token VARCHAR(255) DEFAULT NULL
            )
        ";

        $this->db->exec($sql);

        // // Ajouter des utilisateurs par défaut
        // $sqlInsert = "
        //     INSERT IGNORE INTO users (name, email)
        //     VALUES ('John Doe', 'john@example.com'),
        //         ('Jane Smith', 'jane@example.com')
        // ";

        // $this->db->exec($sqlInsert);
    }

    public function register($username, $email, $password) {
        // Vérifier si l'utilisateur existe déjà
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return null; // Retourner null si l'utilisateur existe déjà
        }

        // Hasher le mot de passe
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insérer l'utilisateur dans la base de données
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->execute(['username' => $username, 'email' => $email, 'password' => $passwordHash]);

        // Récupérer l'utilisateur nouvellement inséré
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $this->db->lastInsertId()]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function authenticate($username, $password) {
        // Requête pour trouver l'utilisateur par nom d'utilisateur
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
    
        // Récupérer l'utilisateur (si trouvé)
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user && password_verify($password, $user['password'])) {
            return $user; // L'utilisateur est authentifié
        }
    
        return null; // Retourner null si échec de l'authentification
    }

    public function isEmailVerified($userId) {
        $stmt = $this->db->prepare("SELECT email_verified FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user['email_verified'];
    }

    public function getAllUsers() {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
