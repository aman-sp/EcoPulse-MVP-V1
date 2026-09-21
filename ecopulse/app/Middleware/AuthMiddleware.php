<?php

class AuthMiddleware {
    public static function handle() {
        Session::start();
        if (!Session::isLoggedIn()) {
            $uri = $_SERVER['REQUEST_URI'];
            if (strpos($uri, '/admin') !== false) {
                header('Location: ' . BASE_URL . '/admin/login');
            } else {
                header('Location: ' . BASE_URL . '/hospital/login');
            }
            exit;
        }
    }
}
