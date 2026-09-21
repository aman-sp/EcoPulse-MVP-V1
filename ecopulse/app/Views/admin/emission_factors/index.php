<div class="page-header">
    <h1 class="page-header__title">Emission Factors</h1>
    <div class="page-header__actions">
        <button class="btn btn--primary" onclick="alert('Open Add Factor Modal (Implementation Required)')">
            <i data-lucide="plus"></i> Add Factor
        </button>
    </div>
</div>

<?php if (!empty($factors)): ?>
    <?php foreach ($factors as $category => $categoryFactors): ?>
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card__header" style="background: #f8fafc;">
                <h3 style="margin: 0; font-size: 1.125rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="zap" style="color: var(--primary);"></i>
                    <?= htmlspecialchars($category) ?>
                </h3>
            </div>
            <div class="card__body" style="padding: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Unit</th>
                            <th>Factor Value (kgCO2e)</th>
                            <th>Version</th>
                            <th>Source</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categoryFactors as $factor): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($factor['name']) ?></strong></td>
                                <td><?= htmlspecialchars($factor['unit']) ?></td>
                                <td><?= htmlspecialchars($factor['factor'] ?? $factor['factor_value'] ?? '0') ?></td>
                                <td><?= htmlspecialchars($factor['version'] ?? '1.0') ?></td>
                                <td><?= htmlspecialchars($factor['source'] ?? 'Default') ?></td>
                                <td>
                                    <form action="<?= BASE_URL ?>/admin/emission-factors/delete/<?= $factor['id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Delete this factor?');">
                                        <?= Session::csrfField() ?>
                                        <button type="submit" class="btn btn--danger btn--sm" style="padding: 0.25rem 0.5rem;">
                                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="card">
        <div class="card__body" style="text-align: center; padding: 3rem;">
            <i data-lucide="inbox" style="width: 48px; height: 48px; color: var(--text-light); opacity: 0.5; margin-bottom: 1rem;"></i>
            <p>No emission factors found.</p>
        </div>
    </div>
<?php endif; ?>
