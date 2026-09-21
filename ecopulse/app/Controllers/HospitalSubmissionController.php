<?php

class HospitalSubmissionController extends Controller {
    public function index() {
        HospitalGuard::handle();
        
        $hospitalId = Session::hospitalId();
        $submissionModel = new MonthlySubmission();
        $submissions = $submissionModel->getRecentByHospital($hospitalId);
        
        $this->view('hospital/submission/index', [
            'pageTitle' => 'Monthly Submissions',
            'currentPage' => 'submission',
            'submissions' => $submissions
        ]);
    }
    
    public function create() {
        HospitalGuard::handle();
        
        $hospitalId = Session::hospitalId();
        $currentMonth = date('Y-m-01'); // Valid MySQL DATE format (YYYY-MM-DD)
        
        $submissionModel = new MonthlySubmission();
        $submission = $submissionModel->findByHospitalAndMonth($hospitalId, $currentMonth);

        // Auto-repair any 0000-00-00 month values in database
        if ($submission && ($submission['month'] === '0000-00-00' || empty($submission['month']))) {
            $submissionModel->update($submission['id'], ['month' => $currentMonth]);
            $submission['month'] = $currentMonth;
        }
        
        if ($submission && $submission['status'] === 'submitted') {
            Session::flash('Submission for this month is already completed.', 'info');
            $this->redirect('/hospital/submission');
            return;
        }
        
        if (!$submission) {
            $submissionId = $submissionModel->create([
                'hospital_id' => $hospitalId,
                'month' => $currentMonth,
                'status' => 'draft',
                'current_step' => 1
            ]);
            $submission = $submissionModel->find($submissionId);
        }
        
        // Load step data
        $submissionId = $submission['id'];
        
        $electricityModel = new ElectricityLog();
        $waterModel = new WaterLog();
        $dieselModel = new DieselLog();
        $bioWasteModel = new BiomedicalWasteLog();
        $medicalGasModel = new MedicalGas();
        $transportModel = new TransportationLog();
        $renewableModel = new RenewableEnergyLog();
        
        $stepData = [
            'electricity' => $electricityModel->findBySubmission($submissionId),
            'water' => $waterModel->findBySubmission($submissionId),
            'diesel' => $dieselModel->findBySubmission($submissionId),
            'biomedical_waste' => $bioWasteModel->findBySubmission($submissionId),
            'medical_gases' => $medicalGasModel->findBySubmission($submissionId),
            'transportation' => $transportModel->findBySubmission($submissionId),
            'renewable_energy' => $renewableModel->findBySubmission($submissionId),
        ];
        
        $this->view('hospital/submission/wizard', [
            'pageTitle' => 'Monthly Submission',
            'currentPage' => 'submission',
            'pageScript' => 'wizard.js',
            'submission' => $submission,
            'stepData' => $stepData
        ]);
    }
    
