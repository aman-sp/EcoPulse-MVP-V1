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
        } else {
            $hospitalUserModel = new HospitalUser();
            $hospitalUser = $hospitalUserModel->findByEmail($email);
            if (!$hospitalUser) {
                $hospitalUser = $hospitalUserModel->findByUsername($email);
            }

            if ($hospitalUser) {
                Session::flash('This email belongs to a Hospital account. We have redirected you to the Hospital Portal.', 'danger');
                return $this->redirect('/hospital/login');
            }

            Session::flash('Invalid email or password.', 'error');
            return $this->redirect('/admin/login');
        }
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
