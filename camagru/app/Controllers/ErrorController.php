<?php

namespace App\Controllers;

use Core\Controller;

class ErrorController extends Controller {

    public function notFound() {
        http_response_code(404);
        $this->render('/error/404');
    }

    public function forbidden() {
        http_response_code(403);
        $this->render('/error/403');
    }
}