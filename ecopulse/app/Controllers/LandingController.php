<?php

class LandingController extends Controller {
    public function __construct() {
        // Do not require database connection just to view the landing page
    }

    public function index() {
        $candidates = [
            PUBLIC_PATH . '/landing/index.html',
            BASE_PATH . '/../landing/index.html',
            dirname(BASE_PATH) . '/landing/index.html'
        ];

        foreach ($candidates as $landingPath) {
            if (file_exists($landingPath)) {
                $content = file_get_contents($landingPath);
                // Ensure asset paths point correctly to /landing/assets/
                $content = str_replace('href="assets/', 'href="/landing/assets/', $content);
                $content = str_replace('src="assets/', 'src="/landing/assets/', $content);
                echo $content;
                exit;
            }
        }
        
        $this->redirect('/hospital/login');
    }
}
