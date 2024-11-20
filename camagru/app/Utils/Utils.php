<?php

namespace App\Utils;

class Utils {

    public static function formatDate($date) {
        $dateTime = new \DateTime($date);
        return $dateTime->format('Y-m-d H:i:s');
    }

    public static function generateToken() {
        return bin2hex(random_bytes(32 / 2)); // Génère un token de $length caractères
    }

    public static function decodeBase64($data) {
        return base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
    }
}