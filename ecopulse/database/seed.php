<?php
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('STORAGE_PATH', BASE_PATH . '/storage');

require_once APP_PATH . '/Config/config.php';
require_once APP_PATH . '/Core/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Check if admin already exists to prevent duplicate seeding errors
    $check = $db->query("SELECT id FROM admins WHERE email = 'admin@ecopulse.in'")->fetch();
    if ($check) {
        echo "Database already seeded.\n";
        exit;
    }
    
    // Hash passwords
    $adminPassword = password_hash('Admin@123', PASSWORD_BCRYPT);
    $hospitalPassword = password_hash('Hospital@123', PASSWORD_BCRYPT);

    // Insert admin
    $stmt = $db->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute(['Super Admin', 'admin@ecopulse.in', $adminPassword]);
    
    // Insert demo hospital
    $stmt = $db->prepare("INSERT INTO hospitals (name, registration_number, hospital_type, ownership, email, phone, address, state, district, city, pin, beds, buildings, floors, departments, solar_installed, stp_installed, dg_sets, nabh_status, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute(['City General Hospital', 'HOS-MH-2024-001', 'Government', 'State Government', 'info@citygeneralhospital.in', '022-12345678', '123 Health Road, Andheri East', 'Maharashtra', 'Mumbai Suburban', 'Mumbai', '400069', 250, 3, 5, 12, 1, 1, 2, 'Accredited', 'active']);
    $hospitalId = $db->lastInsertId();

    // Insert hospital user
    $stmt = $db->prepare("INSERT INTO hospital_users (hospital_id, username, email, password, name, designation, status)
    VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$hospitalId, 'citygenhospital', 'demo@hospital.in', $hospitalPassword, 'Dr. Priya Sharma', 'Sustainability Officer', 'active']);

    // Insert hospital profile
    $stmt = $db->prepare("INSERT INTO hospital_profile (hospital_id, ot_count, icu_count, lab_count, rainwater_harvesting, solar_capacity_kw, stp_capacity_kld, dg_capacity_kva, total_staff, avg_daily_patients, avg_daily_opd, avg_daily_ipd)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$hospitalId, 8, 4, 3, 1, 50.00, 100.00, 500.00, 450, 800, 600, 200]);

    // Insert emission factors aligned to India-specific official reference assumptions.
    $factors = [
        ['electricity', 'Grid Electricity (India)', 'kgCO2/kWh', 0.820000, '2024', 'CEA CO2 Baseline Database, Ministry of Power, India', '2024-01-01'],
        ['diesel', 'Diesel Combustion', 'kgCO2/liter', 2.680000, '2024', 'Indian fuel combustion defaults / IPCC reference methodology', '2024-01-01'],
        ['petrol', 'Petrol Combustion', 'kgCO2/liter', 2.310000, '2024', 'Indian fuel combustion defaults / IPCC reference methodology', '2024-01-01'],
        ['water', 'Municipal Water Supply', 'kgCO2/kL', 0.344000, '2024', 'Water Supply Carbon Factors, India', '2024-01-01'],
        ['biomedical_waste', 'Biomedical Waste Incineration', 'kgCO2/kg', 0.500000, '2024', 'CPCB Guidelines 2023', '2024-01-01'],
        ['medical_gas_oxygen', 'Medical Oxygen Production', 'kgCO2/m3', 0.520000, '2024', 'Industrial Gas Association / Indian healthcare operations standard', '2024-01-01'],
        ['medical_gas_n2o', 'Nitrous Oxide', 'kgCO2e/kg', 265.000000, '2024', 'IPCC AR5 GWP (India reporting reference)', '2024-01-01'],
        ['medical_gas_anaesthetic', 'Anaesthetic Gas (Desflurane)', 'kgCO2e/kg', 2540.000000, '2024', 'NHS / IPCC reference for inhalation anaesthetics', '2024-01-01'],
        ['refrigerant', 'HFC-134a Refrigerant', 'kgCO2e/kg', 1430.000000, '2024', 'IPCC AR5 GWP (India reporting reference)', '2024-01-01']
    ];
    $stmt = $db->prepare("INSERT INTO emission_factors (category, name, unit, factor, version, source, effective_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($factors as $factor) {
        $stmt->execute($factor);
    }

    // Insert sample submission
    $stmt = $db->prepare("INSERT INTO monthly_submissions (hospital_id, month, status, submitted_at, current_step) VALUES (?, ?, ?, NOW(), ?)");
    $stmt->execute([$hospitalId, '2024-07-01', 'submitted', 6]);
    $submissionId = $db->lastInsertId();

    // Insert logs
    $db->prepare("INSERT INTO electricity_logs (submission_id, units_consumed, bill_amount, grid_percentage, renewable_percentage) VALUES (?, ?, ?, ?, ?)")->execute([$submissionId, 45000.00, 382500.00, 85.00, 15.00]);
    $db->prepare("INSERT INTO water_logs (submission_id, municipal_kl, borewell_kl, tanker_kl, recycled_kl) VALUES (?, ?, ?, ?, ?)")->execute([$submissionId, 120.00, 80.00, 30.00, 45.00]);
    $db->prepare("INSERT INTO diesel_logs (submission_id, generator_hours, diesel_purchased, diesel_used) VALUES (?, ?, ?, ?)")->execute([$submissionId, 48.00, 500.00, 480.00]);
    $db->prepare("INSERT INTO biomedical_waste_logs (submission_id, yellow_kg, red_kg, white_kg, blue_kg, general_kg, recycled_kg, vendor_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")->execute([$submissionId, 250.00, 180.00, 120.00, 45.00, 800.00, 150.00, 'EcoWaste Solutions Pvt Ltd']);
    $db->prepare("INSERT INTO medical_gases (submission_id, oxygen_cylinders, oxygen_volume_m3, nitrous_oxide_cylinders, nitrous_oxide_volume_m3, anaesthetic_gas_kg, supplier_name) VALUES (?, ?, ?, ?, ?, ?, ?)")->execute([$submissionId, 120, 360.00, 8, 24.00, 5.00, 'Linde India Ltd']);
    $db->prepare("INSERT INTO transportation_logs (submission_id, ambulance_count, diesel_vehicles, petrol_vehicles, total_distance_km, electric_vehicles) VALUES (?, ?, ?, ?, ?, ?)")->execute([$submissionId, 5, 3, 2, 4500.00, 0]);
    $db->prepare("INSERT INTO renewable_energy_logs (submission_id, solar_generated_kwh, solar_used_kwh, battery_storage_kwh, grid_offset_kwh, renewable_percentage) VALUES (?, ?, ?, ?, ?, ?)")->execute([$submissionId, 6750.00, 6000.00, 500.00, 750.00, 15.00]);

    // Insert carbon results
    $db->prepare("INSERT INTO carbon_results (submission_id, scope1_diesel, scope1_medical_gas, scope1_refrigerant, scope1_transport, scope1_total, scope2_electricity, scope2_total, total_co2e, co2e_per_bed)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")->execute([$submissionId, 1.2864, 0.2002, 0.0000, 0.3200, 1.8066, 36.9000, 36.9000, 38.7066, 0.1548]);

    // Insert recommendations
    $db->prepare("INSERT INTO recommendations (submission_id, category, recommendation, severity) VALUES (?, ?, ?, ?)")->execute([$submissionId, 'energy', 'High electricity consumption detected. Consider energy audit and HVAC optimization.', 'high']);
    $db->prepare("INSERT INTO recommendations (submission_id, category, recommendation, severity) VALUES (?, ?, ?, ?)")->execute([$submissionId, 'renewable', 'Solar utilization is only 15%. Increase solar panel capacity to reduce grid dependency.', 'medium']);
    $db->prepare("INSERT INTO recommendations (submission_id, category, recommendation, severity) VALUES (?, ?, ?, ?)")->execute([$submissionId, 'waste', 'General waste volume is high. Improve waste segregation at source to increase recycling.', 'medium']);
    $db->prepare("INSERT INTO recommendations (submission_id, category, recommendation, severity) VALUES (?, ?, ?, ?)")->execute([$submissionId, 'water', 'Water recycling is below 20%. Install or upgrade STP for higher water reuse.', 'low']);

    // Insert report
    $db->prepare("INSERT INTO generated_reports (hospital_id, submission_id, report_type, file_path, file_name, generated_by) VALUES (?, ?, ?, ?, ?, ?)")->execute([$hospitalId, $submissionId, 'sustainability', 'storage/uploads/reports/RPT-1-202407.pdf', 'Sustainability_Report_July_2024.pdf', 'system']);

    // Insert settings
    $settings = [
        ['app_name', 'EcoPulse'],
        ['app_tagline', 'Healthcare Sustainability Management'],
        ['app_version', '1.0.0'],
        ['footer_text', '© 2024 EcoPulse. All rights reserved.'],
        ['session_timeout', '3600'],
        ['max_upload_size', '10485760'],
        ['allowed_file_types', 'pdf,png,jpg,jpeg']
    ];
    $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
    foreach ($settings as $setting) {
        $stmt->execute($setting);
    }

    // Insert activity logs
    $db->prepare("INSERT INTO activity_logs (user_type, user_id, action, description, ip_address) VALUES (?, ?, ?, ?, ?)")->execute(['hospital', $hospitalId, 'submission_created', 'Monthly submission for July 2024 created', '127.0.0.1']);
    $db->prepare("INSERT INTO activity_logs (user_type, user_id, action, description, ip_address) VALUES (?, ?, ?, ?, ?)")->execute(['hospital', $hospitalId, 'report_generated', 'Sustainability report generated for July 2024', '127.0.0.1']);

    echo "Seed data inserted successfully.\n";

} catch (Exception $e) {
    echo "Error seeding database: " . $e->getMessage() . "\n";
}
