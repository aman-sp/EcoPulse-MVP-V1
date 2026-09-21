<?php


class AdminReportController extends Controller {
    public function index() {
        AdminGuard::handle();
        
        $reportModel = new GeneratedReport();
        $reports = $reportModel->query("
            SELECT r.*, h.name as hospital_name 
            FROM generated_reports r 
            LEFT JOIN hospitals h ON r.hospital_id = h.id 
            ORDER BY r.created_at DESC
        ")->fetchAll();
        
        $hospitalModel = new Hospital();
        $hospitals = $hospitalModel->findAll();
        
        return $this->view('admin/reports/index', [
            'reports' => $reports,
            'hospitals' => $hospitals,
            'pageTitle' => 'Reports',
            'currentPage' => 'reports'
        ]);
    }

    public function generate() {
        AdminGuard::handle();
        CsrfMiddleware::handle();
        
        $hospitalId = Request::post('hospital_id');
        $month = Request::post('month');
        $type = Request::post('report_type');
        
        // Assuming ReportService handles the generation and saving
        $reportService = new ReportService();
        $success = $reportService->generateReport($hospitalId, $month, $type);
        
        if ($success) {
            Session::flash('Report generated successfully.', 'success');
        } else {
            Session::flash('Failed to generate report.', 'error');
        }
        
        return $this->redirect('/admin/reports');
    }

    public function download($id) {
        AdminGuard::handle();
        
        $reportModel = new GeneratedReport();
        $report = $reportModel->find($id);
        
        if ($report) {
            $candidates = [
                BASE_PATH . '/' . $report['file_path'],
                STORAGE_PATH . '/' . $report['file_path'],
                STORAGE_PATH . '/uploads/reports/' . basename($report['file_path']),
                BASE_PATH . '/storage/uploads/reports/' . basename($report['file_path'])
            ];
            
            foreach ($candidates as $filePath) {
                if (file_exists($filePath)) {
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment; filename="' . basename($report['file_name'] ?: $filePath) . '"');
                    readfile($filePath);
                    exit;
                }
            }
        }
        
        Session::flash('Report file not found.', 'error');
        return $this->redirect('/admin/reports');
    }
}
