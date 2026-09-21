<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin') ?> | EcoPulse</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/variables.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/base.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/layout.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/components.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dark-mode.css?v=<?= time() ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --secondary: #64748b;
            --danger: #ef4444;
            --success: #22c55e;
            --bg: #f8fafc;
            --surface: #ffffff;
            --text: #0f172a;
            --text-light: #475569;
            --border: #e2e8f0;
        }
        .main-content { flex: 1; display: flex; flex-direction: column; overflow-x: hidden; }
        .header { height: 64px; background: var(--surface); border-bottom: 1px solid var(--border); display: flex; align-items: center; padding: 0 1.5rem; justify-content: space-between; }
        .content-area { padding: 1.5rem; flex: 1; overflow-y: auto; }
        
        .card { background: var(--surface); border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid var(--border); margin-bottom: 1.5rem; }
        .card__header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .card__body { padding: 1.5rem; }
        
        .stat-card { display: flex; align-items: center; padding: 1.5rem; background: var(--surface); border-radius: 8px; border: 1px solid var(--border); }
        .stat-card__icon { width: 48px; height: 48px; border-radius: 8px; background: #ecfdf5; color: var(--primary); display: flex; align-items: center; justify-content: center; margin-right: 1rem; }
        .stat-card__value { font-size: 1.5rem; font-weight: 600; margin-bottom: 0.25rem; }
        .stat-card__label { color: var(--text-light); font-size: 0.875rem; }
        
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid var(--border); }
        .data-table th { background: #f8fafc; font-weight: 500; color: var(--text-light); }
        
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 500; cursor: pointer; text-decoration: none; border: 1px solid transparent; gap: 0.5rem; font-size: 0.875rem; }
        .btn--primary { background: var(--primary); color: white; }
        .btn--primary:hover { background: var(--primary-dark); }
        .btn--outline { border-color: var(--border); background: transparent; color: var(--text); }
        .btn--outline:hover { background: #f1f5f9; }
        .btn--danger { background: var(--danger); color: white; }
        
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.875rem; }
        .form-control, .form-select { width: 100%; padding: 0.625rem; border: 1px solid var(--border); border-radius: 6px; font-family: inherit; font-size: 0.875rem; box-sizing: border-box; }
        .form-control:focus, .form-select:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }
        
        .badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; }
        .badge--success { background: #dcfce7; color: #166534; }
        .badge--warning { background: #fef9c3; color: #854d0e; }
        .badge--danger { background: #fee2e2; color: #991b1b; }
        
        .grid { display: grid; gap: 1.5rem; }
        .grid--2 { grid-template-columns: repeat(2, 1fr); }
        .grid--3 { grid-template-columns: repeat(3, 1fr); }
        .grid--4 { grid-template-columns: repeat(4, 1fr); }
        
        .flex { display: flex; }
        .flex--between { justify-content: space-between; }
        .flex--center { align-items: center; }
        .gap-2 { gap: 0.5rem; }
        .gap-4 { gap: 1rem; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .page-header__title { font-size: 1.5rem; font-weight: 600; margin: 0; }
        
        .alert { padding: 1rem; border-radius: 6px; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .alert--success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert--error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    </style>
</head>
<body>
    <div class="app-container">
        <?php require APP_PATH . '/Views/partials/sidebar_admin.php'; ?>
        <div class="main-content">
            <div class="header">
                <div class="header-left">
                    <button class="btn btn--outline" id="sidebarToggle"><i data-lucide="menu"></i></button>
                </div>
                <div class="header-right">
                    <div class="flex flex--center gap-2">
                        <i data-lucide="user-circle"></i>
                        <span><?= htmlspecialchars(Session::get('user_name') ?? 'Admin') ?></span>
                    </div>
                </div>
            </div>
            <div class="content-area">
                <?php $flash = Session::getFlash(); ?>
                <?php if ($flash): ?>
                    <div class="alert alert--<?= htmlspecialchars($flash['type']) ?>">
                        <i data-lucide="<?= $flash['type'] === 'success' ? 'check-circle' : 'alert-circle' ?>"></i>
                        <?= htmlspecialchars($flash['message']) ?>
                    </div>
                <?php endif; ?>
                
                <?php require $contentView; ?>
            </div>
        </div>
    </div>
    
    <script>
        lucide.createIcons();
        document.getElementById('sidebarToggle')?.addEventListener('click', () => {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar.style.display === 'none') {
                sidebar.style.display = 'flex';
            } else {
                sidebar.style.display = 'none';
            }
        });
    </script>
    <?php if (isset($pageScript)): ?>
    <script src="<?= BASE_URL ?>/assets/js/<?= htmlspecialchars($pageScript) ?>?v=<?= time() ?>"></script>
    <?php endif; ?>
</body>
</html>
