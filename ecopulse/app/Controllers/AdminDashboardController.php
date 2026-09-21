<?php


class AdminDashboardController extends Controller {
    public function index() {
        AdminGuard::handle();
        
        // Assuming DashboardService is static or injected
        $dashboardService = new DashboardService();
        $data = $dashboardService->getAdminDashboard();
        
        $data['pageTitle'] = 'Dashboard';
        $data['currentPage'] = 'dashboard';
        $data['pageScript'] = 'dashboard.js';
        
        return $this->view('admin/dashboard', $data);
    }
}
