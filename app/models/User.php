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
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE
            )
        ";

        $this->db->exec($sql);

        // Ajouter des utilisateurs par défaut
        $sqlInsert = "
            INSERT IGNORE INTO users (name, email)
            VALUES ('John Doe', 'john@example.com'),
                ('Jane Smith', 'jane@example.com')
        ";

        $this->db->exec($sqlInsert);
    }

    public function getAllUsers() {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
