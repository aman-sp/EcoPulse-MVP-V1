<?php

class CsrfMiddleware {
    public static function handle() {
        if (Request::isPost()) {
            $token = Request::post('csrf_token') ?? '';
            if (!Session::verifyCsrf($token)) {
                http_response_code(403);
                die('CSRF token validation failed.');
            }
        }
    }
}
