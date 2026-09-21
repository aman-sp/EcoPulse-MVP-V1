<?php

class HospitalAuthController extends Controller {
    public function showLogin() {
        if (Auth::check() && Auth::role() === 'hospital') {
            $this->redirect('/hospital/dashboard');
            return;
        }
        
        $this->view('hospital/login');
    }
    
    public function login() {
        CsrfMiddleware::handle();
        
        if (!Request::isPost()) {
            $this->redirect('/hospital/login');
            return;
        }
        
        $username = Request::post('username');
        $password = Request::post('password');
        
        if (empty($username) || empty($password)) {
            Session::flash('Please enter username/email and password.', 'danger');
            $this->redirect('/hospital/login');
            return;
        }
        
        $userModel = new HospitalUser();
        $user = $userModel->findByUsername($username);
        
        if (!$user) {
            $user = $userModel->findByEmail($username);
        }
        
        if ($user && Auth::verifyPassword($password, $user['password'])) {
            if (isset($user['status']) && $user['status'] !== 'active') {
                Session::flash('Your account is not active. Please contact administrator.', 'danger');
                $this->redirect('/hospital/login');
                return;
            }
            
            Auth::login([
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => 'hospital',
                'hospital_id' => $user['hospital_id']
            ]);
            
            $userModel->updateLastLogin($user['id']);
            
            $activityLog = new ActivityLog();
            $activityLog->log('hospital', $user['id'], 'login', 'Hospital user logged in');
            
            $this->redirect('/hospital/dashboard');
        } else {
            $adminModel = new Admin();
            $admin = $adminModel->findBy('email', $username);

            if ($admin) {
                Session::flash('This email belongs to an Admin account. We have redirected you to the Admin Portal.', 'error');
                $this->redirect('/admin/login');
                return;
            }

            Session::flash('Invalid credentials.', 'danger');
            $this->redirect('/hospital/login');
        }
    }
    
    public function logout() {
        Auth::logout();
        $this->redirect('/hospital/login');
    }
}
