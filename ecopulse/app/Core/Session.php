<?php

class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $timeout = $_ENV['SESSION_TIMEOUT'] ?? 3600;
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
            self::destroy();
        }
        $_SESSION['last_activity'] = time();
    }
    
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }
    
    public static function has($key) {
        return isset($_SESSION[$key]);
    }
    
    public static function remove($key) {
        unset($_SESSION[$key]);
    }
    
    public static function destroy() {
        session_destroy();
    }
    
    public static function regenerate() {
        session_regenerate_id(true);
    }
    
    public static function flash($message, $type = 'success') {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    
    public static function getFlash() {
        $msg = $_SESSION['flash_message'] ?? null;
        $type = $_SESSION['flash_type'] ?? 'info';
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        return $msg ? ['message' => $msg, 'type' => $type] : null;
    }
    
    public static function generateCsrf() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function verifyCsrf($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public static function csrfField() {
        $token = self::generateCsrf();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
    
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public static function isAdmin() {
        return ($_SESSION['user_role'] ?? '') === 'admin';
    }
    
    public static function isHospital() {
        return ($_SESSION['user_role'] ?? '') === 'hospital';
    }
    
    public static function userId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    public static function hospitalId() {
        return $_SESSION['hospital_id'] ?? null;
    }
}
