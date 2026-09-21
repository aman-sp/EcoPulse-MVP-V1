<div class="page-header">
    <h1 class="page-header__title">System Settings</h1>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card__body">
        <form action="<?= BASE_URL ?>/admin/settings/update" method="POST">
            <?= Session::csrfField() ?>
            
            <h3 style="margin-top: 0; margin-bottom: 1rem; color: var(--primary); font-size: 1.125rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">General Settings</h3>
            
            <div class="form-group">
                <label class="form-label" for="app_name">Application Name</label>
                <input type="text" id="app_name" name="app_name" class="form-control" value="<?= htmlspecialchars($settings['app_name'] ?? 'EcoPulse') ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="app_tagline">Application Tagline</label>
                <input type="text" id="app_tagline" name="app_tagline" class="form-control" value="<?= htmlspecialchars($settings['app_tagline'] ?? 'Indian Healthcare Sustainability Management Platform') ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="footer_text">Footer Text</label>
                <input type="text" id="footer_text" name="footer_text" class="form-control" value="<?= htmlspecialchars($settings['footer_text'] ?? '© 2024 EcoPulse. All rights reserved.') ?>">
            </div>
            
            <h3 style="margin-top: 1.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.125rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">System Configuration</h3>
            
            <div class="grid grid--2">
                <div class="form-group">
                    <label class="form-label" for="session_timeout">Session Timeout (minutes)</label>
                    <input type="number" id="session_timeout" name="session_timeout" class="form-control" value="<?= htmlspecialchars($settings['session_timeout'] ?? '120') ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="max_upload_size">Max Upload Size (MB)</label>
                    <input type="number" id="max_upload_size" name="max_upload_size" class="form-control" value="<?= htmlspecialchars($settings['max_upload_size'] ?? '10') ?>">
                </div>
            </div>
            
            <div style="margin-top: 2rem;">
                <button type="submit" class="btn btn--primary">
                    <i data-lucide="save"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
