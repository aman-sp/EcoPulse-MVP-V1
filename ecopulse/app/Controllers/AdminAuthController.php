<?php

class AdminAuthController extends Controller {
    public function showLogin() {
        if (Auth::check() && Auth::role() === 'admin') {
            return $this->redirect('/admin/dashboard');
        }
        return $this->view('admin/login', [
            'pageTitle' => 'Admin Login'
        ]);
    }

    public function login() {
        CsrfMiddleware::handle();
        
        $email = Request::post('email');
        $password = Request::post('password');

        // 1. Check Admin User
        $adminModel = new Admin();
        $admin = $adminModel->findBy('email', $email);

        if ($admin && Auth::verifyPassword($password, $admin['password'])) {
            Auth::login([
                'id' => $admin['id'],
                'name' => $admin['name'],
                'email' => $admin['email'],
                'role' => 'admin'
            ]);
            
            $activityLog = new ActivityLog();
            $activityLog->log('admin', $admin['id'], 'login', 'Admin logged in successfully.');
            
            return $this->redirect('/admin/dashboard');
        }

        // 2. Check Hospital User (Auto-login and direct to hospital dashboard)
        $hospitalUserModel = new HospitalUser();
        $hospitalUser = $hospitalUserModel->findByEmail($email);
        if (!$hospitalUser) {
            $hospitalUser = $hospitalUserModel->findByUsername($email);
        }

        if ($hospitalUser && Auth::verifyPassword($password, $hospitalUser['password'])) {
            if (isset($hospitalUser['status']) && $hospitalUser['status'] !== 'active') {
                Session::flash('Your account is not active. Please contact administrator.', 'danger');
                return $this->redirect('/admin/login');
            }

            Auth::login([
                'id' => $hospitalUser['id'],
                'name' => $hospitalUser['name'],
                'email' => $hospitalUser['email'],
                'role' => 'hospital',
                'hospital_id' => $hospitalUser['hospital_id']
            ]);

            $hospitalUserModel->updateLastLogin($hospitalUser['id']);

            $activityLog = new ActivityLog();
            $activityLog->log('hospital', $hospitalUser['id'], 'login', 'Hospital user logged in via unified login.');

            return $this->redirect('/hospital/dashboard');
        }

        Session::flash('Invalid email or password.', 'error');
        return $this->redirect('/admin/login');
    }

    public function logout() {
        if (Auth::check() && Auth::role() === 'admin') {
            $activityLog = new ActivityLog();
            $activityLog->log('admin', Auth::id(), 'logout', 'Admin logged out.');
        }
        
        Auth::logout();
        return $this->redirect('/admin/login');
    }
}
