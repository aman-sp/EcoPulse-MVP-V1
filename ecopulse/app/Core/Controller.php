<?php

class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    protected function view($view, $data = []) {
        extract($data);
        $viewFile = APP_PATH . '/Views/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            http_response_code(404);
            echo 'View not found: ' . $view;
            return;
        }
        
        if (str_starts_with($view, 'admin/') && $view !== 'admin/login') {
            $contentView = $viewFile;
            require APP_PATH . '/Views/layouts/admin_layout.php';
        } elseif (str_starts_with($view, 'hospital/') && $view !== 'hospital/login') {
            $contentView = $viewFile;
            require APP_PATH . '/Views/layouts/hospital_layout.php';
        } else {
            require $viewFile;
        }
    }
    
    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
    
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function back() {
        $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL . '/';
        header('Location: ' . $referer);
        exit;
    }
    
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}
