<?php
/**
 * EcoPulse – Rich Random Test Data Seeder
 * ----------------------------------------
 * Seeds 5 hospitals across India with 12 months of realistic
 * monthly submissions, all associated logs, carbon calculations,
 * recommendations, notifications, and activity logs.
 *
 * Run from the ecopulse/ directory:
 *   php database/seed_test_data.php
 *
 * Safe to re-run: skips hospitals that already exist by reg number.
 */

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH',  BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('STORAGE_PATH', BASE_PATH . '/storage');

require_once APP_PATH . '/Config/config.php';
require_once APP_PATH . '/Core/Database.php';

// ─── Emission factors (matches production seed) ──────────────────────────────
const EF_ELECTRICITY  = 0.820;
const EF_DIESEL       = 2.680;
const EF_WATER        = 0.344;
const EF_BIO_WASTE    = 0.500;
const EF_OXYGEN       = 0.520;
const EF_N2O          = 265.0;
const EF_ANAESTHETIC  = 2540.0;

// ─── Helpers ─────────────────────────────────────────────────────────────────
function rf(float $min, float $max, int $decimals = 2): float {
    return round($min + mt_rand() / mt_getrandmax() * ($max - $min), $decimals);
}
function ri(int $min, int $max): int { return mt_rand($min, $max); }

// ─── Hospital master data ─────────────────────────────────────────────────────
$hospitals = [
    [
        'name' => 'Apollo Multi-Specialty Hospital', 'registration_number' => 'HOS-DL-2024-002',
        'hospital_type' => 'Private', 'ownership' => 'Apollo Hospitals Group',
        'email' => 'sustainability@apollodelhi.in', 'phone' => '011-44556677',
        'address' => '21 Sarita Vihar, Mathura Road', 'state' => 'Delhi',
        'district' => 'South Delhi', 'city' => 'New Delhi', 'pin' => '110076',
        'beds' => 450, 'buildings' => 4, 'floors' => 8, 'departments' => 22,
        'solar_installed' => 1, 'stp_installed' => 1, 'dg_sets' => 4,
        'nabh_status' => 'Accredited',
        'profile' => [12, 6, 5, 1, 120.0, 200.0, 1000.0, 750, 1200, 900, 300],
        'user_name' => 'Dr. Anjali Mehta', 'designation' => 'Sustainability Officer',
    ],
    [
        'name' => 'Fortis Hospitals Bengaluru', 'registration_number' => 'HOS-KA-2024-003',
        'hospital_type' => 'Corporate', 'ownership' => 'Fortis Healthcare Ltd',
        'email' => 'green@fortisbengaluru.in', 'phone' => '080-66214444',
        'address' => '154/9 Bannerghatta Road, Billekahalli', 'state' => 'Karnataka',
        'district' => 'Bengaluru Urban', 'city' => 'Bengaluru', 'pin' => '560076',
        'beds' => 300, 'buildings' => 3, 'floors' => 6, 'departments' => 18,
        'solar_installed' => 1, 'stp_installed' => 0, 'dg_sets' => 3,
        'nabh_status' => 'Accredited',
        'profile' => [10, 5, 4, 1, 90.0, 0.0, 800.0, 600, 950, 700, 250],
        'user_name' => 'Dr. Rajesh Kumar', 'designation' => 'Green Coordinator',
    ],
    [
        'name' => 'AIIMS Jodhpur', 'registration_number' => 'HOS-RJ-2024-004',
        'hospital_type' => 'Government', 'ownership' => 'Ministry of Health & Family Welfare',
        'email' => 'sustainability@aiimsjodhupr.edu.in', 'phone' => '0291-2740741',
        'address' => 'Basni Phase-2, Jodhpur', 'state' => 'Rajasthan',
        'district' => 'Jodhpur', 'city' => 'Jodhpur', 'pin' => '342005',
        'beds' => 500, 'buildings' => 6, 'floors' => 7, 'departments' => 30,
        'solar_installed' => 1, 'stp_installed' => 1, 'dg_sets' => 6,
        'nabh_status' => 'In Process',
        'profile' => [15, 8, 6, 1, 200.0, 300.0, 1500.0, 1000, 1800, 1400, 400],
        'user_name' => 'Dr. Sunita Rao', 'designation' => 'Energy Manager',
    ],
    [
        'name' => 'Narayana Health City', 'registration_number' => 'HOS-KA-2024-005',
        'hospital_type' => 'Trust', 'ownership' => 'Narayana Hrudayalaya Trust',
        'email' => 'eco@narayanahealth.org', 'phone' => '080-71222222',
        'address' => '258/A Bommasandra, Hosur Road', 'state' => 'Karnataka',
        'district' => 'Bengaluru Rural', 'city' => 'Bengaluru', 'pin' => '560099',
        'beds' => 1000, 'buildings' => 8, 'floors' => 10, 'departments' => 40,
        'solar_installed' => 1, 'stp_installed' => 1, 'dg_sets' => 8,
        'nabh_status' => 'Accredited',
        'profile' => [20, 12, 8, 1, 350.0, 500.0, 2500.0, 2000, 3500, 2800, 700],
        'user_name' => 'Dr. Vikram Singh', 'designation' => 'Eco Officer',
    ],
    [
        'name' => 'Command Hospital Southern Command', 'registration_number' => 'HOS-TN-2024-006',
        'hospital_type' => 'Military', 'ownership' => 'Ministry of Defence',
        'email' => 'admin@chsc.mil.in', 'phone' => '044-25671234',
        'address' => 'Poonamallee High Road, Perambur', 'state' => 'Tamil Nadu',
        'district' => 'Chennai', 'city' => 'Chennai', 'pin' => '600011',
        'beds' => 350, 'buildings' => 5, 'floors' => 5, 'departments' => 20,
        'solar_installed' => 0, 'stp_installed' => 1, 'dg_sets' => 5,
        'nabh_status' => 'Accredited',
        'profile' => [8, 4, 3, 0, 0.0, 150.0, 900.0, 500, 700, 500, 200],
        'user_name' => 'Dr. Priya Nair', 'designation' => 'HSE Manager',
    ],
];

