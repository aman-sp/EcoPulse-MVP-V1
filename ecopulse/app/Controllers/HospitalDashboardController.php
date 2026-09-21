<?php

class HospitalDashboardController extends Controller {
    public function index() {
        HospitalGuard::handle();
        
        $hospitalId = Session::hospitalId();
        $dashboardService = new DashboardService();
        $dashboardData = $dashboardService->getHospitalDashboard($hospitalId);
        
        $this->view('hospital/dashboard', [
            'pageTitle' => 'Dashboard',
            'currentPage' => 'dashboard',
            'pageScript' => 'dashboard.js',
            'dashboardData' => $dashboardData,
            'hospitalName' => Session::get('user_name')
        ]);
    }
    
    public function chartData() {
        HospitalGuard::handle();
        
        if (!Request::isAjax()) {
            $this->json(['error' => 'Invalid request'], 400);
            return;
        }
        
        $hospitalId = Session::hospitalId();
        // Assume CarbonResult has a method to get trends, or we compute it
        $carbonResultModel = new CarbonResult();
        $trends = $carbonResultModel->getTrendByHospital($hospitalId);
        
        $this->json([
            'success' => true,
            'data' => $trends
        ]);
    }
}
