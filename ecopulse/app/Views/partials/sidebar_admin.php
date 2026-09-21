<aside class="sidebar">
    <!-- Brand Header -->
    <div class="sidebar__brand">
        <a href="<?= BASE_URL ?>/admin/dashboard" class="sidebar__brand-link">
            <div class="sidebar__logo-wrap" style="background: linear-gradient(135deg, #6366f1, #4f46e5); box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);">
                <i data-lucide="shield-check" class="sidebar__logo-icon"></i>
            </div>
            <div class="sidebar__brand-text">
                <h2 class="sidebar__brand-title">EcoPulse</h2>
                <span class="sidebar__brand-badge sidebar__brand-badge--admin">Admin Center</span>
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="sidebar__nav">
        <div class="sidebar__section-title">OVERSIGHT & ANALYTICS</div>
        <ul class="sidebar__menu">
            <li>
                <a href="<?= BASE_URL ?>/admin/dashboard" class="sidebar__link <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <span class="sidebar__icon-box"><i data-lucide="layout-dashboard"></i></span>
                    <span class="sidebar__link-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/admin/reports" class="sidebar__link <?= ($currentPage ?? '') === 'reports' ? 'active' : '' ?>">
                    <span class="sidebar__icon-box"><i data-lucide="file-text"></i></span>
                    <span class="sidebar__link-text">Consolidated Reports</span>
                </a>
            </li>
        </ul>

        <div class="sidebar__section-title">HOSPITAL MANAGEMENT</div>
        <ul class="sidebar__menu">
            <li>
                <a href="<?= BASE_URL ?>/admin/hospitals" class="sidebar__link <?= ($currentPage ?? '') === 'hospitals' ? 'active' : '' ?>">
                    <span class="sidebar__icon-box"><i data-lucide="building-2"></i></span>
                    <span class="sidebar__link-text">Hospitals Directory</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/admin/users" class="sidebar__link <?= ($currentPage ?? '') === 'users' ? 'active' : '' ?>">
                    <span class="sidebar__icon-box"><i data-lucide="users"></i></span>
                    <span class="sidebar__link-text">Hospital Users</span>
                </a>
            </li>
        </ul>

        <div class="sidebar__section-title">PLATFORM CONFIG</div>
        <ul class="sidebar__menu">
            <li>
                <a href="<?= BASE_URL ?>/admin/emission-factors" class="sidebar__link <?= ($currentPage ?? '') === 'emission-factors' ? 'active' : '' ?>">
                    <span class="sidebar__icon-box"><i data-lucide="zap"></i></span>
                    <span class="sidebar__link-text">Emission Factors</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/admin/settings" class="sidebar__link <?= ($currentPage ?? '') === 'settings' ? 'active' : '' ?>">
                    <span class="sidebar__icon-box"><i data-lucide="settings"></i></span>
                    <span class="sidebar__link-text">System Settings</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/admin/profile" class="sidebar__link <?= ($currentPage ?? '') === 'profile' ? 'active' : '' ?>">
                    <span class="sidebar__icon-box"><i data-lucide="user"></i></span>
                    <span class="sidebar__link-text">Admin Profile</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Bottom User Profile Footer -->
    <div class="sidebar__footer">
        <div class="sidebar__user-card">
            <div class="sidebar__user-avatar-wrap">
                <div class="sidebar__user-avatar" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
                    A
                </div>
                <span class="sidebar__user-status"></span>
            </div>
            <div class="sidebar__user-info">
                <span class="sidebar__user-name" title="<?= htmlspecialchars(Session::get('user_name', 'Super Admin')) ?>">
                    <?= htmlspecialchars(Session::get('user_name', 'Super Admin')) ?>
                </span>
                <span class="sidebar__user-role">Super Administrator</span>
            </div>
            <a href="<?= BASE_URL ?>/admin/logout" class="sidebar__logout-btn" title="Logout">
                <i data-lucide="log-out"></i>
            </a>
        </div>
    </div>
</aside>

