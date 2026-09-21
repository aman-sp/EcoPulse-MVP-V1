<div class="page-header mb-4">
    <h1 class="page-header__title">Hospital Profile</h1>
    <p class="text-muted">Manage your hospital details and infrastructure information.</p>
</div>

<form action="<?= BASE_URL ?>/hospital/profile/update" method="POST">
    <?= Session::csrfField() ?>
    
    <div class="card mb-4">
        <div class="card__header">General Information</div>
        <div class="card__body">
            <div class="grid grid--2" style="gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Hospital Name</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($hospital['name'] ?? '') ?>" readonly style="background:#f1f5f9;">
                </div>
                <div class="form-group">
                    <label class="form-label">Registration Number</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($hospital['registration_number'] ?? '') ?>" readonly style="background:#f1f5f9;">
                </div>
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="clinic" <?= ($profile['type'] ?? '') == 'clinic' ? 'selected' : '' ?>>Clinic</option>
                        <option value="general_hospital" <?= ($profile['type'] ?? '') == 'general_hospital' ? 'selected' : '' ?>>General Hospital</option>
                        <option value="multispecialty" <?= ($profile['type'] ?? '') == 'multispecialty' ? 'selected' : '' ?>>Multispecialty</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Ownership</label>
                    <select name="ownership" class="form-select">
                        <option value="private" <?= ($profile['ownership'] ?? '') == 'private' ? 'selected' : '' ?>>Private</option>
                        <option value="government" <?= ($profile['ownership'] ?? '') == 'government' ? 'selected' : '' ?>>Government</option>
                        <option value="trust" <?= ($profile['ownership'] ?? '') == 'trust' ? 'selected' : '' ?>>Trust</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($hospital['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($profile['phone'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card__header">Address</div>
        <div class="card__body">
            <div class="form-group mb-3">
                <label class="form-label">Street Address</label>
                <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($profile['address'] ?? '') ?>">
            </div>
            <div class="grid grid--2" style="gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" value="<?= htmlspecialchars($profile['state'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">District</label>
                    <input type="text" name="district" class="form-control" value="<?= htmlspecialchars($profile['district'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($profile['city'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">PIN Code</label>
                    <input type="text" name="pin" class="form-control" value="<?= htmlspecialchars($profile['pin'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card__header">Infrastructure & Clinical</div>
        <div class="card__body grid grid--3" style="gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Total Beds</label>
                <input type="number" name="beds" class="form-control" value="<?= htmlspecialchars($profile['beds'] ?? 0) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Buildings</label>
                <input type="number" name="buildings" class="form-control" value="<?= htmlspecialchars($profile['buildings'] ?? 1) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Floors</label>
                <input type="number" name="floors" class="form-control" value="<?= htmlspecialchars($profile['floors'] ?? 1) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Departments</label>
                <input type="number" name="departments" class="form-control" value="<?= htmlspecialchars($profile['departments'] ?? 1) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">OT Count</label>
                <input type="number" name="ot_count" class="form-control" value="<?= htmlspecialchars($profile['ot_count'] ?? 0) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">ICU Count</label>
                <input type="number" name="icu_count" class="form-control" value="<?= htmlspecialchars($profile['icu_count'] ?? 0) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Lab Count</label>
                <input type="number" name="lab_count" class="form-control" value="<?= htmlspecialchars($profile['lab_count'] ?? 0) ?>">
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card__header">Sustainability Infrastructure</div>
        <div class="card__body grid grid--2" style="gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="solar_installed" value="1" <?= !empty($profile['solar_installed']) ? 'checked' : '' ?>>
                    Solar Installed
                </label>
            </div>
            <div class="form-group">
                <label class="form-label">Solar Capacity (kW)</label>
                <input type="number" step="0.01" name="solar_capacity_kw" class="form-control" value="<?= htmlspecialchars($profile['solar_capacity_kw'] ?? 0) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="rainwater_harvesting" value="1" <?= !empty($profile['rainwater_harvesting']) ? 'checked' : '' ?>>
                    Rainwater Harvesting
                </label>
            </div>
            <div></div> <!-- Spacer -->
            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="stp_installed" value="1" <?= !empty($profile['stp_installed']) ? 'checked' : '' ?>>
                    STP Installed
                </label>
            </div>
            <div class="form-group">
                <label class="form-label">STP Capacity (KLD)</label>
                <input type="number" step="0.01" name="stp_capacity_kld" class="form-control" value="<?= htmlspecialchars($profile['stp_capacity_kld'] ?? 0) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">DG Sets (Count)</label>
                <input type="number" name="dg_sets" class="form-control" value="<?= htmlspecialchars($profile['dg_sets'] ?? 0) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Total DG Capacity (kVA)</label>
                <input type="number" step="0.01" name="dg_capacity_kva" class="form-control" value="<?= htmlspecialchars($profile['dg_capacity_kva'] ?? 0) ?>">
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card__header">Staff & Patient Load</div>
        <div class="card__body grid grid--2" style="gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Total Staff</label>
                <input type="number" name="total_staff" class="form-control" value="<?= htmlspecialchars($profile['total_staff'] ?? 0) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Avg. Daily Patients</label>
                <input type="number" name="avg_daily_patients" class="form-control" value="<?= htmlspecialchars($profile['avg_daily_patients'] ?? 0) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Avg. Daily OPD</label>
                <input type="number" name="avg_daily_opd" class="form-control" value="<?= htmlspecialchars($profile['avg_daily_opd'] ?? 0) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Avg. Daily IPD</label>
                <input type="number" name="avg_daily_ipd" class="form-control" value="<?= htmlspecialchars($profile['avg_daily_ipd'] ?? 0) ?>">
            </div>
        </div>
    </div>

    <div class="mb-4">
        <button type="submit" class="btn btn--primary">Save Profile</button>
    </div>
</form>
