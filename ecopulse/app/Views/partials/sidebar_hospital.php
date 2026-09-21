<aside class="sidebar">
    <!-- Brand Header -->
    <div class="sidebar__brand">
        <a href="<?= BASE_URL ?>/hospital/dashboard" class="sidebar__brand-link">
            <div class="sidebar__logo-wrap">
                <i data-lucide="leaf" class="sidebar__logo-icon"></i>
            </div>
            <div class="sidebar__brand-text">
                <h2 class="sidebar__brand-title">EcoPulse</h2>
                <span class="sidebar__brand-badge">Hospital Portal</span>
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="sidebar__nav">
        <div class="sidebar__section-title">MAIN</div>
        <ul class="sidebar__menu">
            <li>
                <a href="<?= BASE_URL ?>/hospital/dashboard" class="sidebar__link <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <span class="sidebar__icon-box"><i data-lucide="layout-dashboard"></i></span>
                    <span class="sidebar__link-text">Dashboard</span>
                </a>
            </li>
        </ul>

        <div class="sidebar__section-title">SUSTAINABILITY DATA</div>
        <ul class="sidebar__menu">
            <li>
                <a href="<?= BASE_URL ?>/hospital/submission" class="sidebar__link <?= ($currentPage ?? '') === 'submission' ? 'active' : '' ?>">
                    <span class="sidebar__icon-box"><i data-lucide="clipboard-list"></i></span>
                    <span class="sidebar__link-text">Monthly Submissions</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/hospital/reports" class="sidebar__link <?= ($currentPage ?? '') === 'reports' ? 'active' : '' ?>">
                    <span class="sidebar__icon-box"><i data-lucide="file-text"></i></span>
                    <span class="sidebar__link-text">Reports & Certs</span>
                </a>
            </li>
        </ul>

        <div class="sidebar__section-title">ORGANIZATION</div>
        <ul class="sidebar__menu">
            <li>
                <a href="<?= BASE_URL ?>/hospital/profile" class="sidebar__link <?= ($currentPage ?? '') === 'profile' ? 'active' : '' ?>">
                    <span class="sidebar__icon-box"><i data-lucide="building-2"></i></span>
                    <span class="sidebar__link-text">Hospital Profile</span>
                </a>
            </li>
        </ul>

        <!-- Quick ESG Sustainability Widget Card in Sidebar -->
        <div class="sidebar__card">
            <div class="sidebar__card-header">
                <span class="sidebar__card-tag"><i data-lucide="sparkles" style="width:12px;height:12px;margin-right:4px;"></i> ESG Tracker</span>
            </div>
            <p class="sidebar__card-desc">Keep your carbon data verified and updated monthly.</p>
            <a href="<?= BASE_URL ?>/hospital/submission/create" class="sidebar__card-btn">
                <i data-lucide="plus-circle" style="width:14px;height:14px;"></i>
                <span>Submit Data</span>
            </a>
        </div>
    </nav>

    <!-- Bottom User Profile Footer -->
    <div class="sidebar__footer">
        <div class="sidebar__user-card">
            <div class="sidebar__user-avatar-wrap">
                <div class="sidebar__user-avatar">
                    <?= strtoupper(substr(Session::get('user_name', 'H'), 0, 1)) ?>
                </div>
                <span class="sidebar__user-status"></span>
            </div>
            <div class="sidebar__user-info">
                <span class="sidebar__user-name" title="<?= htmlspecialchars(Session::get('user_name', 'Hospital Admin')) ?>">
                    <?= htmlspecialchars(Session::get('user_name', 'Hospital Admin')) ?>
                </span>
                <span class="sidebar__user-role">Hospital Admin</span>
            </div>
            <a href="<?= BASE_URL ?>/hospital/logout" class="sidebar__logout-btn" title="Logout">
                <i data-lucide="log-out"></i>
            </a>
        </div>
    </div>
</aside>


