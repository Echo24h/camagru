<?php

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $host = $_ENV['MYSQL_HOST'];
        $db = $_ENV['MYSQL_DATABASE'];
        $user = $_ENV['MYSQL_USER'];
        $password = $_ENV['MYSQL_PASSWORD'];

        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
