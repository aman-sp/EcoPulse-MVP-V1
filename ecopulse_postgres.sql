-- EcoPulse PostgreSQL Schema (Converted for Aiven / Supabase)
-- Drop existing tables in reverse dependency order
DROP TABLE IF EXISTS settings CASCADE;
DROP TABLE IF EXISTS notifications CASCADE;
DROP TABLE IF EXISTS activity_logs CASCADE;
DROP TABLE IF EXISTS generated_reports CASCADE;
DROP TABLE IF EXISTS recommendations CASCADE;
DROP TABLE IF EXISTS carbon_results CASCADE;
DROP TABLE IF EXISTS renewable_energy_logs CASCADE;
DROP TABLE IF EXISTS transportation_logs CASCADE;
DROP TABLE IF EXISTS medical_gases CASCADE;
DROP TABLE IF EXISTS biomedical_waste_logs CASCADE;
DROP TABLE IF EXISTS diesel_logs CASCADE;
DROP TABLE IF EXISTS water_logs CASCADE;
DROP TABLE IF EXISTS electricity_logs CASCADE;
DROP TABLE IF EXISTS monthly_submissions CASCADE;
DROP TABLE IF EXISTS hospital_profile CASCADE;
DROP TABLE IF EXISTS hospital_users CASCADE;
DROP TABLE IF EXISTS emission_factors CASCADE;
DROP TABLE IF EXISTS hospitals CASCADE;
DROP TABLE IF EXISTS admins CASCADE;

