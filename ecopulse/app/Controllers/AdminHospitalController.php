<?php

class AdminHospitalController extends Controller {
    public function index() {
        AdminGuard::handle();
        $hospitalModel = new Hospital();
        
        $keyword = Request::get('search');
        $status = Request::get('status');
        $page = (int)(Request::get('page') ?: 1);
        
        if (!empty($keyword)) {
            $hospitals = $hospitalModel->search($keyword);
            $pagination = null;
        } elseif (!empty($status)) {
            $hospitals = $hospitalModel->findAll(['status' => $status]);
            $pagination = null;
        } else {
            $paginated = $hospitalModel->paginate($page, 10);
            $hospitals = $paginated['data'] ?? [];
            $pagination = $paginated;
        }
        
        return $this->view('admin/hospitals/index', [
            'hospitals' => $hospitals,
            'pagination' => $pagination,
            'pageTitle' => 'Hospitals',
            'currentPage' => 'hospitals'
        ]);
    }

    public function create() {
        AdminGuard::handle();
        return $this->view('admin/hospitals/create', [
            'pageTitle' => 'Create Hospital',
            'currentPage' => 'hospitals'
        ]);
    }

    public function store() {
        AdminGuard::handle();
        CsrfMiddleware::handle();
        
        $validator = new Validator();
        $rules = [
            'name' => 'required',
            'registration_number' => 'required',
            'hospital_type' => 'required',
            'ownership' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'state' => 'required',
            'district' => 'required',
            'city' => 'required',
            'pin' => 'required',
            'admin_username' => 'required',
            'admin_email' => 'required|email',
            'admin_password' => 'required'
        ];
        
        $data = Request::all();
        $errors = $validator->validate($data, $rules);
        
        if (!empty($errors)) {
            Session::flash(implode(', ', array_merge(...array_values($errors))), 'danger');
            return $this->redirect('/admin/hospitals/create');
        }
        
        $hospitalModel = new Hospital();
        $hospitalId = $hospitalModel->create([
            'name' => $data['name'],
            'registration_number' => $data['registration_number'],
            'hospital_type' => $data['hospital_type'],
            'ownership' => $data['ownership'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'state' => $data['state'],
            'district' => $data['district'],
            'city' => $data['city'],
            'pin' => $data['pin'],
            'beds' => (int)($data['beds'] ?? 0),
            'buildings' => (int)($data['buildings'] ?? 1),
            'floors' => (int)($data['floors'] ?? 1),
            'departments' => (int)($data['departments'] ?? 1),
            'solar_installed' => isset($data['solar_installed']) ? 1 : 0,
            'stp_installed' => isset($data['stp_installed']) ? 1 : 0,
            'dg_sets' => (int)($data['dg_sets'] ?? 0),
            'nabh_status' => $data['nabh_status'] ?? 'Not Accredited',
            'status' => 'active'
        ]);
        
        $hospitalUserModel = new HospitalUser();
        $hospitalUserModel->create([
            'hospital_id' => $hospitalId,
            'username' => $data['admin_username'],
            'email' => $data['admin_email'],
            'password' => password_hash($data['admin_password'], PASSWORD_BCRYPT),
            'name' => $data['admin_name'] ?? 'Hospital Admin',
            'designation' => 'Administrator',
            'status' => 'active'
        ]);
        
        $hospitalProfileModel = new HospitalProfile();
        $hospitalProfileModel->create([
            'hospital_id' => $hospitalId
        ]);
        
        $activityLog = new ActivityLog();
        $activityLog->log('admin', Auth::id(), 'create_hospital', "Created hospital {$data['name']}");
        
        Session::flash('Hospital created successfully.', 'success');
        return $this->redirect('/admin/hospitals');
    }

    public function edit($id) {
        AdminGuard::handle();
        $hospitalModel = new Hospital();
        $hospital = $hospitalModel->find($id);
        
        if (!$hospital) {
            Session::flash('Hospital not found.', 'danger');
            return $this->redirect('/admin/hospitals');
        }
        
        return $this->view('admin/hospitals/edit', [
            'hospital' => $hospital,
            'pageTitle' => 'Edit Hospital',
            'currentPage' => 'hospitals'
        ]);
    }

    public function update($id) {
        AdminGuard::handle();
        CsrfMiddleware::handle();
        
        $data = Request::all();
        $hospitalModel = new Hospital();
        
        $hospitalModel->update($id, [
            'name' => $data['name'],
            'hospital_type' => $data['hospital_type'],
            'ownership' => $data['ownership'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'state' => $data['state'],
            'district' => $data['district'],
            'city' => $data['city'],
            'pin' => $data['pin'],
            'beds' => (int)($data['beds'] ?? 0),
            'nabh_status' => $data['nabh_status'] ?? 'Not Accredited'
        ]);
        
        Session::flash('Hospital updated successfully.', 'success');
        return $this->redirect('/admin/hospitals');
    }

    public function delete($id) {
        AdminGuard::handle();
        CsrfMiddleware::handle();
        
        $hospitalModel = new Hospital();
        $hospitalModel->delete($id);
        
        Session::flash('Hospital deleted successfully.', 'success');
        return $this->redirect('/admin/hospitals');
    }

    public function suspend($id) {
        AdminGuard::handle();
        CsrfMiddleware::handle();
        
        $hospitalModel = new Hospital();
        $hospital = $hospitalModel->find($id);
        
        if ($hospital) {
            $newStatus = ($hospital['status'] === 'active') ? 'suspended' : 'active';
            $hospitalModel->update($id, ['status' => $newStatus]);
            Session::flash("Hospital status changed to {$newStatus}.", 'success');
        }
        
        return $this->redirect('/admin/hospitals');
    }

    public function resetPassword($id) {
        AdminGuard::handle();
        CsrfMiddleware::handle();
        
        $hospitalUserModel = new HospitalUser();
        $users = $hospitalUserModel->findByHospitalId($id);
        $user = $users[0] ?? null;
        
        if ($user) {
            $newPassword = 'Hospital@' . rand(100, 999);
            $hospitalUserModel->update($user['id'], [
                'password' => password_hash($newPassword, PASSWORD_BCRYPT)
            ]);
            Session::flash("Password reset successfully. New password: {$newPassword}", 'warning');
        } else {
            Session::flash('No admin user found for this hospital.', 'danger');
        }
        
        return $this->redirect('/admin/hospitals');
    }
}
