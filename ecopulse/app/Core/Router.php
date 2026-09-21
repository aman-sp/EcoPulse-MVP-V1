<?php

class Router {
    private static $routes = [];
    
    public static function get($path, $controller, $method) {
        self::$routes['GET'][$path] = ['controller' => $controller, 'method' => $method];
    }
    
    public static function post($path, $controller, $method) {
        self::$routes['POST'][$path] = ['controller' => $controller, 'method' => $method];
    }
    
    public static function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        $uri = str_replace('\\', '/', $uri);
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        
        // Calculate directories to strip
        $scriptDir = dirname($scriptName);
        $rootDir = preg_replace('#/public$#i', '', $scriptDir);
        
        if ($scriptDir !== '/' && $scriptDir !== '.' && strpos($uri, $scriptDir) === 0) {
            $uri = substr($uri, strlen($scriptDir));
        } elseif ($rootDir !== '/' && $rootDir !== '.' && strpos($uri, $rootDir) === 0) {
            $uri = substr($uri, strlen($rootDir));
        }
        
        // Strip leading /public if still present
        if (strpos($uri, '/public/') === 0) {
            $uri = substr($uri, 7);
        } elseif ($uri === '/public') {
            $uri = '/';
        }
        
        $uri = '/' . ltrim($uri, '/');
        
        if (!isset(self::$routes[$method])) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        foreach (self::$routes[$method] as $routePath => $target) {
            // Convert {param} to regex
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $routePath);
            $pattern = "@^" . $pattern . "$@D";
            
            if (preg_match($pattern, $uri, $matches)) {
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }
                
                $controllerName = $target['controller'];
                $methodName = $target['method'];
                
                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $methodName)) {
                        call_user_func_array([$controller, $methodName], $params);
                        return;
                    }
                }
            }
        }
        
        http_response_code(404);
        echo "404 Not Found";
    }
}
