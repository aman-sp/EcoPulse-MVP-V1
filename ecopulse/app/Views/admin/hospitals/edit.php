<div class="breadcrumbs" style="margin-bottom: 1.5rem; color: var(--text-light); font-size: 0.875rem;">
    <a href="<?= BASE_URL ?>/admin/hospitals" style="color: var(--primary); text-decoration: none;">Hospitals</a>
    <span style="margin: 0 0.5rem;">/</span>
    <span>Edit Hospital</span>
</div>

<div class="page-header">
    <h1 class="page-header__title">Edit Hospital: <?= htmlspecialchars($hospital['name'] ?? 'Unknown') ?></h1>
</div>

<div class="card">
    <div class="card__body">
        <form action="<?= BASE_URL ?>/admin/hospitals/update/<?= htmlspecialchars($hospital['id'] ?? '') ?>" method="POST">
            <?= Session::csrfField() ?>
            
            <h3 style="margin-top: 0; margin-bottom: 1rem; color: var(--primary); font-size: 1.125rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Basic Information</h3>
            <div class="grid grid--2">
                <div class="form-group">
                    <label class="form-label" for="name">Hospital Name *</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($hospital['name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="registration_number">Registration Number *</label>
                    <input type="text" id="registration_number" name="registration_number" class="form-control" value="<?= htmlspecialchars($hospital['registration_number'] ?? '') ?>" required>
                </div>
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 1rem; border-top: 1px solid var(--border); padding-top: 1.5rem; margin-top: 1.5rem;">
                <a href="<?= BASE_URL ?>/admin/hospitals" class="btn btn--outline">Cancel</a>
                <button type="submit" class="btn btn--primary">Update Hospital</button>
            </div>
        </form>
    </div>
</div>