$months   = [];
for ($m = 1; $m <= 12; $m++) $months[] = sprintf('2024-%02d-01', $m);

$vendors       = ['EcoWaste Solutions Pvt Ltd','Clean Earth Biomedical','BioSafe Waste Mgmt','GreenMed Disposal','SafeMed Logistics'];
$gasSuppliers  = ['Linde India Ltd','INOX Air Products','Air Liquide India','Bhuruka Gases Ltd','National Oxygen Co'];

$recTemplates = [
    ['energy',    'high',   'Electricity consumption exceeds benchmark. Conduct energy audit and optimize HVAC scheduling.'],
    ['energy',    'medium', 'Replace conventional lighting with LED across all wards to save 15-20% on electricity bills.'],
    ['renewable', 'high',   'Solar utilization below 20%. Expand panel capacity or add battery storage to cut grid dependency.'],
    ['renewable', 'medium', 'Enable net metering to offset grid costs with excess solar energy exported to the grid.'],
    ['waste',     'high',   'Biomedical waste volume is elevated. Strengthen waste segregation training for clinical staff.'],
    ['waste',     'medium', 'General waste recycling rate is below 25%. Partner with a certified recycler for dry waste.'],
    ['water',     'medium', 'Install rainwater harvesting; borewell dependency may stress local groundwater reserves.'],
    ['water',     'low',    'Route STP recycled water to flushing and landscaping to reduce municipal water draw.'],
    ['transport', 'medium', 'Fleet fuel consumption is high. Consider converting petrol vehicles to CNG or adding EVs.'],
    ['diesel',    'high',   'Generator runtime exceeds 40 hrs/month. Review DG scheduling and perform preventive maintenance.'],
    ['gases',     'medium', 'Nitrous oxide consumption is elevated. Audit anaesthetic gas lines for leaks and wastage.'],
];

