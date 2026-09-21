<?php
define("BASE_PATH", dirname(__DIR__));
define("APP_PATH",  BASE_PATH . "/app");
define("PUBLIC_PATH", BASE_PATH . "/public");
define("STORAGE_PATH", BASE_PATH . "/storage");
require_once APP_PATH . "/Config/config.php";
require_once APP_PATH . "/Core/Database.php";
$db = Database::getInstance()->getConnection();
$tables = ["hospitals","monthly_submissions","electricity_logs","water_logs","diesel_logs",
           "biomedical_waste_logs","medical_gases","transportation_logs","renewable_energy_logs",
           "carbon_results","recommendations","notifications","generated_reports","activity_logs"];
echo str_pad("Table", 28) . "Rows\n";
echo str_repeat("-", 36) . "\n";
foreach ($tables as $t) {
    $c = $db->query("SELECT COUNT(*) FROM $t")->fetchColumn();
    echo str_pad($t, 28) . $c . "\n";
}
echo "\nHospitals:\n";
foreach ($db->query("SELECT id, name, beds, hospital_type FROM hospitals ORDER BY id") as $row) {
    echo "  [{$row['id']}] {$row['name']} ({$row['beds']} beds, {$row['hospital_type']})\n";
}
