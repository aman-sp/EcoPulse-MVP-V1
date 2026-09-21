<?php


class AdminUserController extends Controller {
    public function index() {
        AdminGuard::handle();
        
        $userModel = new HospitalUser();
        // Assuming a join or relationship method exists in model
        $users = $userModel->query("
            SELECT u.*, h.name as hospital_name 
            FROM hospital_users u 
            LEFT JOIN hospitals h ON u.hospital_id = h.id
        ")->fetchAll();
        
        return $this->view('admin/users/index', [
            'users' => $users,
            'pageTitle' => 'Hospital Users',
            'currentPage' => 'users'
        ]);
    }

    public function store() {
        AdminGuard::handle();
        CsrfMiddleware::handle();
        // Creation logic
        Session::flash('User created successfully.', 'success');
        return $this->redirect('/admin/users');
    }

    public function update($id) {
        AdminGuard::handle();
        CsrfMiddleware::handle();
        // Update logic
        Session::flash('User updated successfully.', 'success');
        return $this->redirect('/admin/users');
    }

    public function delete($id) {
        AdminGuard::handle();
        CsrfMiddleware::handle();
        
        $userModel = new HospitalUser();
        $userModel->delete($id);
        
        Session::flash('User deleted successfully.', 'success');
        return $this->redirect('/admin/users');
    }
}
