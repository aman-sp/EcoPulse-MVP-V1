<?php


class AdminSettingsController extends Controller {
    public function index() {
        AdminGuard::handle();
        
        $settingModel = new Setting();
        $settings = $settingModel->getAll();
        
        return $this->view('admin/settings', [
            'settings' => $settings,
            'pageTitle' => 'Settings',
            'currentPage' => 'settings'
        ]);
    }

    public function update() {
        AdminGuard::handle();
        CsrfMiddleware::handle();
        
        $settingModel = new Setting();
        $data = Request::all();
        
        foreach ($data as $key => $value) {
            if ($key !== 'csrf_token') {
                $settingModel->setValue($key, $value);
            }
        }
        
        Session::flash('Settings updated successfully.', 'success');
        return $this->redirect('/admin/settings');
    }

    public function profile() {
        AdminGuard::handle();
        
        $adminModel = new Admin();
        $admin = $adminModel->find(Session::userId());
        
        return $this->view('admin/profile', [
            'admin' => $admin,
            'pageTitle' => 'Profile',
            'currentPage' => 'profile'
        ]);
    }

    public function updateProfile() {
        AdminGuard::handle();
        CsrfMiddleware::handle();
        
        $adminModel = new Admin();
        $userId = Session::userId();
        
        $data = [
            'name' => Request::post('name'),
            'email' => Request::post('email')
        ];
        
        $password = Request::post('new_password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        $adminModel->update($userId, $data);
        
        // Update session
        Session::set('user_name', $data['name']);
        Session::set('user_email', $data['email']);
        
        Session::flash('Profile updated successfully.', 'success');
        return $this->redirect('/admin/profile');
    }
}
