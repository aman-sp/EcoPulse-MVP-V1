<header class="header">
    <div class="header__left">
        <button class="btn btn--icon sidebar-toggle-mobile" id="mobileSidebarToggle">
            <i data-lucide="menu"></i>
        </button>
        <div class="breadcrumbs">
            <?php if (isset($breadcrumbs)): ?>
                <?php foreach ($breadcrumbs as $i => $crumb): ?>
                    <?php if ($i > 0): ?><span class="breadcrumbs__separator">/</span><?php endif; ?>
                    <?php if (isset($crumb['url'])): ?>
                        <a href="<?= BASE_URL . $crumb['url'] ?>" class="breadcrumbs__item"><?= htmlspecialchars($crumb['label']) ?></a>
                    <?php else: ?>
                        <span class="breadcrumbs__item"><?= htmlspecialchars($crumb['label']) ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="header__actions">
        <div class="header__search">
            <i data-lucide="search"></i>
            <input type="text" placeholder="Search..." class="search-box__input">
        </div>
        <button class="btn btn--icon" id="darkModeToggle" title="Toggle dark mode">
            <i data-lucide="moon" id="darkModeIcon"></i>
        </button>
        <div class="dropdown" id="notificationDropdown">
            <button class="btn btn--icon dropdown__trigger">
                <i data-lucide="bell"></i>
                <span class="notification-badge" id="notifBadge" style="display:none;"></span>
            </button>
            <div class="dropdown__menu">
                <div class="dropdown__header">Notifications</div>
                <div id="notifList" style="padding: 10px 15px; font-size: 0.875rem; color: var(--color-text-muted);">No new notifications</div>
            </div>
        </div>
        <div class="dropdown header__user" id="userDropdown">
            <button class="dropdown__trigger header__user-btn">
                <div class="header__avatar"><?= strtoupper(substr(Session::get('user_name', 'U'), 0, 1)) ?></div>
                <span class="header__user-name"><?= htmlspecialchars(Session::get('user_name', 'User')) ?></span>
                <i data-lucide="chevron-down"></i>
            </button>
            <div class="dropdown__menu">
                <a href="<?= BASE_URL ?>/<?= Session::get('user_role') ?>/profile" class="dropdown__item">
                    <i data-lucide="user"></i> Profile
                </a>
                <a href="<?= BASE_URL ?>/<?= Session::get('user_role') ?>/logout" class="dropdown__item">
                    <i data-lucide="log-out"></i> Logout
                </a>
            </div>
        </div>
    </div>
</header>
