<?php

namespace Core;

class View {
    public static function render($view, $data = []) {
        // Convertir les données en variables
        extract($data);

        // Bufferiser le contenu de la vue
        ob_start();
        require_once __DIR__ . '/../app/Views/' . $view . '.php';
        $content = ob_get_clean();

        // Inclure le layout principal avec le contenu capturé
        require_once __DIR__ . '/../app/Views/layouts/main.php';
    }
}