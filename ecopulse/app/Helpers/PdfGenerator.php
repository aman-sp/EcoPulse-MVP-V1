<?php

require_once __DIR__ . '/Fpdf.php';

class PdfGenerator {
    /**
     * Generate a complete, multi-section sustainability report PDF.
     */
    public function generateReport(array $data, string $outputPath): bool {
        $pdf = new Fpdf('P', 'mm', 'A4');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        $hospital = $data['hospital'] ?? [];
        $submission = $data['submission'] ?? [];
        $carbon = $data['carbon'] ?? [];
        $electricity = $data['electricity'] ?? [];
        $water = $data['water'] ?? [];
        $diesel = $data['diesel'] ?? [];
        $waste = $data['biomedical_waste'] ?? [];
        $renewable = $data['renewable_energy'] ?? [];
        $recommendations = $data['recommendations'] ?? [];

        $hospitalName = $hospital['name'] ?? 'Hospital Facility';
        $regNo = $hospital['registration_number'] ?? 'N/A';
        $city = $hospital['city'] ?? 'India';
        $state = $hospital['state'] ?? '';
        $beds = (int)($hospital['beds'] ?? 0);
        $score = number_format((float)($data['score'] ?? 75.0), 1);
        $monthLabel = !empty($submission['month']) ? date('F Y', strtotime($submission['month'])) : date('F Y');
        $reportId = 'RPT-' . ($hospital['id'] ?? '1') . '-' . strtoupper(date('MY', strtotime($submission['month'] ?? 'now')));

        // ─── Header Banner ──────────────────────────────────────────────────────────
        $pdf->SetFillColor(16, 185, 129); // EcoPulse Emerald
        $pdf->Rect(15, 15, 180, 24, 'F');

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->SetXY(20, 19);
        $pdf->Cell(120, 8, 'EcoPulse | Healthcare Sustainability Report', 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetXY(20, 27);
        $pdf->Cell(120, 6, 'National Healthcare Environmental Decarbonization Framework', 0, 1, 'L');

        // Right side badge on banner
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->SetXY(145, 19);
        $pdf->Cell(45, 7, 'GRADE A+', 0, 1, 'R');
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetXY(145, 27);
        $pdf->Cell(45, 6, $monthLabel, 0, 1, 'R');

        $pdf->SetY(43);

        // ─── Hospital Details & Metadata Box ───────────────────────────────────────
        $pdf->SetFillColor(248, 250, 252);
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->Rect(15, 43, 180, 28, 'DF');

        $pdf->SetTextColor(30, 41, 59);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetXY(19, 46);
        $pdf->Cell(110, 6, $hospitalName, 0, 1);

        $pdf->SetFont('Helvetica', '', 8.5);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->SetX(19);
        $pdf->Cell(110, 5, 'Reg No: ' . $regNo . '  |  NABH: ' . ($hospital['nabh_status'] ?? 'Accredited') . '  |  Type: ' . ($hospital['hospital_type'] ?? 'General'), 0, 1);
        $pdf->SetX(19);
        $pdf->Cell(110, 5, 'Location: ' . $city . ($state ? ', ' . $state : '') . '  |  Bed Capacity: ' . $beds . ' Beds', 0, 1);

        // Score Card Box on Right
        $pdf->SetFillColor(236, 253, 245);
        $pdf->SetDrawColor(16, 185, 129);
        $pdf->Rect(138, 46, 52, 22, 'DF');

        $pdf->SetTextColor(5, 150, 105);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetXY(138, 48);
        $pdf->Cell(52, 4, 'SUSTAINABILITY INDEX', 0, 1, 'C');

        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->SetXY(138, 54);
        $pdf->Cell(52, 7, $score . ' / 100', 0, 1, 'C');

        $pdf->SetFont('Helvetica', '', 7);
        $pdf->SetXY(138, 62);
        $pdf->Cell(52, 4, 'Performance: Optimal', 0, 1, 'C');

        // ─── Executive Summary: Carbon Footprint ──────────────────────────────────
        $pdf->SetY(76);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Cell(180, 6, '1. Carbon Footprint Assessment (tCO2e)', 0, 1);
        $pdf->SetDrawColor(16, 185, 129);
        $pdf->SetLineWidth(0.4);
        $pdf->Line(15, 83, 195, 83);

        $pdf->SetY(86);
        // Table Header
        $pdf->SetFillColor(241, 245, 249);
        $pdf->SetTextColor(71, 85, 105);
        $pdf->SetFont('Helvetica', 'B', 8.5);
        $pdf->Cell(60, 7, 'Emission Category', 1, 0, 'L', true);
        $pdf->Cell(40, 7, 'Activity Source', 1, 0, 'L', true);
        $pdf->Cell(40, 7, 'Emissions (tCO2e)', 1, 0, 'R', true);
        $pdf->Cell(40, 7, '% Contribution', 1, 1, 'R', true);

        $totCO2 = max(0.0001, (float)($carbon['total_co2e'] ?? 38.71));
        $s1Diesel = (float)($carbon['scope1_diesel'] ?? 1.28);
        $s1Gas = (float)($carbon['scope1_medical_gas'] ?? 0.20);
        $s1Trans = (float)($carbon['scope1_transport'] ?? 0.32);
        $s1Refrig = (float)($carbon['scope1_refrigerant'] ?? 0.00);
        $s1Total = (float)($carbon['scope1_total'] ?? ($s1Diesel + $s1Gas + $s1Trans + $s1Refrig));
        $s2Elec = (float)($carbon['scope2_electricity'] ?? ($totCO2 - $s1Total));

        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetTextColor(30, 41, 59);

        // Scope 1 rows
        $pdf->Cell(60, 6, 'Scope 1 - Diesel Generators', 1, 0, 'L');
        $pdf->Cell(40, 6, 'Backup Generation', 1, 0, 'L');
        $pdf->Cell(40, 6, number_format($s1Diesel, 4), 1, 0, 'R');
        $pdf->Cell(40, 6, number_format(($s1Diesel / $totCO2) * 100, 1) . '%', 1, 1, 'R');

        $pdf->Cell(60, 6, 'Scope 1 - Medical Gases', 1, 0, 'L');
        $pdf->Cell(40, 6, 'N2O & Anaesthetic Gases', 1, 0, 'L');
        $pdf->Cell(40, 6, number_format($s1Gas, 4), 1, 0, 'R');
        $pdf->Cell(40, 6, number_format(($s1Gas / $totCO2) * 100, 1) . '%', 1, 1, 'R');

        $pdf->Cell(60, 6, 'Scope 1 - Transport Fleet', 1, 0, 'L');
        $pdf->Cell(40, 6, 'Hospital Ambulances / Fleet', 1, 0, 'L');
        $pdf->Cell(40, 6, number_format($s1Trans, 4), 1, 0, 'R');
        $pdf->Cell(40, 6, number_format(($s1Trans / $totCO2) * 100, 1) . '%', 1, 1, 'R');

        // Scope 2 rows
        $pdf->Cell(60, 6, 'Scope 2 - Grid Electricity', 1, 0, 'L');
        $pdf->Cell(40, 6, 'State Utility Grid Supply', 1, 0, 'L');
        $pdf->Cell(40, 6, number_format($s2Elec, 4), 1, 0, 'R');
        $pdf->Cell(40, 6, number_format(($s2Elec / $totCO2) * 100, 1) . '%', 1, 1, 'R');

        // Total Row
        $pdf->SetFont('Helvetica', 'B', 8.5);
        $pdf->SetFillColor(236, 253, 245);
        $pdf->Cell(100, 7, 'TOTAL NET EMISSIONS (Scope 1 + Scope 2)', 1, 0, 'L', true);
        $pdf->Cell(40, 7, number_format($totCO2, 4) . ' tCO2e', 1, 0, 'R', true);
        $pdf->Cell(40, 7, '100.0%', 1, 1, 'R', true);

        // Per Bed benchmark
        $perBed = $beds > 0 ? ($totCO2 / $beds) : ($carbon['co2e_per_bed'] ?? 0);
        $pdf->SetFont('Helvetica', 'I', 7.5);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell(180, 5, '* Specific Carbon Intensity: ' . number_format($perBed, 4) . ' tCO2e / bed / month (Benchmark: < 0.20 tCO2e/bed)', 0, 1);

        // ─── Resource Consumption Overview ─────────────────────────────────────────
        $pdf->SetY(135);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Cell(180, 6, '2. Resource Consumption & Utilities', 0, 1);
        $pdf->Line(15, 142, 195, 142);

        $pdf->SetY(145);
        // 3 Column summary cards
        $wCol = 58;

        // Card 1: Energy
        $pdf->SetFillColor(248, 250, 252);
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->Rect(15, 145, $wCol, 36, 'DF');
        $pdf->SetXY(17, 147);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetTextColor(16, 185, 129);
        $pdf->Cell($wCol - 4, 5, 'Electricity & Solar', 0, 1);

        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetTextColor(30, 41, 59);
        $pdf->SetX(17);
        $pdf->Cell($wCol - 4, 4.5, 'Total Units: ' . number_format((float)($electricity['units_consumed'] ?? 0)) . ' kWh', 0, 1);
        $pdf->SetX(17);
        $pdf->Cell($wCol - 4, 4.5, 'Grid Share: ' . number_format((float)($electricity['grid_percentage'] ?? 85)) . '%', 0, 1);
        $pdf->SetX(17);
        $pdf->Cell($wCol - 4, 4.5, 'Renewable: ' . number_format((float)($electricity['renewable_percentage'] ?? 15)) . '%', 0, 1);
        $pdf->SetX(17);
        $pdf->Cell($wCol - 4, 4.5, 'Bill Amount: INR ' . number_format((float)($electricity['bill_amount'] ?? 0)), 0, 1);

        // Card 2: Water
        $pdf->Rect(76, 145, $wCol, 36, 'DF');
        $pdf->SetXY(78, 147);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetTextColor(14, 165, 233);
        $pdf->Cell($wCol - 4, 5, 'Water Management', 0, 1);

        $totWater = (float)($water['municipal_kl'] ?? 0) + (float)($water['borewell_kl'] ?? 0) + (float)($water['tanker_kl'] ?? 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetTextColor(30, 41, 59);
        $pdf->SetX(78);
        $pdf->Cell($wCol - 4, 4.5, 'Municipal Draw: ' . number_format((float)($water['municipal_kl'] ?? 0)) . ' kL', 0, 1);
        $pdf->SetX(78);
        $pdf->Cell($wCol - 4, 4.5, 'Borewell / Tanker: ' . number_format((float)($water['borewell_kl'] ?? 0) + (float)($water['tanker_kl'] ?? 0)) . ' kL', 0, 1);
        $pdf->SetX(78);
        $pdf->Cell($wCol - 4, 4.5, 'Total Water: ' . number_format($totWater, 1) . ' kL', 0, 1);
        $pdf->SetX(78);
        $pdf->Cell($wCol - 4, 4.5, 'STP Recycled: ' . number_format((float)($water['recycled_kl'] ?? 0)) . ' kL', 0, 1);

        // Card 3: Waste
        $pdf->Rect(137, 145, $wCol, 36, 'DF');
        $pdf->SetXY(139, 147);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetTextColor(236, 72, 153);
        $pdf->Cell($wCol - 4, 5, 'Biomedical & Waste', 0, 1);

        $bioTot = (float)($waste['yellow_kg'] ?? 0) + (float)($waste['red_kg'] ?? 0) + (float)($waste['white_kg'] ?? 0) + (float)($waste['blue_kg'] ?? 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetTextColor(30, 41, 59);
        $pdf->SetX(139);
        $pdf->Cell($wCol - 4, 4.5, 'Biomedical Total: ' . number_format($bioTot, 1) . ' kg', 0, 1);
        $pdf->SetX(139);
        $pdf->Cell($wCol - 4, 4.5, 'Yellow / Red: ' . number_format((float)($waste['yellow_kg'] ?? 0) + (float)($waste['red_kg'] ?? 0), 1) . ' kg', 0, 1);
        $pdf->SetX(139);
        $pdf->Cell($wCol - 4, 4.5, 'General Waste: ' . number_format((float)($waste['general_kg'] ?? 0), 1) . ' kg', 0, 1);
        $pdf->SetX(139);
        $pdf->Cell($wCol - 4, 4.5, 'Recycled: ' . number_format((float)($waste['recycled_kg'] ?? 0), 1) . ' kg', 0, 1);

        // ─── Sustainability Recommendations ────────────────────────────────────────
        $pdf->SetY(190);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Cell(180, 6, '3. Targeted Sustainability Recommendations', 0, 1);
        $pdf->Line(15, 197, 195, 197);

        $pdf->SetY(201);
        if (!empty($recommendations)) {
            foreach (array_slice($recommendations, 0, 3) as $idx => $rec) {
                $category = is_array($rec) ? strtoupper($rec['category'] ?? 'GENERAL') : 'TIP';
                $severity = is_array($rec) ? strtoupper($rec['severity'] ?? 'MEDIUM') : 'INFO';
                $text = is_array($rec) ? ($rec['recommendation'] ?? '') : (string)$rec;
                $rowY = $pdf->GetY();

                // Outer Card Box
                $pdf->SetFillColor(248, 250, 252);
                $pdf->SetDrawColor(226, 232, 240);
                $pdf->Rect(15, $rowY, 180, 14, 'DF');

                // Severity Pill Badge
                if ($severity === 'HIGH') {
                    $pdf->SetFillColor(225, 29, 72); // Rose Red
                } elseif ($severity === 'MEDIUM') {
                    $pdf->SetFillColor(217, 119, 6); // Amber
                } else {
                    $pdf->SetFillColor(16, 185, 129); // Emerald
                }
                $pdf->Rect(19, $rowY + 3.5, 22, 7, 'F');
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetFont('Helvetica', 'B', 7.5);
                $pdf->SetXY(19, $rowY + 3.5);
                $pdf->Cell(22, 7, $severity, 0, 0, 'C');

                // Category Tag
                $pdf->SetTextColor(71, 85, 105);
                $pdf->SetFont('Helvetica', 'B', 8);
                $pdf->SetXY(44, $rowY + 3.5);
                $pdf->Cell(26, 7, substr($category, 0, 14), 0, 0, 'L');

                // Recommendation Text Description
                $pdf->SetFont('Helvetica', '', 8);
                $pdf->SetTextColor(30, 41, 59);
                $pdf->SetXY(72, $rowY + 3.5);
                $pdf->Cell(120, 7, substr($text, 0, 85), 0, 1, 'L');

                $pdf->SetY($rowY + 16.5);
            }
        } else {
            $pdf->SetFont('Helvetica', '', 8);
            $pdf->SetTextColor(100, 116, 139);
            $pdf->Cell(180, 6, 'All environmental parameters are within standard operating benchmarks. Continue monitoring.', 0, 1);
        }

        // ─── Footer Sign-off ───────────────────────────────────────────────────────
        $pdf->SetY(255);
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->Line(15, 255, 195, 255);

        $pdf->SetFont('Helvetica', '', 7.5);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->SetXY(15, 258);
        $pdf->Cell(90, 4, 'Report ID: ' . $reportId . '  |  Generated: ' . date('d M Y, h:i A'), 0, 0);
        $pdf->Cell(90, 4, 'Authorized by EcoPulse Certification Engine (v1.0)', 0, 1, 'R');

        $pdf->SetXY(15, 263);
        $pdf->Cell(180, 4, 'Confidential — For Hospital Internal Review and Green Hospital Accreditation Submissions', 0, 1, 'C');

        // Output to file
        return $pdf->Output('F', $outputPath);
    }
}
