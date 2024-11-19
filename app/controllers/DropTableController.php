<?php

require_once './models/Database.php';

class DropTableController {

    private $db;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function dropTables() {
        // Requête pour supprimer toutes les tables
        try {
            $sql = "DROP TABLE IF EXISTS users";
            $this->db->exec($sql);
            echo "Toutes les tables ont été supprimées avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de la table: " . $e->getMessage();
        }

        // Affiche toutes les tables restantes
        $stmt = $this->db->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
        print_r($tables);
    }

}