<div class="page-header">
    <h1 class="page-header__title">Admin Profile</h1>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card__body">
        <form action="<?= BASE_URL ?>/admin/profile/update" method="POST">
            <?= Session::csrfField() ?>
            
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                <div style="width: 80px; height: 80px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-light);">
                    <i data-lucide="user" style="width: 40px; height: 40px;"></i>
                </div>
                <div>
                    <h2 style="margin: 0; font-size: 1.25rem;"><?= htmlspecialchars($admin['name'] ?? 'Admin User') ?></h2>
                    <p style="margin: 0; color: var(--text-light);"><?= htmlspecialchars($admin['email'] ?? 'admin@example.com') ?></p>
                </div>
            </div>
            
            <h3 style="margin-top: 0; margin-bottom: 1rem; color: var(--primary); font-size: 1.125rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Personal Information</h3>
            
            <div class="form-group">
                <label class="form-label" for="name">Full Name *</label>
                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($admin['name'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="email">Email Address *</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($admin['email'] ?? '') ?>" required>
            </div>
            
            <h3 style="margin-top: 1.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.125rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Change Password</h3>
            <p class="text-light" style="font-size: 0.875rem; margin-bottom: 1rem;">Leave blank if you don't want to change your password.</p>
            
            <div class="form-group">
                <label class="form-label" for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" class="form-control">
            </div>
            
            <div class="grid grid--2">
                <div class="form-group">
                    <label class="form-label" for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label" for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                </div>
            </div>
            
            <div style="margin-top: 2rem;">
                <button type="submit" class="btn btn--primary">
                    <i data-lucide="save"></i> Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
