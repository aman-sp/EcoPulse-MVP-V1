<?php

class HospitalProfileController extends Controller {
    public function index() {
        HospitalGuard::handle();
        
        $hospitalId = Session::hospitalId();
        
        $hospitalModel = new Hospital();
        $profileModel = new HospitalProfile();
        
        $hospital = $hospitalModel->find($hospitalId);
        $profile = $profileModel->findByHospitalId($hospitalId) ?? [];
        
        $this->view('hospital/profile', [
            'pageTitle' => 'Hospital Profile',
            'currentPage' => 'profile',
            'hospital' => $hospital,
            'profile' => $profile
        ]);
    }
    
    public function update() {
        HospitalGuard::handle();
        CsrfMiddleware::handle();
        
        if (!Request::isPost()) {
            $this->redirect('/hospital/profile');
            return;
        }
        
        $hospitalId = Session::hospitalId();
        
        $profileData = [
            'type' => Request::post('type'),
            'ownership' => Request::post('ownership'),
            'phone' => Request::post('phone'),
            'address' => Request::post('address'),
            'state' => Request::post('state'),
            'district' => Request::post('district'),
            'city' => Request::post('city'),
            'pin' => Request::post('pin'),
            
            'beds' => Request::post('beds'),
            'buildings' => Request::post('buildings'),
            'floors' => Request::post('floors'),
            'departments' => Request::post('departments'),
            
            'ot_count' => Request::post('ot_count'),
            'icu_count' => Request::post('icu_count'),
            'lab_count' => Request::post('lab_count'),
            
            'solar_installed' => Request::post('solar_installed') ? 1 : 0,
            'solar_capacity_kw' => Request::post('solar_capacity_kw'),
            'rainwater_harvesting' => Request::post('rainwater_harvesting') ? 1 : 0,
            'stp_installed' => Request::post('stp_installed') ? 1 : 0,
            'stp_capacity_kld' => Request::post('stp_capacity_kld'),
            'dg_sets' => Request::post('dg_sets'),
            'dg_capacity_kva' => Request::post('dg_capacity_kva'),
            
            'total_staff' => Request::post('total_staff'),
            'avg_daily_patients' => Request::post('avg_daily_patients'),
            'avg_daily_opd' => Request::post('avg_daily_opd'),
            'avg_daily_ipd' => Request::post('avg_daily_ipd'),
        ];
        
        $profileModel = new HospitalProfile();
        $profileModel->createOrUpdate($hospitalId, $profileData);
        
        $hospitalModel = new Hospital();
        $hospitalUpdate = [];
        
        if (Request::post('email')) {
            $hospitalUpdate['email'] = Request::post('email');
        }
        
        if (!empty($hospitalUpdate)) {
            $hospitalModel->update($hospitalId, $hospitalUpdate);
        }
        
        Session::flash('Profile updated successfully.', 'success');
        $this->redirect('/hospital/profile');
    }
}