    public function saveStep($step) {
        HospitalGuard::handle();
        CsrfMiddleware::handle();
        
        $submissionId = Request::post('submission_id');
        $submissionModel = new MonthlySubmission();
        $submission = $submissionModel->find($submissionId);
        
        if (!$submission || $submission['hospital_id'] != Session::hospitalId()) {
            if (Request::isAjax()) {
                $this->json(['error' => 'Invalid submission'], 400);
            } else {
                $this->redirect('/hospital/submission');
            }
            return;
        }
        
        $fileUploader = new FileUploader();
        
        if ($step == 1) {
            // Electricity
            $elecData = [
                'units_consumed' => Request::post('elec_units_consumed'),
                'bill_amount' => Request::post('elec_bill_amount'),
                'grid_percentage' => Request::post('elec_grid_percentage'),
                'renewable_percentage' => Request::post('elec_renewable_percentage'),
            ];
            $elecFile = Request::file('elec_bill');
            if ($elecFile && $elecFile['error'] == 0) {
                $elecData['bill_file_path'] = $fileUploader->upload($elecFile, 'electricity');
            }
            (new ElectricityLog())->createOrUpdate($submissionId, $elecData);
            
            // Water
            $waterData = [
                'municipal_kl' => Request::post('water_municipal_kl'),
                'borewell_kl' => Request::post('water_borewell_kl'),
                'tanker_kl' => Request::post('water_tanker_kl'),
                'recycled_kl' => Request::post('water_recycled_kl'),
            ];
            $waterFile = Request::file('water_bill');
            if ($waterFile && $waterFile['error'] == 0) {
                $waterData['bill_file_path'] = $fileUploader->upload($waterFile, 'water');
            }
            (new WaterLog())->createOrUpdate($submissionId, $waterData);
            
            // Diesel
            $dieselData = [
                'generator_hours' => Request::post('diesel_generator_hours'),
                'diesel_purchased' => Request::post('diesel_purchased'),
                'diesel_used' => Request::post('diesel_used'),
            ];
            $dieselFile = Request::file('diesel_invoice');
            if ($dieselFile && $dieselFile['error'] == 0) {
                $dieselData['invoice_file_path'] = $fileUploader->upload($dieselFile, 'diesel');
            }
            (new DieselLog())->createOrUpdate($submissionId, $dieselData);
            
        } elseif ($step == 2) {
            $wasteData = [
                'yellow_kg' => Request::post('waste_yellow_kg'),
                'red_kg' => Request::post('waste_red_kg'),
                'white_kg' => Request::post('waste_white_kg'),
                'blue_kg' => Request::post('waste_blue_kg'),
                'general_kg' => Request::post('waste_general_kg'),
                'recycled_kg' => Request::post('waste_recycled_kg'),
                'vendor_name' => Request::post('waste_vendor_name'),
            ];
            $wasteFile = Request::file('waste_manifest');
            if ($wasteFile && $wasteFile['error'] == 0) {
                $wasteData['manifest_file_path'] = $fileUploader->upload($wasteFile, 'waste');
            }
            (new BiomedicalWasteLog())->createOrUpdate($submissionId, $wasteData);
            
        } elseif ($step == 3) {
            $gasData = [
                'oxygen_cylinders' => Request::post('gas_oxygen_cylinders'),
                'oxygen_volume_m3' => Request::post('gas_oxygen_volume_m3'),
                'nitrous_oxide_cylinders' => Request::post('gas_nitrous_oxide_cylinders'),
                'nitrous_oxide_volume_m3' => Request::post('gas_nitrous_oxide_volume_m3'),
                'anaesthetic_gas_kg' => Request::post('gas_anaesthetic_gas_kg'),
                'supplier_name' => Request::post('gas_supplier_name'),
            ];
            $gasFile = Request::file('gas_invoice');
            if ($gasFile && $gasFile['error'] == 0) {
                $gasData['invoice_file_path'] = $fileUploader->upload($gasFile, 'medical_gases'); // adjust folder as needed
            }
            (new MedicalGas())->createOrUpdate($submissionId, $gasData);
            
        } elseif ($step == 4) {
            $transData = [
                'ambulance_count' => Request::post('trans_ambulance_count'),
                'diesel_vehicles' => Request::post('trans_diesel_vehicles'),
                'petrol_vehicles' => Request::post('trans_petrol_vehicles'),
                'electric_vehicles' => Request::post('trans_electric_vehicles'),
                'total_distance_km' => Request::post('trans_total_distance_km'),
            ];
            (new TransportationLog())->createOrUpdate($submissionId, $transData);
            
        } elseif ($step == 5) {
            $renData = [
                'solar_generated_kwh' => Request::post('ren_solar_generated_kwh'),
                'solar_used_kwh' => Request::post('ren_solar_used_kwh'),
                'battery_storage_kwh' => Request::post('ren_battery_storage_kwh'),
                'grid_offset_kwh' => Request::post('ren_grid_offset_kwh'),
            ];
            (new RenewableEnergyLog())->createOrUpdate($submissionId, $renData);
        }
        
        $nextStep = $step < 6 ? $step + 1 : 6;
        if ($submission['current_step'] < $nextStep) {
            $submissionModel->update($submissionId, ['current_step' => $nextStep]);
        }
        
        if (Request::isAjax()) {
            $this->json(['success' => true, 'step' => $step]);
        } else {
            // Usually step wizard forms are managed in JS via AJAX, but if non-ajax:
            $this->redirect('/hospital/submission/create');
        }
    }
    
    public function submit($id) {
        HospitalGuard::handle();
        CsrfMiddleware::handle();
        
        if (!Request::isPost()) {
            $this->redirect('/hospital/submission');
            return;
        }
        
        $submissionModel = new MonthlySubmission();
        $submission = $submissionModel->find($id);
        
        if (!$submission || $submission['hospital_id'] != Session::hospitalId()) {
            Session::flash('Invalid submission.', 'danger');
            $this->redirect('/hospital/submission');
            return;
        }
        
        $submissionModel->update($id, [
            'status' => 'submitted',
            'submitted_at' => date('Y-m-d H:i:s'),
            'current_step' => 6
        ]);
        
        $reportService = new ReportService();
        $reportService->generateReport($id);
        
        Session::flash('Submission successful! Report generated.', 'success');
        $this->redirect('/hospital/dashboard');
    }
    
    public function viewSubmission($id) {
        HospitalGuard::handle();
        // similar to create but read-only
        $hospitalId = Session::hospitalId();
        $submissionModel = new MonthlySubmission();
        $submission = $submissionModel->find($id);
        
        if (!$submission || $submission['hospital_id'] != $hospitalId) {
            $this->redirect('/hospital/submission');
            return;
        }
        
        $submissionId = $submission['id'];
        
        $stepData = [
            'electricity' => (new ElectricityLog())->findBySubmission($submissionId),
            'water' => (new WaterLog())->findBySubmission($submissionId),
            'diesel' => (new DieselLog())->findBySubmission($submissionId),
            'biomedical_waste' => (new BiomedicalWasteLog())->findBySubmission($submissionId),
            'medical_gases' => (new MedicalGas())->findBySubmission($submissionId),
            'transportation' => (new TransportationLog())->findBySubmission($submissionId),
            'renewable_energy' => (new RenewableEnergyLog())->findBySubmission($submissionId),
        ];
        
        // Use a generic view or reuse wizard in readonly mode
        $this->view('hospital/submission/wizard', [
            'pageTitle' => 'View Submission',
            'currentPage' => 'submission',
            'pageScript' => 'wizard.js',
            'submission' => $submission,
            'stepData' => $stepData,
            'readonly' => true
        ]);
    }
}