-- 1. Admins
CREATE TABLE admins (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  avatar VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Hospitals
CREATE TABLE hospitals (
  id SERIAL PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  registration_number VARCHAR(100) NOT NULL UNIQUE,
  hospital_type VARCHAR(50) NOT NULL,
  ownership VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  address TEXT NOT NULL,
  state VARCHAR(100) NOT NULL,
  district VARCHAR(100) NOT NULL,
  city VARCHAR(100) NOT NULL,
  pin VARCHAR(10) NOT NULL,
  beds INT DEFAULT 0,
  buildings INT DEFAULT 1,
  floors INT DEFAULT 1,
  departments INT DEFAULT 1,
  solar_installed SMALLINT DEFAULT 0,
  stp_installed SMALLINT DEFAULT 0,
  dg_sets INT DEFAULT 0,
  nabh_status VARCHAR(50) DEFAULT 'Not Accredited',
  status VARCHAR(50) DEFAULT 'active',
  logo VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Hospital Users
CREATE TABLE hospital_users (
  id SERIAL PRIMARY KEY,
  hospital_id INT NOT NULL REFERENCES hospitals(id) ON DELETE CASCADE,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(150) NOT NULL,
  password VARCHAR(255) NOT NULL,
  name VARCHAR(150) NOT NULL,
  phone VARCHAR(20) NULL,
  designation VARCHAR(100) NULL,
  status VARCHAR(50) DEFAULT 'active',
  last_login TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Hospital Profile
CREATE TABLE hospital_profile (
  id SERIAL PRIMARY KEY,
  hospital_id INT NOT NULL UNIQUE REFERENCES hospitals(id) ON DELETE CASCADE,
  ot_count INT DEFAULT 0,
  icu_count INT DEFAULT 0,
  lab_count INT DEFAULT 0,
  rainwater_harvesting SMALLINT DEFAULT 0,
  solar_capacity_kw DECIMAL(10,2) DEFAULT 0,
  stp_capacity_kld DECIMAL(10,2) DEFAULT 0,
  dg_capacity_kva DECIMAL(10,2) DEFAULT 0,
  total_staff INT DEFAULT 0,
  avg_daily_patients INT DEFAULT 0,
  avg_daily_opd INT DEFAULT 0,
  avg_daily_ipd INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. Monthly Submissions
CREATE TABLE monthly_submissions (
  id SERIAL PRIMARY KEY,
  hospital_id INT NOT NULL REFERENCES hospitals(id) ON DELETE CASCADE,
  month DATE NOT NULL,
  status VARCHAR(50) DEFAULT 'draft',
  submitted_at TIMESTAMP NULL,
  current_step INT DEFAULT 1,
  notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT unique_hospital_month UNIQUE (hospital_id, month)
);

-- 6. Electricity Logs
CREATE TABLE electricity_logs (
  id SERIAL PRIMARY KEY,
  submission_id INT NOT NULL REFERENCES monthly_submissions(id) ON DELETE CASCADE,
  units_consumed DECIMAL(12,2) DEFAULT 0,
  bill_amount DECIMAL(12,2) DEFAULT 0,
  grid_percentage DECIMAL(5,2) DEFAULT 100,
  renewable_percentage DECIMAL(5,2) DEFAULT 0,
  bill_file_path VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 7. Water Logs
CREATE TABLE water_logs (
  id SERIAL PRIMARY KEY,
  submission_id INT NOT NULL REFERENCES monthly_submissions(id) ON DELETE CASCADE,
  municipal_kl DECIMAL(10,2) DEFAULT 0,
  borewell_kl DECIMAL(10,2) DEFAULT 0,
  tanker_kl DECIMAL(10,2) DEFAULT 0,
  recycled_kl DECIMAL(10,2) DEFAULT 0,
  bill_upload VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 8. Diesel Logs
CREATE TABLE diesel_logs (
  id SERIAL PRIMARY KEY,
  submission_id INT NOT NULL REFERENCES monthly_submissions(id) ON DELETE CASCADE,
  generator_hours DECIMAL(10,2) DEFAULT 0,
  diesel_purchased DECIMAL(10,2) DEFAULT 0,
  diesel_used DECIMAL(10,2) DEFAULT 0,
  invoice_file_path VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 9. Biomedical Waste Logs
CREATE TABLE biomedical_waste_logs (
  id SERIAL PRIMARY KEY,
  submission_id INT NOT NULL REFERENCES monthly_submissions(id) ON DELETE CASCADE,
  yellow_kg DECIMAL(10,2) DEFAULT 0,
  red_kg DECIMAL(10,2) DEFAULT 0,
  white_kg DECIMAL(10,2) DEFAULT 0,
  blue_kg DECIMAL(10,2) DEFAULT 0,
  general_kg DECIMAL(10,2) DEFAULT 0,
  recycled_kg DECIMAL(10,2) DEFAULT 0,
  vendor_name VARCHAR(200) NULL,
  manifest_file_path VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 10. Medical Gases
CREATE TABLE medical_gases (
  id SERIAL PRIMARY KEY,
  submission_id INT NOT NULL REFERENCES monthly_submissions(id) ON DELETE CASCADE,
  oxygen_cylinders INT DEFAULT 0,
  oxygen_volume_m3 DECIMAL(10,2) DEFAULT 0,
  nitrous_oxide_cylinders INT DEFAULT 0,
  nitrous_oxide_volume_m3 DECIMAL(10,2) DEFAULT 0,
  anaesthetic_gas_kg DECIMAL(10,2) DEFAULT 0,
  supplier_name VARCHAR(200) NULL,
  invoice_file_path VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 11. Transportation Logs
CREATE TABLE transportation_logs (
  id SERIAL PRIMARY KEY,
  submission_id INT NOT NULL REFERENCES monthly_submissions(id) ON DELETE CASCADE,
  ambulance_count INT DEFAULT 0,
  diesel_vehicles INT DEFAULT 0,
  petrol_vehicles INT DEFAULT 0,
  total_distance_km DECIMAL(10,2) DEFAULT 0,
  electric_vehicles INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 12. Renewable Energy Logs
CREATE TABLE renewable_energy_logs (
  id SERIAL PRIMARY KEY,
  submission_id INT NOT NULL REFERENCES monthly_submissions(id) ON DELETE CASCADE,
  solar_generated_kwh DECIMAL(12,2) DEFAULT 0,
  solar_used_kwh DECIMAL(12,2) DEFAULT 0,
  battery_storage_kwh DECIMAL(12,2) DEFAULT 0,
  grid_offset_kwh DECIMAL(12,2) DEFAULT 0,
  renewable_percentage DECIMAL(5,2) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 13. Emission Factors
CREATE TABLE emission_factors (
  id SERIAL PRIMARY KEY,
  category VARCHAR(100) NOT NULL,
  name VARCHAR(200) NOT NULL,
  unit VARCHAR(50) NOT NULL,
  factor DECIMAL(15,6) NOT NULL,
  version VARCHAR(20) DEFAULT '1.0',
  source VARCHAR(255) NULL,
  effective_date DATE NOT NULL,
  is_active SMALLINT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_category_active ON emission_factors(category, is_active);

-- 14. Carbon Results
CREATE TABLE carbon_results (
  id SERIAL PRIMARY KEY,
  submission_id INT NOT NULL REFERENCES monthly_submissions(id) ON DELETE CASCADE,
  scope1_diesel DECIMAL(12,4) DEFAULT 0,
  scope1_medical_gas DECIMAL(12,4) DEFAULT 0,
  scope1_refrigerant DECIMAL(12,4) DEFAULT 0,
  scope1_transport DECIMAL(12,4) DEFAULT 0,
  scope1_total DECIMAL(12,4) DEFAULT 0,
  scope2_electricity DECIMAL(12,4) DEFAULT 0,
  scope2_total DECIMAL(12,4) DEFAULT 0,
  total_co2e DECIMAL(12,4) DEFAULT 0,
  co2e_per_bed DECIMAL(12,4) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 15. Recommendations
CREATE TABLE recommendations (
  id SERIAL PRIMARY KEY,
  submission_id INT NOT NULL REFERENCES monthly_submissions(id) ON DELETE CASCADE,
  category VARCHAR(100) NOT NULL,
  recommendation TEXT NOT NULL,
  severity VARCHAR(50) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 16. Generated Reports
CREATE TABLE generated_reports (
  id SERIAL PRIMARY KEY,
  hospital_id INT NOT NULL REFERENCES hospitals(id) ON DELETE CASCADE,
  submission_id INT NOT NULL REFERENCES monthly_submissions(id) ON DELETE CASCADE,
  report_type VARCHAR(50) DEFAULT 'sustainability',
  file_path VARCHAR(255) NOT NULL,
  file_name VARCHAR(255) NOT NULL,
  generated_by VARCHAR(50) DEFAULT 'system',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 17. Activity Logs
CREATE TABLE activity_logs (
  id SERIAL PRIMARY KEY,
  user_type VARCHAR(50) NOT NULL,
  user_id INT NOT NULL,
  action VARCHAR(255) NOT NULL,
  description TEXT NULL,
  ip_address VARCHAR(45) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_user_type_id ON activity_logs(user_type, user_id);

-- 18. Notifications
CREATE TABLE notifications (
  id SERIAL PRIMARY KEY,
  user_type VARCHAR(50) NOT NULL,
  user_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  is_read SMALLINT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_user_read ON notifications(user_type, user_id, is_read);

-- 19. Settings
CREATE TABLE settings (
  id SERIAL PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed Default Admin
INSERT INTO admins (id, name, email, password) VALUES
(1, 'Super Admin', 'admin@ecopulse.in', '$2y$10$E9kARS.91EomRE.IXNgX4e8ET.dPOX/loyOTcrLyU7YfR6ZYbPgKy')
ON CONFLICT (id) DO NOTHING;

-- Seed Default Hospital
INSERT INTO hospitals (id, name, registration_number, hospital_type, ownership, email, phone, address, state, district, city, pin, beds, buildings, floors, departments, solar_installed, stp_installed, dg_sets, nabh_status, status) VALUES
(1, 'City General Hospital', 'HOS-MH-2024-001', 'Government', 'State Government', 'info@citygeneralhospital.in', '022-12345678', '123 Health Road, Andheri East', 'Maharashtra', 'Mumbai Suburban', 'Mumbai', '400069', 250, 3, 5, 12, 1, 1, 2, 'Accredited', 'active')
ON CONFLICT (id) DO NOTHING;

-- Seed Hospital User
INSERT INTO hospital_users (id, hospital_id, username, email, password, name, phone, designation, status) VALUES
(1, 1, 'citygenhospital', 'demo@hospital.in', '$2y$10$tIPzdfeG7MXfjskVm0QESOhrxRDiuAZfIH/Wwk8Shhbcf3SdiTnAS', 'Dr. Priya Sharma', '9876543210', 'Sustainability Officer', 'active')
ON CONFLICT (id) DO NOTHING;

-- Seed Hospital Profile
INSERT INTO hospital_profile (id, hospital_id, ot_count, icu_count, lab_count, rainwater_harvesting, solar_capacity_kw, stp_capacity_kld, dg_capacity_kva, total_staff, avg_daily_patients, avg_daily_opd, avg_daily_ipd) VALUES
(1, 1, 8, 4, 3, 1, 50.00, 100.00, 500.00, 450, 800, 600, 200)
ON CONFLICT (id) DO NOTHING;

-- Seed Emission Factors
INSERT INTO emission_factors (category, name, unit, factor, version, source, effective_date) VALUES
('electricity', 'Grid Electricity (India)', 'kgCO2/kWh', 0.820000, '2024', 'CEA CO2 Baseline Database, Ministry of Power, India', '2024-01-01'),
('diesel', 'Diesel Combustion', 'kgCO2/liter', 2.680000, '2024', 'Indian fuel combustion defaults / IPCC reference methodology', '2024-01-01'),
('petrol', 'Petrol Combustion', 'kgCO2/liter', 2.310000, '2024', 'Indian fuel combustion defaults / IPCC reference methodology', '2024-01-01'),
('water', 'Municipal Water Supply', 'kgCO2/kL', 0.344000, '2024', 'Water Supply Carbon Factors, India', '2024-01-01'),
('biomedical_waste', 'Biomedical Waste Incineration', 'kgCO2/kg', 0.500000, '2024', 'CPCB Guidelines 2023', '2024-01-01'),
('medical_gas_oxygen', 'Medical Oxygen Production', 'kgCO2/m3', 0.520000, '2024', 'Industrial Gas Association / Indian healthcare operations standard', '2024-01-01'),
('medical_gas_n2o', 'Nitrous Oxide', 'kgCO2e/kg', 265.000000, '2024', 'IPCC AR5 GWP (India reporting reference)', '2024-01-01'),
('medical_gas_anaesthetic', 'Anaesthetic Gas (Desflurane)', 'kgCO2e/kg', 2540.000000, '2024', 'NHS / IPCC reference for inhalation anaesthetics', '2024-01-01'),
('refrigerant', 'HFC-134a Refrigerant', 'kgCO2e/kg', 1430.000000, '2024', 'IPCC AR5 GWP (India reporting reference)', '2024-01-01');

-- Seed Settings
INSERT INTO settings (setting_key, setting_value) VALUES
('app_name', 'EcoPulse'),
('app_tagline', 'Healthcare Sustainability Management'),
('app_version', '1.0.0'),
('footer_text', '© 2024 EcoPulse. All rights reserved.'),
('session_timeout', '3600'),
('max_upload_size', '10485760'),
('allowed_file_types', 'pdf,png,jpg,jpeg')
ON CONFLICT (setting_key) DO NOTHING;

-- Reset sequences
SELECT setval('admins_id_seq', (SELECT MAX(id) FROM admins));
SELECT setval('hospitals_id_seq', (SELECT MAX(id) FROM hospitals));
SELECT setval('hospital_users_id_seq', (SELECT MAX(id) FROM hospital_users));
SELECT setval('hospital_profile_id_seq', (SELECT MAX(id) FROM hospital_profile));
SELECT setval('emission_factors_id_seq', (SELECT MAX(id) FROM emission_factors));
