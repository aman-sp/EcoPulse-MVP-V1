<?php
$isReadonly = isset($readonly) && $readonly;
$roAttr = $isReadonly ? 'disabled readonly' : '';
?>
<?php
$monthRaw = $submission['month'] ?? date('Y-m-d');
$monthTime = strtotime($monthRaw);
if ($monthTime === false) {
    $monthTime = strtotime($monthRaw . '-01') ?: time();
}
?>
<div class="page-header mb-4 flex flex--between align-center">
    <div>
        <h1 class="page-header__title"><?= $isReadonly ? 'View Submission' : 'Monthly Submission' ?></h1>
        <p class="text-muted">Data for <?= htmlspecialchars(date('F Y', $monthTime)) ?></p>
    </div>
</div>

<div class="wizard">
    <!-- Progress Bar -->
    <div class="wizard__progress flex mb-4" style="gap: 1rem;">
        <?php
        $steps = [
            1 => 'Utilities',
            2 => 'Waste',
            3 => 'Gases',
            4 => 'Transport',
            5 => 'Renewable',
            6 => 'Review'
        ];
        foreach ($steps as $num => $label):
            $isActive = $submission['current_step'] == $num;
            $isCompleted = $submission['current_step'] > $num || $isReadonly;
            $class = 'wizard__step';
            if ($isActive) $class .= ' wizard__step--active';
            if ($isCompleted) $class .= ' wizard__step--completed';
        ?>
        <div class="<?= $class ?>" data-step="<?= $num ?>" style="flex:1; padding: 1rem; text-align: center; background: #f8fafc; border-bottom: 3px solid <?= $isActive ? '#0d9488' : ($isCompleted ? '#10b981' : '#e2e8f0') ?>; cursor: pointer;">
            <span class="wizard__step-number" style="font-weight: bold; color: <?= $isActive || $isCompleted ? '#0f172a' : '#94a3b8' ?>;"><?= $num ?></span>
            <span class="wizard__step-label" style="display: block; font-size: 0.875rem; color: <?= $isActive || $isCompleted ? '#475569' : '#94a3b8' ?>;"><?= $label ?></span>
        </div>
        <?php endforeach; ?>
    </div>
    
    <form id="wizardForm" method="POST" action="<?= BASE_URL ?>/hospital/submission/submit/<?= $submission['id'] ?>" enctype="multipart/form-data">
        <?= Session::csrfField() ?>
        <input type="hidden" name="submission_id" value="<?= $submission['id'] ?>">
        
        <!-- Step 1: Utilities -->
        <div class="wizard__content" id="step-1" <?= $submission['current_step'] == 1 ? '' : 'style="display:none;"' ?>>
            <h3 class="mb-3">1. Utilities</h3>
            
            <div class="card mb-4">
                <div class="card__header flex align-center" style="gap: 0.5rem;"><i data-lucide="zap" class="text-warning"></i> Electricity</div>
                <div class="card__body grid grid--2" style="gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Units Consumed (kWh)</label>
                        <input type="number" step="0.01" name="elec_units_consumed" class="form-control" value="<?= htmlspecialchars($stepData['electricity']['units_consumed'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bill Amount (₹)</label>
                        <input type="number" step="0.01" name="elec_bill_amount" class="form-control" value="<?= htmlspecialchars($stepData['electricity']['bill_amount'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Grid Percentage (%)</label>
                        <input type="number" step="0.01" name="elec_grid_percentage" class="form-control" value="<?= htmlspecialchars($stepData['electricity']['grid_percentage'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Renewable Percentage (%)</label>
                        <input type="number" step="0.01" name="elec_renewable_percentage" class="form-control" value="<?= htmlspecialchars($stepData['electricity']['renewable_percentage'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <?php if (!$isReadonly): ?>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Upload Electricity Bill</label>
                        <input type="file" name="elec_bill" class="form-control">
                        <?php if (!empty($stepData['electricity']['bill_file_path'])): ?>
                            <small class="text-muted">Current file: <?= htmlspecialchars(basename($stepData['electricity']['bill_file_path'])) ?></small>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card__header flex align-center" style="gap: 0.5rem;"><i data-lucide="droplets" class="text-info"></i> Water</div>
                <div class="card__body grid grid--2" style="gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Municipal (KL)</label>
                        <input type="number" step="0.01" name="water_municipal_kl" class="form-control" value="<?= htmlspecialchars($stepData['water']['municipal_kl'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Borewell (KL)</label>
                        <input type="number" step="0.01" name="water_borewell_kl" class="form-control" value="<?= htmlspecialchars($stepData['water']['borewell_kl'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanker (KL)</label>
                        <input type="number" step="0.01" name="water_tanker_kl" class="form-control" value="<?= htmlspecialchars($stepData['water']['tanker_kl'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Recycled (KL)</label>
                        <input type="number" step="0.01" name="water_recycled_kl" class="form-control" value="<?= htmlspecialchars($stepData['water']['recycled_kl'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <?php if (!$isReadonly): ?>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Upload Water Bill</label>
                        <input type="file" name="water_bill" class="form-control">
                        <?php if (!empty($stepData['water']['bill_file_path'])): ?>
                            <small class="text-muted">Current file: <?= htmlspecialchars(basename($stepData['water']['bill_file_path'])) ?></small>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card__header flex align-center" style="gap: 0.5rem;"><i data-lucide="fuel" class="text-muted"></i> Diesel</div>
                <div class="card__body grid grid--2" style="gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Generator Hours</label>
                        <input type="number" step="0.01" name="diesel_generator_hours" class="form-control" value="<?= htmlspecialchars($stepData['diesel']['generator_hours'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Diesel Purchased (Liters)</label>
                        <input type="number" step="0.01" name="diesel_purchased" class="form-control" value="<?= htmlspecialchars($stepData['diesel']['diesel_purchased'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Diesel Used (Liters)</label>
                        <input type="number" step="0.01" name="diesel_used" class="form-control" value="<?= htmlspecialchars($stepData['diesel']['diesel_used'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <?php if (!$isReadonly): ?>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Upload Diesel Invoice</label>
                        <input type="file" name="diesel_invoice" class="form-control">
                        <?php if (!empty($stepData['diesel']['invoice_file_path'])): ?>
                            <small class="text-muted">Current file: <?= htmlspecialchars(basename($stepData['diesel']['invoice_file_path'])) ?></small>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Step 2: Biomedical Waste -->
        <div class="wizard__content" id="step-2" <?= $submission['current_step'] == 2 ? '' : 'style="display:none;"' ?>>
            <h3 class="mb-3">2. Biomedical Waste</h3>
            <div class="card mb-4">
                <div class="card__body grid grid--3" style="gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Yellow Waste (kg)</label>
                        <input type="number" step="0.01" name="waste_yellow_kg" class="form-control" value="<?= htmlspecialchars($stepData['biomedical_waste']['yellow_kg'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Red Waste (kg)</label>
                        <input type="number" step="0.01" name="waste_red_kg" class="form-control" value="<?= htmlspecialchars($stepData['biomedical_waste']['red_kg'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">White Waste (kg)</label>
                        <input type="number" step="0.01" name="waste_white_kg" class="form-control" value="<?= htmlspecialchars($stepData['biomedical_waste']['white_kg'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Blue Waste (kg)</label>
                        <input type="number" step="0.01" name="waste_blue_kg" class="form-control" value="<?= htmlspecialchars($stepData['biomedical_waste']['blue_kg'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">General Waste (kg)</label>
                        <input type="number" step="0.01" name="waste_general_kg" class="form-control" value="<?= htmlspecialchars($stepData['biomedical_waste']['general_kg'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Recycled Waste (kg)</label>
                        <input type="number" step="0.01" name="waste_recycled_kg" class="form-control" value="<?= htmlspecialchars($stepData['biomedical_waste']['recycled_kg'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group" style="grid-column: span 3;">
                        <label class="form-label">Vendor Name</label>
                        <input type="text" name="waste_vendor_name" class="form-control" value="<?= htmlspecialchars($stepData['biomedical_waste']['vendor_name'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <?php if (!$isReadonly): ?>
                    <div class="form-group" style="grid-column: span 3;">
                        <label class="form-label">Upload Waste Manifest</label>
                        <input type="file" name="waste_manifest" class="form-control">
                        <?php if (!empty($stepData['biomedical_waste']['manifest_file_path'])): ?>
                            <small class="text-muted">Current file: <?= htmlspecialchars(basename($stepData['biomedical_waste']['manifest_file_path'])) ?></small>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Step 3: Medical Gases -->
        <div class="wizard__content" id="step-3" <?= $submission['current_step'] == 3 ? '' : 'style="display:none;"' ?>>
            <h3 class="mb-3">3. Medical Gases</h3>
            <div class="card mb-4">
                <div class="card__body grid grid--2" style="gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Oxygen Cylinders</label>
                        <input type="number" name="gas_oxygen_cylinders" class="form-control" value="<?= htmlspecialchars($stepData['medical_gases']['oxygen_cylinders'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Oxygen Volume (m³)</label>
                        <input type="number" step="0.01" name="gas_oxygen_volume_m3" class="form-control" value="<?= htmlspecialchars($stepData['medical_gases']['oxygen_volume_m3'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nitrous Oxide Cylinders</label>
                        <input type="number" name="gas_nitrous_oxide_cylinders" class="form-control" value="<?= htmlspecialchars($stepData['medical_gases']['nitrous_oxide_cylinders'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nitrous Oxide Volume (m³)</label>
                        <input type="number" step="0.01" name="gas_nitrous_oxide_volume_m3" class="form-control" value="<?= htmlspecialchars($stepData['medical_gases']['nitrous_oxide_volume_m3'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Anaesthetic Gas (kg)</label>
                        <input type="number" step="0.01" name="gas_anaesthetic_gas_kg" class="form-control" value="<?= htmlspecialchars($stepData['medical_gases']['anaesthetic_gas_kg'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Supplier Name</label>
                        <input type="text" name="gas_supplier_name" class="form-control" value="<?= htmlspecialchars($stepData['medical_gases']['supplier_name'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <?php if (!$isReadonly): ?>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Upload Medical Gas Invoice</label>
                        <input type="file" name="gas_invoice" class="form-control">
                        <?php if (!empty($stepData['medical_gases']['invoice_file_path'])): ?>
                            <small class="text-muted">Current file: <?= htmlspecialchars(basename($stepData['medical_gases']['invoice_file_path'])) ?></small>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Step 4: Transportation -->
        <div class="wizard__content" id="step-4" <?= $submission['current_step'] == 4 ? '' : 'style="display:none;"' ?>>
            <h3 class="mb-3">4. Transportation</h3>
            <div class="card mb-4">
                <div class="card__body grid grid--2" style="gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Ambulance Count</label>
                        <input type="number" name="trans_ambulance_count" class="form-control" value="<?= htmlspecialchars($stepData['transportation']['ambulance_count'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Distance (km)</label>
                        <input type="number" step="0.01" name="trans_total_distance_km" class="form-control" value="<?= htmlspecialchars($stepData['transportation']['total_distance_km'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Diesel Vehicles</label>
                        <input type="number" name="trans_diesel_vehicles" class="form-control" value="<?= htmlspecialchars($stepData['transportation']['diesel_vehicles'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Petrol Vehicles</label>
                        <input type="number" name="trans_petrol_vehicles" class="form-control" value="<?= htmlspecialchars($stepData['transportation']['petrol_vehicles'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Electric Vehicles</label>
                        <input type="number" name="trans_electric_vehicles" class="form-control" value="<?= htmlspecialchars($stepData['transportation']['electric_vehicles'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Step 5: Renewable Energy -->
        <div class="wizard__content" id="step-5" <?= $submission['current_step'] == 5 ? '' : 'style="display:none;"' ?>>
            <h3 class="mb-3">5. Renewable Energy</h3>
            <div class="card mb-4">
                <div class="card__body grid grid--2" style="gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Solar Generated (kWh)</label>
                        <input type="number" step="0.01" name="ren_solar_generated_kwh" class="form-control" value="<?= htmlspecialchars($stepData['renewable_energy']['solar_generated_kwh'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Solar Used (kWh)</label>
                        <input type="number" step="0.01" name="ren_solar_used_kwh" class="form-control" value="<?= htmlspecialchars($stepData['renewable_energy']['solar_used_kwh'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Battery Storage (kWh)</label>
                        <input type="number" step="0.01" name="ren_battery_storage_kwh" class="form-control" value="<?= htmlspecialchars($stepData['renewable_energy']['battery_storage_kwh'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Grid Offset (kWh)</label>
                        <input type="number" step="0.01" name="ren_grid_offset_kwh" class="form-control" value="<?= htmlspecialchars($stepData['renewable_energy']['grid_offset_kwh'] ?? '') ?>" <?= $roAttr ?>>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Step 6: Review -->
        <div class="wizard__content" id="step-6" <?= $submission['current_step'] == 6 ? '' : 'style="display:none;"' ?>>
            <h3 class="mb-3">6. Review & Submit</h3>
            <div class="card mb-4">
                <div class="card__body text-center" style="padding: 3rem;">
                    <?php if ($isReadonly): ?>
                        <i data-lucide="check-circle" class="text-success mb-3" style="width: 64px; height: 64px; margin: 0 auto;"></i>
                        <h2>Submission Completed</h2>
                        <p class="text-muted mt-2">This submission is locked and cannot be edited.</p>
                        <a href="<?= BASE_URL ?>/hospital/submission" class="btn btn--outline mt-4">Back to List</a>
                    <?php else: ?>
                        <i data-lucide="file-check" class="text-primary mb-3" style="width: 64px; height: 64px; margin: 0 auto;"></i>
                        <h2>Ready to Submit</h2>
                        <p class="text-muted mt-2">Please ensure all entered information is accurate.</p>
                        <div class="form-group mt-4 flex align-center" style="justify-content: center; gap: 0.5rem;">
                            <input type="checkbox" id="confirm_accuracy" required>
                            <label for="confirm_accuracy" class="form-label" style="margin: 0;">I confirm the submitted information is accurate.</label>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="wizard__actions flex flex--between align-center mt-4">
            <div>
                <button type="button" class="btn btn--outline" id="prevBtn" style="display:none;">Previous</button>
            </div>
            
            <div class="flex align-center" style="gap: 1rem;">
                <div class="wizard__autosave-indicator text-muted text-sm" id="autosaveIndicator" style="display:none;">Saving...</div>
                
                <?php if (!$isReadonly): ?>
                <button type="button" class="btn btn--primary" id="nextBtn" <?= $submission['current_step'] == 6 ? 'style="display:none;"' : '' ?>>Next Step</button>
                <button type="submit" class="btn btn--success" id="submitBtn" <?= $submission['current_step'] == 6 ? '' : 'style="display:none;"' ?>>Submit & Generate Report</button>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<script>
    const isReadonly = <?= $isReadonly ? 'true' : 'false' ?>;
</script>
