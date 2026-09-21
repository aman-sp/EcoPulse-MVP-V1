<?php

class ReportService {
    protected $monthlySubmissionModel;
    protected $electricityModel;
    protected $waterModel;
    protected $dieselModel;
    protected $medicalGasModel;
    protected $biomedicalWasteModel;

    public function __construct() {
        $this->monthlySubmissionModel = new MonthlySubmission();
        $this->electricityModel = new ElectricityLog();
        $this->waterModel = new WaterLog();
        $this->dieselModel = new DieselLog();
        $this->medicalGasModel = new MedicalGas();
        $this->biomedicalWasteModel = new BiomedicalWasteLog();
    }

    public function getHospitalMonthlyReport($hospitalId, $month) {
        $submission = $this->monthlySubmissionModel->findByHospitalAndMonth($hospitalId, $month);
        
        if (!$submission) {
            return null;
        }

        $subId = $submission['id'];

        return [
            'submission' => $submission,
            'electricity' => $this->electricityModel->findBySubmission($subId),
            'water' => $this->waterModel->findBySubmission($subId),
            'diesel' => $this->dieselModel->findBySubmission($subId),
            'medical_gas' => $this->medicalGasModel->findBySubmission($subId),
            'biomedical_waste' => $this->biomedicalWasteModel->findBySubmission($subId)
        ];
    }

    public function generateAggregateReport($month) {
        $submissions = $this->monthlySubmissionModel->findAll(['month' => $month, 'status' => 'submitted']);
        
        $report = [
            'month' => $month,
            'total_hospitals_submitted' => count($submissions),
            'data' => []
        ];

        foreach ($submissions as $sub) {
            $subId = $sub['id'];
            $report['data'][] = [
                'hospital_id' => $sub['hospital_id'],
                'electricity' => $this->electricityModel->findBySubmission($subId),
                'water' => $this->waterModel->findBySubmission($subId),
                'diesel' => $this->dieselModel->findBySubmission($subId),
                'medical_gas' => $this->medicalGasModel->findBySubmission($subId),
                'biomedical_waste' => $this->biomedicalWasteModel->findBySubmission($subId)
            ];
        }

        return $report;
    }

    public function generateReport($hospitalIdOrSubmissionId, $month = null, $type = 'sustainability') {
        $submission = null;
        $hospitalId = null;

        // If first argument is a submission ID directly (e.g. called from HospitalSubmissionController or HospitalReportController)
        if (is_numeric($hospitalIdOrSubmissionId) && !$month) {
            $submission = $this->monthlySubmissionModel->find($hospitalIdOrSubmissionId);
            if ($submission) {
                $hospitalId = (int)$submission['hospital_id'];
                $month = $submission['month'];
            }
        } elseif ($hospitalIdOrSubmissionId) {
            $hospitalId = (int)$hospitalIdOrSubmissionId;
            if ($month) {
                $submission = $this->monthlySubmissionModel->findByHospitalAndMonth($hospitalId, $month);
            }
        }

        if (!$hospitalId) {
            return false;
        }

        if (!$submission) {
            $recent = $this->monthlySubmissionModel->getRecentByHospital($hospitalId, 1);
            $submission = !empty($recent) ? $recent[0] : null;
        }

        if (!$submission) {
            $formattedMonth = $month ? date('Y-m-01', strtotime($month)) : date('Y-m-01');
            $newSubId = $this->monthlySubmissionModel->create([
                'hospital_id' => $hospitalId,
                'month' => $formattedMonth,
                'status' => 'draft',
                'current_step' => 1
            ]);
            $submission = $this->monthlySubmissionModel->find($newSubId);
        }

        $subId = (int)$submission['id'];
        $month = $submission['month'];
        $monthLabel = date('MY', strtotime($month));
        $fileName = "Sustainability_Report_{$monthLabel}_H{$hospitalId}.pdf";
        $relPath = "storage/uploads/reports/RPT-{$hospitalId}-{$monthLabel}.pdf";
        $fullPath = BASE_PATH . '/' . $relPath;

        // Ensure storage directory exists
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // Fetch all data models for this submission
        $hospital = (new Hospital())->find($hospitalId);
        $carbon = (new CarbonResult())->findBySubmission($subId) ?: [];
        $elec = $this->electricityModel->findBySubmission($subId) ?: [];
        $water = $this->waterModel->findBySubmission($subId) ?: [];
        $diesel = $this->dieselModel->findBySubmission($subId) ?: [];
        $waste = $this->biomedicalWasteModel->findBySubmission($subId) ?: [];
        $gas = $this->medicalGasModel->findBySubmission($subId) ?: [];
        $renewable = (new RenewableEnergyLog())->findBySubmission($subId) ?: [];
        $transport = (new TransportationLog())->findBySubmission($subId) ?: [];
        $recs = (new Recommendation())->findBySubmission($subId) ?: [];

        // Calculate score
        $renewPct = (float)($renewable['renewable_percentage'] ?? ($elec['renewable_percentage'] ?? 0));
        $recyclePct = ((float)($waste['general_kg'] ?? 0) > 0)
            ? ((float)($waste['recycled_kg'] ?? 0) / (float)($waste['general_kg'] ?? 1) * 100)
            : 15.0;
        $score = min(100, max(40, round(50 + ($renewPct * 1.0) + ($recyclePct * 0.8), 1)));

        // Generate full PDF report
        require_once APP_PATH . '/Helpers/PdfGenerator.php';
        $pdfGen = new PdfGenerator();
        $pdfGen->generateReport([
            'hospital' => $hospital,
            'submission' => $submission,
            'carbon' => $carbon,
            'electricity' => $elec,
            'water' => $water,
            'diesel' => $diesel,
            'biomedical_waste' => $waste,
            'medical_gas' => $gas,
            'renewable_energy' => $renewable,
            'transportation' => $transport,
            'recommendations' => $recs,
            'score' => $score
        ], $fullPath);

        $reportModel = new GeneratedReport();
        $existing = null;
        if ($subId > 0) {
            $existing = $reportModel->findBySubmission($subId);
        }

        $reportId = null;
        if ($existing) {
            $reportModel->update($existing['id'], [
                'file_path' => $relPath,
                'file_name' => $fileName,
                'report_type' => $type,
                'generated_by' => Session::get('user_role', 'system'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $reportId = $existing['id'];
        } else {
            $reportId = $reportModel->create([
                'hospital_id' => $hospitalId,
                'submission_id' => $subId > 0 ? $subId : null,
                'report_type' => $type,
                'file_path' => $relPath,
                'file_name' => $fileName,
                'generated_by' => Session::get('user_role', 'system'),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // Log activity
        $activityLog = new ActivityLog();
        $userId = Session::userId() ?? $hospitalId;
        $userRole = Session::get('user_role', 'hospital');
        $activityLog->log($userRole, $userId, 'report_generated', "Report {$fileName} generated for hospital #{$hospitalId}");

        return $reportModel->find($reportId);
    }
}