$notifTitles = ['Submission Approved','Report Ready','Carbon Alert','Recommendation Available'];
$notifMsgs   = [
    'Your monthly data submission for %s has been approved by the admin.',
    'Your sustainability report for %s is ready to download.',
    'Carbon footprint for %s exceeded the benchmark. Please review your emissions.',
    'New improvement recommendations are available for your %s submission.',
];

$hospitalPassword = password_hash('Hospital@123', PASSWORD_BCRYPT);

// ─── Main seeder ─────────────────────────────────────────────────────────────
try {
    $db = Database::getInstance()->getConnection();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $totH = $totS = $totL = 0;

    foreach ($hospitals as $idx => $h) {
        $chk = $db->prepare("SELECT id FROM hospitals WHERE registration_number = ?");
        $chk->execute([$h['registration_number']]);
        if ($chk->fetch()) {
            echo "SKIP  '{$h['name']}' already seeded.\n";
            continue;
        }

        // Hospital
        $db->prepare("INSERT INTO hospitals
            (name,registration_number,hospital_type,ownership,email,phone,address,state,district,city,pin,
             beds,buildings,floors,departments,solar_installed,stp_installed,dg_sets,nabh_status,status)
            VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'active')")
           ->execute([$h['name'],$h['registration_number'],$h['hospital_type'],$h['ownership'],
                      $h['email'],$h['phone'],$h['address'],$h['state'],$h['district'],$h['city'],$h['pin'],
                      $h['beds'],$h['buildings'],$h['floors'],$h['departments'],
                      $h['solar_installed'],$h['stp_installed'],$h['dg_sets'],$h['nabh_status']]);
        $hId = (int)$db->lastInsertId();
        $totH++;

        // Hospital user
        $uname = strtolower(preg_replace('/[^a-z0-9]/i','',explode(' ',$h['name'])[0])).$hId;
        $city2 = strtolower(preg_replace('/[^a-z]/i','',$h['city']));
        $db->prepare("INSERT INTO hospital_users(hospital_id,username,email,password,name,phone,designation,status)VALUES(?,?,?,?,?,?,?,'active')")
           ->execute([$hId,$uname,"user@{$city2}.hospital.in",$hospitalPassword,$h['user_name'],'98'.ri(10000000,99999999),$h['designation']]);

        // Hospital profile
        [$otc,$icuc,$labc,$rwh,$solar,$stp,$dgkva,$staff,$adp,$opd,$ipd] = $h['profile'];
        $db->prepare("INSERT INTO hospital_profile(hospital_id,ot_count,icu_count,lab_count,rainwater_harvesting,solar_capacity_kw,stp_capacity_kld,dg_capacity_kva,total_staff,avg_daily_patients,avg_daily_opd,avg_daily_ipd)VALUES(?,?,?,?,?,?,?,?,?,?,?,?)")
           ->execute([$hId,$otc,$icuc,$labc,$rwh,$solar,$stp,$dgkva,$staff,$adp,$opd,$ipd]);

        echo "OK    [{$hId}] {$h['name']}\n";

        foreach ($months as $mi => $month) {
            $beds = $h['beds'];
            $smul = ($mi >= 3 && $mi <= 7) ? 1.18 : 1.0;

            // Electricity
            $units   = round(ri((int)($beds*150),(int)($beds*220)) * $smul, 2);
            $bill    = round($units * rf(7.5,9.5), 2);
            $solPct  = $h['solar_installed'] ? rf(10.0,35.0) : 0.0;
            $grdPct  = round(100 - $solPct, 2);

            // Water
            $mun_kl  = round($beds * rf(0.45,0.75) * $smul, 2);
            $bor_kl  = round($beds * rf(0.20,0.40), 2);
            $tnk_kl  = round($beds * rf(0.05,0.15), 2);
            $rec_kl  = $h['stp_installed'] ? round(($mun_kl+$bor_kl)*rf(0.10,0.30),2) : 0.0;

            // Diesel
            $gHours  = ri(30,120);
            $dPurch  = round($gHours * rf(8,15), 2);
            $dUsed   = round($dPurch  * rf(0.88,0.98), 2);

            // Bio waste
            $yelkg = round($beds*rf(0.8,1.5),2); $redkg = round($beds*rf(0.5,1.0),2);
            $whtkg = round($beds*rf(0.3,0.7),2); $blukg = round($beds*rf(0.1,0.3),2);
            $genkg = round($beds*rf(2.0,4.0),2); $reckg = round($genkg*rf(0.10,0.35),2);
            $vend  = $vendors[$idx % count($vendors)];

            // Medical gases
            $o2cyl = ri((int)($beds*0.3),(int)($beds*0.7));
            $o2vol = round($o2cyl * rf(2.8,3.5), 2);
            $n2cyl = ri(2,15); $n2vol = round($n2cyl * rf(2.8,3.2),2);
            $ankkg = round(rf(1.5,8.0),2);
            $gasSup = $gasSuppliers[$idx % count($gasSuppliers)];

            // Transport
            $amb = ri(3,12); $dv = ri(2,8); $pv = ri(1,5); $ev = ri(0,3);
            $tdist = round(($amb+$dv+$pv)*rf(180,420),2);

            // Renewable
            $solKW = (float)$solar;
            if ($solKW > 0) {
                $ph    = rf(4.5,6.5) * $smul;
                $solG  = round($solKW * $ph * 30, 2);
                $solU  = round($solG  * rf(0.80,0.95), 2);
                $batS  = round($solG  * rf(0.05,0.15), 2);
                $gOff  = round($solG  - $solU, 2);
                $rPct  = round($solU  / max($units,1)*100, 2);
            } else {
                $solG = $solU = $batS = $gOff = $rPct = 0.0;
            }

            // Carbon
            $s1d = round($dUsed * EF_DIESEL / 1000, 4);
            $s1g = round(($o2vol*EF_OXYGEN + $n2vol*EF_N2O/1000*1.96 + $ankkg*EF_ANAESTHETIC/1000)/1000, 4);
            $s1r = round(rf(0,0.5), 4);
            $dkm = $tdist * ($dv / max($dv+$pv,1));
            $pkm = $tdist - $dkm;
            $s1t = round($dkm*0.00027 + $pkm*0.000192, 4);
            $s1  = round($s1d+$s1g+$s1r+$s1t, 4);
            $s2  = round($units * ($grdPct/100) * EF_ELECTRICITY / 1000, 4);
            $tot = round($s1+$s2, 4);
            $cpb = round($tot / max($beds,1), 4);

            // Submission status
            $status = ($mi <= 9) ? 'approved' : (($mi == 10) ? 'submitted' : 'draft');
            $subAt  = ($status !== 'draft') ? date('Y-m-d H:i:s', strtotime($month.' +'.ri(5,25).' days')) : null;

            $db->prepare("INSERT INTO monthly_submissions(hospital_id,month,status,submitted_at,current_step)VALUES(?,?,?,?,6)")
               ->execute([$hId,$month,$status,$subAt]);
            $sId = (int)$db->lastInsertId();
            $totS++;

            // Logs
            $db->prepare("INSERT INTO electricity_logs(submission_id,units_consumed,bill_amount,grid_percentage,renewable_percentage)VALUES(?,?,?,?,?)")->execute([$sId,$units,$bill,$grdPct,$solPct]);
            $db->prepare("INSERT INTO water_logs(submission_id,municipal_kl,borewell_kl,tanker_kl,recycled_kl)VALUES(?,?,?,?,?)")->execute([$sId,$mun_kl,$bor_kl,$tnk_kl,$rec_kl]);
            $db->prepare("INSERT INTO diesel_logs(submission_id,generator_hours,diesel_purchased,diesel_used)VALUES(?,?,?,?)")->execute([$sId,$gHours,$dPurch,$dUsed]);
            $db->prepare("INSERT INTO biomedical_waste_logs(submission_id,yellow_kg,red_kg,white_kg,blue_kg,general_kg,recycled_kg,vendor_name)VALUES(?,?,?,?,?,?,?,?)")->execute([$sId,$yelkg,$redkg,$whtkg,$blukg,$genkg,$reckg,$vend]);
            $db->prepare("INSERT INTO medical_gases(submission_id,oxygen_cylinders,oxygen_volume_m3,nitrous_oxide_cylinders,nitrous_oxide_volume_m3,anaesthetic_gas_kg,supplier_name)VALUES(?,?,?,?,?,?,?)")->execute([$sId,$o2cyl,$o2vol,$n2cyl,$n2vol,$ankkg,$gasSup]);
            $db->prepare("INSERT INTO transportation_logs(submission_id,ambulance_count,diesel_vehicles,petrol_vehicles,total_distance_km,electric_vehicles)VALUES(?,?,?,?,?,?)")->execute([$sId,$amb,$dv,$pv,$tdist,$ev]);
            $db->prepare("INSERT INTO renewable_energy_logs(submission_id,solar_generated_kwh,solar_used_kwh,battery_storage_kwh,grid_offset_kwh,renewable_percentage)VALUES(?,?,?,?,?,?)")->execute([$sId,$solG,$solU,$batS,$gOff,$rPct]);
            $db->prepare("INSERT INTO carbon_results(submission_id,scope1_diesel,scope1_medical_gas,scope1_refrigerant,scope1_transport,scope1_total,scope2_electricity,scope2_total,total_co2e,co2e_per_bed)VALUES(?,?,?,?,?,?,?,?,?,?)")->execute([$sId,$s1d,$s1g,$s1r,$s1t,$s1,$s2,$s2,$tot,$cpb]);
            $totL += 8;

            // Recommendations
            if ($status !== 'draft') {
                $picks = (array)array_rand($recTemplates, ri(2,4));
                foreach ($picks as $pi) {
                    [$rc,$rv,$rt] = $recTemplates[$pi];
                    $db->prepare("INSERT INTO recommendations(submission_id,category,recommendation,severity)VALUES(?,?,?,?)")->execute([$sId,$rc,$rt,$rv]);
                }
            }

            // Notifications + reports for approved
            if ($status === 'approved') {
                $ml  = date('F Y', strtotime($month));
                $ni  = ri(0,3);
                $db->prepare("INSERT INTO notifications(user_type,user_id,title,message,is_read)VALUES('hospital',?,?,?,?)")
                   ->execute([$hId,$notifTitles[$ni],sprintf($notifMsgs[$ni],$ml),ri(0,1)]);

                $rl = date('MY', strtotime($month));
                $db->prepare("INSERT INTO generated_reports(hospital_id,submission_id,report_type,file_path,file_name,generated_by)VALUES(?,?,'sustainability',?,?,'system')")
                   ->execute([$hId,$sId,"storage/uploads/reports/RPT-{$hId}-{$rl}.pdf","Sustainability_Report_{$rl}_H{$hId}.pdf"]);
            }

            // Activity log
            $db->prepare("INSERT INTO activity_logs(user_type,user_id,action,description,ip_address)VALUES('hospital',?,?,?,?)")
               ->execute([$hId,'submission_created',"Submission for {$month} created",'192.168.'.ri(1,254).'.'.ri(1,254)]);
        }

        echo "      12 months seeded for [{$hId}].\n";
    }

    echo "\n";
    echo "===================================================\n";
    echo "  EcoPulse Test Data Seeding Complete\n";
    echo "---------------------------------------------------\n";
    echo "  Hospitals   : {$totH}\n";
    echo "  Submissions : {$totS}\n";
    echo "  Log records : {$totL}\n";
    echo "===================================================\n";
    echo "  Admin   : admin@ecopulse.in  / Admin@123\n";
    echo "  Hospital: Hospital@123  (all new hospitals)\n\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "  at " . $e->getFile() . ":" . $e->getLine() . "\n";
}
