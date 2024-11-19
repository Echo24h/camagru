<?php

namespace Core;

use PDO;

class Model {

    protected static function getDB() {
        static $db = null;
        if ($db === null) {
            $config = require __DIR__ . '/../config/database.php';
            $db = new PDO($config['dsn'], $config['user'], $config['password']);
        }
        return $db;
    }
}