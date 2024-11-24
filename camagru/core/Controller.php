<?php

namespace Core;

class Controller {
    public function render($view, $data = []) {
        // Appeler la méthode de la classe View pour rendre la vue
        \Core\View::render($view, $data);
    }
}