<div class="breadcrumbs" style="margin-bottom: 1.5rem; color: var(--text-light); font-size: 0.875rem;">
    <a href="<?= BASE_URL ?>/admin/hospitals" style="color: var(--primary); text-decoration: none;">Hospitals</a>
    <span style="margin: 0 0.5rem;">/</span>
    <span>Create Hospital</span>
</div>

<div class="page-header">
    <h1 class="page-header__title">Create Hospital</h1>
</div>

<div class="card">
    <div class="card__body">
        <form action="<?= BASE_URL ?>/admin/hospitals/store" method="POST">
            <?= Session::csrfField() ?>
            
            <h3 style="margin-top: 0; margin-bottom: 1rem; color: var(--primary); font-size: 1.125rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Basic Information</h3>
            <div class="grid grid--2">
                <div class="form-group">
                    <label class="form-label" for="name">Hospital Name *</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="registration_number">Registration Number *</label>
                    <input type="text" id="registration_number" name="registration_number" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="type">Hospital Type *</label>
                    <select id="type" name="type" class="form-select" required>
                        <option value="">Select Type</option>
                        <option value="General">General</option>
                        <option value="Specialty">Specialty</option>
                        <option value="Clinic">Clinic</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="ownership">Ownership</label>
                    <select id="ownership" name="ownership" class="form-select">
                        <option value="Private">Private</option>
                        <option value="Public">Public/Government</option>
                        <option value="Trust">Trust/NGO</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Primary Email *</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control">
                </div>
            </div>
            
            <h3 style="margin-top: 1.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.125rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Address</h3>
            <div class="form-group">
                <label class="form-label" for="address">Full Address</label>
                <input type="text" id="address" name="address" class="form-control">
            </div>
            <div class="grid grid--4">
                <div class="form-group">
                    <label class="form-label" for="city">City</label>
                    <input type="text" id="city" name="city" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label" for="district">District</label>
                    <input type="text" id="district" name="district" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label" for="state">State</label>
                    <input type="text" id="state" name="state" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label" for="pin_code">PIN Code</label>
                    <input type="text" id="pin_code" name="pin_code" class="form-control">
                </div>
            </div>
            
            <h3 style="margin-top: 1.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.125rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Infrastructure & Facilities</h3>
            <div class="grid grid--3">
                <div class="form-group">
                    <label class="form-label" for="beds">Number of Beds</label>
                    <input type="number" id="beds" name="beds" class="form-control" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label" for="buildings">Number of Buildings</label>
                    <input type="number" id="buildings" name="buildings" class="form-control" min="1" value="1">
                </div>
                <div class="form-group">
                    <label class="form-label" for="dg_sets">Number of DG Sets</label>
                    <input type="number" id="dg_sets" name="dg_sets" class="form-control" min="0" value="0">
                </div>
            </div>
            
            <div class="grid grid--3" style="margin-bottom: 1.5rem;">
                <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" id="solar_installed" name="solar_installed" value="1">
                    <label class="form-label" for="solar_installed" style="margin: 0;">Solar Panels Installed</label>
                </div>
                <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" id="stp_installed" name="stp_installed" value="1">
                    <label class="form-label" for="stp_installed" style="margin: 0;">STP (Sewage Treatment) Installed</label>
                </div>
                <div class="form-group">
                    <label class="form-label" for="nabh_status">NABH Status</label>
                    <select id="nabh_status" name="nabh_status" class="form-select">
                        <option value="Not Accredited">Not Accredited</option>
                        <option value="Applied">Applied</option>
                        <option value="Accredited">Accredited</option>
                    </select>
                </div>
            </div>
            
            <div class="alert alert--info" style="background: #e0f2fe; color: #0369a1; border-color: #bae6fd; margin-bottom: 2rem;">
                <i data-lucide="info"></i>
                <div>
                    <strong>User Account</strong><br>
                    A primary user account will be automatically generated with a random password. Credentials will be displayed on the next screen.
                </div>
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 1rem; border-top: 1px solid var(--border); padding-top: 1.5rem;">
                <a href="<?= BASE_URL ?>/admin/hospitals" class="btn btn--outline">Cancel</a>
                <button type="submit" class="btn btn--primary">Create Hospital</button>
            </div>
        </form>
    </div>
</div>
