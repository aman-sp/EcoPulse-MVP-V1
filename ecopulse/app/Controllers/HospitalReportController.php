<?php

class HospitalReportController extends Controller {
    public function index() {
        HospitalGuard::handle();
        
        $hospitalId = Session::hospitalId();
        $reportModel = new GeneratedReport();
        $reports = $reportModel->findByHospital($hospitalId) ?? [];
        
        $this->view('hospital/reports/index', [
            'pageTitle' => 'Reports',
            'currentPage' => 'reports',
            'reports' => $reports
        ]);
    }
    
    public function download($id) {
        HospitalGuard::handle();
        
        $hospitalId = Session::hospitalId();
        $reportModel = new GeneratedReport();
        $report = $reportModel->find($id);
        
        if (!$report || $report['hospital_id'] != $hospitalId) {
            Session::flash('Report not found or unauthorized.', 'danger');
            $this->redirect('/hospital/reports');
            return;
        }
        
        $candidates = [
            BASE_PATH . '/' . $report['file_path'],
            STORAGE_PATH . '/' . $report['file_path'],
            STORAGE_PATH . '/uploads/reports/' . basename($report['file_path']),
            BASE_PATH . '/storage/uploads/reports/' . basename($report['file_path']),
            STORAGE_PATH . '/app/' . $report['file_path']
        ];
        
        foreach ($candidates as $filePath) {
            if (file_exists($filePath)) {
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . basename($report['file_name'] ?: $filePath) . '"');
                header('Content-Length: ' . filesize($filePath));
                readfile($filePath);
                exit;
            }
        }
        
        Session::flash('File not found on server.', 'danger');
        $this->redirect('/hospital/reports');
    }
    
    public function generate($id) {
        HospitalGuard::handle();
        CsrfMiddleware::handle();
        
        if (!Request::isPost()) {
            $this->redirect('/hospital/reports');
            return;
        }
        
        $hospitalId = Session::hospitalId();
        $submissionModel = new MonthlySubmission();
        $submission = $submissionModel->find($id);
        
        if (!$submission || $submission['hospital_id'] != $hospitalId || $submission['status'] !== 'submitted') {
            Session::flash('Invalid submission for report generation.', 'danger');
            $this->redirect('/hospital/reports');
            return;
        }
        
        $reportService = new ReportService();
        $report = $reportService->generateReport($id);
        
        if ($report) {
            Session::flash('Report re-generated successfully.', 'success');
        } else {
            Session::flash('Failed to generate report.', 'danger');
        }
        
        $this->redirect('/hospital/reports');
    }
}
