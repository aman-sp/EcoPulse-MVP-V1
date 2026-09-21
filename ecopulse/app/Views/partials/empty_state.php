<?php
$icon = $icon ?? 'folder-open';
$title = $title ?? 'No Data Found';
$message = $message ?? 'There is currently no data to display here.';
$actionUrl = $actionUrl ?? null;
$actionText = $actionText ?? 'Create New';
?>
<div class="empty-state">
    <div class="empty-state__icon">
        <i data-lucide="<?= htmlspecialchars($icon) ?>" style="width: 64px; height: 64px;"></i>
    </div>
    <h3 style="margin-bottom: var(--space-2);"><?= htmlspecialchars($title) ?></h3>
    <p class="empty-state__text"><?= htmlspecialchars($message) ?></p>
    <?php if ($actionUrl): ?>
        <a href="<?= BASE_URL . $actionUrl ?>" class="btn btn--primary empty-state__action">
            <i data-lucide="plus"></i> <?= htmlspecialchars($actionText) ?>
        </a>
    <?php endif; ?>
</div>
