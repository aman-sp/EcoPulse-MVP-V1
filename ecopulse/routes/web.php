<?php

// Admin Auth
Router::get('/admin/login', 'AdminAuthController', 'showLogin');
Router::post('/admin/login', 'AdminAuthController', 'login');
Router::get('/admin/logout', 'AdminAuthController', 'logout');

// Admin Dashboard
Router::get('/admin/dashboard', 'AdminDashboardController', 'index');

// Admin Hospitals
Router::get('/admin/hospitals', 'AdminHospitalController', 'index');
Router::get('/admin/hospitals/create', 'AdminHospitalController', 'create');
Router::post('/admin/hospitals/store', 'AdminHospitalController', 'store');
Router::get('/admin/hospitals/edit/{id}', 'AdminHospitalController', 'edit');
Router::post('/admin/hospitals/update/{id}', 'AdminHospitalController', 'update');
Router::post('/admin/hospitals/delete/{id}', 'AdminHospitalController', 'delete');
Router::post('/admin/hospitals/suspend/{id}', 'AdminHospitalController', 'suspend');
Router::post('/admin/hospitals/reset-password/{id}', 'AdminHospitalController', 'resetPassword');
Router::get('/admin/hospitals/export', 'AdminHospitalController', 'export');

// Admin Users
Router::get('/admin/users', 'AdminUserController', 'index');
Router::post('/admin/users/store', 'AdminUserController', 'store');
Router::post('/admin/users/update/{id}', 'AdminUserController', 'update');
Router::post('/admin/users/delete/{id}', 'AdminUserController', 'delete');

// Admin Emission Factors
Router::get('/admin/emission-factors', 'AdminEmissionFactorController', 'index');
Router::post('/admin/emission-factors/store', 'AdminEmissionFactorController', 'store');
Router::post('/admin/emission-factors/update/{id}', 'AdminEmissionFactorController', 'update');
Router::post('/admin/emission-factors/delete/{id}', 'AdminEmissionFactorController', 'delete');

// Admin Reports
Router::get('/admin/reports', 'AdminReportController', 'index');
Router::post('/admin/reports/generate', 'AdminReportController', 'generate');
Router::get('/admin/reports/download/{id}', 'AdminReportController', 'download');

// Admin Settings
Router::get('/admin/settings', 'AdminSettingsController', 'index');
Router::post('/admin/settings/update', 'AdminSettingsController', 'update');
Router::get('/admin/profile', 'AdminSettingsController', 'profile');
Router::post('/admin/profile/update', 'AdminSettingsController', 'updateProfile');

// Hospital Auth
Router::get('/hospital/login', 'HospitalAuthController', 'showLogin');
Router::post('/hospital/login', 'HospitalAuthController', 'login');
Router::get('/hospital/logout', 'HospitalAuthController', 'logout');

// Hospital Dashboard
Router::get('/hospital/dashboard', 'HospitalDashboardController', 'index');
Router::get('/hospital/dashboard/chart-data', 'HospitalDashboardController', 'chartData');

// Hospital Profile
Router::get('/hospital/profile', 'HospitalProfileController', 'index');
Router::post('/hospital/profile/update', 'HospitalProfileController', 'update');

// Hospital Submission
Router::get('/hospital/submission', 'HospitalSubmissionController', 'index');
Router::get('/hospital/submission/create', 'HospitalSubmissionController', 'create');
Router::post('/hospital/submission/save-step/{step}', 'HospitalSubmissionController', 'saveStep');
Router::post('/hospital/submission/submit/{id}', 'HospitalSubmissionController', 'submit');
Router::get('/hospital/submission/view/{id}', 'HospitalSubmissionController', 'viewSubmission');

// Hospital Reports
Router::get('/hospital/reports', 'HospitalReportController', 'index');
Router::get('/hospital/reports/download/{id}', 'HospitalReportController', 'download');
Router::post('/hospital/reports/generate/{id}', 'HospitalReportController', 'generate');

// Default redirect
Router::get('/', 'AdminAuthController', 'showLogin');
