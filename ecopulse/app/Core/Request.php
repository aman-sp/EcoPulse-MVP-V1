<?php

class Request {
    public static function method() {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    public static function isPost() {
        return self::method() === 'POST';
    }
    
    public static function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    public static function get($key, $default = null) {
        return isset($_GET[$key]) ? htmlspecialchars(trim($_GET[$key]), ENT_QUOTES, 'UTF-8') : $default;
    }
    
    public static function post($key, $default = null) {
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }
    
    public static function file($key) {
        return $_FILES[$key] ?? null;
    }
    
    public static function all() {
        return $_POST;
    }
    
    public static function ip() {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}
