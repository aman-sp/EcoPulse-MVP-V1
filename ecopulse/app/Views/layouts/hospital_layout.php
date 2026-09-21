<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Hospital') ?> | EcoPulse</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/variables.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/base.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/layout.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/components.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/wizard.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dark-mode.css?v=<?= time() ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="app-container">
        <?php require APP_PATH . '/Views/partials/sidebar_hospital.php'; ?>
        <div class="main-content">
            <?php require APP_PATH . '/Views/partials/header.php'; ?>
            <div class="content-area">
                <?php require $contentView; ?>
            </div>
        </div>
    </div>
    <?php require APP_PATH . '/Views/partials/toast.php'; ?>
    <script src="<?= BASE_URL ?>/assets/js/app.js?v=<?= time() ?>"></script>
    <?php if (isset($pageScript)): ?>
    <script src="<?= BASE_URL ?>/assets/js/<?= htmlspecialchars($pageScript) ?>?v=<?= time() ?>"></script>
    <?php endif; ?>
    <script>lucide.createIcons();</script>
</body>
</html>
