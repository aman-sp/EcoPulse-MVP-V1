<div class="page-header">
    <div>
        <h1 class="page-header__title">Welcome, <?= htmlspecialchars($hospitalName ?? 'Hospital') ?></h1>
        <p class="text-muted text-sm">Here is your hospital's comprehensive sustainability and carbon overview.</p>
    </div>
    <div class="page-header__actions">
        <?php if (($dashboardData['current_draft']['status'] ?? '') === 'draft'): ?>
            <a href="<?= BASE_URL ?>/hospital/submission/create" class="btn btn--primary">
                <i data-lucide="edit-3"></i> Continue Draft
            </a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/hospital/submission/create" class="btn btn--primary">
                <i data-lucide="plus-circle"></i> New Monthly Submission
            </a>
        <?php endif; ?>
        
        <?php if (!empty($dashboardData['latest_report'])): ?>
            <a href="<?= BASE_URL ?>/hospital/reports/download/<?= $dashboardData['latest_report']['id'] ?>" class="btn btn--outline">
                <i data-lucide="download"></i> Latest Report
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Primary Stats Row -->
<div class="grid grid--3 mb-6">
    <div class="stat-card stat-card--green">
        <div class="stat-card__content">
            <p class="stat-card__label">Monthly Submission Status</p>
            <div class="stat-card__value mt-1">
                <?php 
                    $currStatus = $dashboardData['current_draft']['status'] ?? $dashboardData['submissionStatus'] ?? 'pending';
                    if ($currStatus === 'approved'): 
                ?>
                    <span class="badge badge--success"><i data-lucide="check-circle" style="width:14px;height:14px;margin-right:4px;"></i> Approved</span>
                <?php elseif ($currStatus === 'submitted'): ?>
                    <span class="badge badge--info"><i data-lucide="clock" style="width:14px;height:14px;margin-right:4px;"></i> Under Review</span>
                <?php elseif ($currStatus === 'draft'): ?>
                    <span class="badge badge--warning"><i data-lucide="file-edit" style="width:14px;height:14px;margin-right:4px;"></i> In Progress</span>
                <?php else: ?>
                    <span class="badge badge--warning"><i data-lucide="alert-circle" style="width:14px;height:14px;margin-right:4px;"></i> Action Required</span>
                <?php endif; ?>
            </div>
            <p class="text-muted text-xs mt-2">
                <?= !empty($dashboardData['current_draft']['month']) ? date('F Y', strtotime($dashboardData['current_draft']['month'])) : date('F Y') ?>
            </p>
        </div>
        <div class="stat-card__icon stat-card--green">
            <i data-lucide="clipboard-check"></i>
        </div>
    </div>
    
    <div class="stat-card stat-card--rose">
        <div class="stat-card__content">
            <p class="stat-card__label">Est. Carbon Footprint</p>
            <div class="stat-card__value mt-1 text-danger">
                <?= number_format((float)($dashboardData['total_carbon'] ?? 0), 2) ?> <span class="text-sm font-semibold text-muted">tCO₂e</span>
            </div>
            <p class="text-muted text-xs mt-2">Scope 1 (Direct) + Scope 2 (Grid)</p>
        </div>
        <div class="stat-card__icon stat-card--rose">
            <i data-lucide="cloud-fog"></i>
        </div>
    </div>
    
    <div class="stat-card stat-card--purple">
        <div class="stat-card__content">
            <p class="stat-card__label">Sustainability Score</p>
            <div class="stat-card__value mt-1" style="color: #7c3aed;">
                <?= number_format((float)($dashboardData['score'] ?? 75), 1) ?> <span class="text-sm font-semibold text-muted">/ 100</span>
            </div>
            <p class="text-muted text-xs mt-2">Energy & Waste Performance Index</p>
        </div>
        <div class="stat-card__icon stat-card--purple">
            <i data-lucide="award"></i>
        </div>
    </div>
</div>

<!-- Secondary Stats Row -->
<div class="grid grid--3 mb-6">
    <div class="stat-card stat-card--amber">
        <div class="stat-card__content">
            <p class="stat-card__label">Est. Utilities Expense</p>
            <div class="stat-card__value mt-1">
                ₹<?= number_format((float)($dashboardData['total_utilities'] ?? 0), 0) ?>
            </div>
            <p class="text-muted text-xs mt-2">Grid Electricity + Diesel Fuel</p>
        </div>
        <div class="stat-card__icon stat-card--amber">
            <i data-lucide="zap"></i>
        </div>
    </div>
    
    <div class="stat-card stat-card--blue">
        <div class="stat-card__content">
            <p class="stat-card__label">Total Waste Managed</p>
            <div class="stat-card__value mt-1">
                <?= number_format((float)($dashboardData['total_waste'] ?? 0), 0) ?> <span class="text-sm font-semibold text-muted">kg</span>
            </div>
            <p class="text-muted text-xs mt-2">Biomedical + General Waste</p>
        </div>
        <div class="stat-card__icon stat-card--blue">
            <i data-lucide="trash-2"></i>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card__content">
            <p class="stat-card__label">Submission Step Progress</p>
            <?php 
                $step = (int)($dashboardData['current_draft']['current_step'] ?? 6);
                if (($dashboardData['current_draft']['status'] ?? '') === 'approved' || ($dashboardData['current_draft']['status'] ?? '') === 'submitted') {
                    $percent = 100;
                } else {
                    $percent = min(100, round(($step / 6) * 100));
                }
            ?>
            <div class="stat-card__value mt-1"><?= $percent ?>%</div>
            <div class="mt-2" style="width: 100%; background: var(--color-border); height: 8px; border-radius: 4px; overflow: hidden;">
                <div style="width: <?= $percent ?>%; background: var(--color-primary); height: 100%; border-radius: 4px; transition: width 0.4s ease;"></div>
            </div>
        </div>
        <div class="stat-card__icon">
            <i data-lucide="list-checks"></i>
        </div>
    </div>
</div>

<!-- Recommendations Alert if any -->
<?php if (!empty($dashboardData['recommendations'])): ?>
<div class="card mb-6" style="border-left: 4px solid var(--color-primary);">
    <div class="card__header" style="background-color: var(--color-primary-bg);">
        <div class="flex align-center gap-2">
            <i data-lucide="sparkles" class="text-primary" style="width: 20px; height: 20px;"></i>
            <h3 style="margin: 0; font-size: 1rem; color: var(--color-primary-dark); font-weight: 600;">Key Sustainability Recommendations</h3>
        </div>
        <span class="badge badge--success"><?= count($dashboardData['recommendations']) ?> Active Tips</span>
    </div>
    <div class="card__body p-4">
        <div style="display: grid; gap: 0.75rem;">
            <?php foreach (array_slice($dashboardData['recommendations'], 0, 3) as $rec): ?>
                <div class="flex align-center gap-3 p-2" style="background: var(--color-bg); border-radius: var(--radius-md);">
                    <?php if ($rec['severity'] === 'high'): ?>
                        <span class="badge badge--danger">High Impact</span>
                    <?php elseif ($rec['severity'] === 'medium'): ?>
                        <span class="badge badge--warning">Medium</span>
                    <?php else: ?>
                        <span class="badge badge--info">Optimization</span>
                    <?php endif; ?>
                    <span class="text-sm font-semibold" style="text-transform: capitalize; color: var(--color-text-secondary); min-width: 80px;"><?= htmlspecialchars($rec['category']) ?>:</span>
                    <span class="text-sm text-secondary flex-1"><?= htmlspecialchars($rec['recommendation']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- 2x2 Resource Consumption Trends -->
<div class="grid grid--2 mb-6">
    <div class="card m-0">
        <div class="card__header">
            <div class="flex align-center gap-2">
                <i data-lucide="zap" class="text-primary" style="width: 18px; height: 18px;"></i>
                <h3 style="margin: 0; font-size: 1rem; font-weight: 600;">Electricity Consumption (kWh)</h3>
            </div>
            <span class="text-xs text-muted">Monthly Trend</span>
        </div>
        <div class="card__body" style="height: 280px;">
            <canvas id="electricityChart"></canvas>
        </div>
    </div>
    
    <div class="card m-0">
        <div class="card__header">
            <div class="flex align-center gap-2">
                <i data-lucide="droplet" class="text-info" style="width: 18px; height: 18px;"></i>
                <h3 style="margin: 0; font-size: 1rem; font-weight: 600;">Water Consumption (kL)</h3>
            </div>
            <span class="text-xs text-muted">Municipal + Borewell + Tanker</span>
        </div>
        <div class="card__body" style="height: 280px;">
            <canvas id="waterChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid--2 mb-6">
    <div class="card m-0">
        <div class="card__header">
            <div class="flex align-center gap-2">
                <i data-lucide="flame" class="text-warning" style="width: 18px; height: 18px;"></i>
                <h3 style="margin: 0; font-size: 1rem; font-weight: 600;">Diesel Generator Usage (Liters)</h3>
            </div>
            <span class="text-xs text-muted">Monthly Generator Fuel</span>
        </div>
        <div class="card__body" style="height: 280px;">
            <canvas id="dieselChart"></canvas>
        </div>
    </div>
    
    <div class="card m-0">
        <div class="card__header">
            <div class="flex align-center gap-2">
                <i data-lucide="trash-2" style="width: 18px; height: 18px; color: #ec4899;"></i>
                <h3 style="margin: 0; font-size: 1rem; font-weight: 600;">Biomedical & General Waste (kg)</h3>
            </div>
            <span class="text-xs text-muted">Monthly Total Weight</span>
        </div>
        <div class="card__body" style="height: 280px;">
            <canvas id="wasteChart"></canvas>
        </div>
    </div>
</div>

<!-- Full Width Carbon Footprint Trend Chart -->
<div class="card mb-6">
    <div class="card__header">
        <div class="flex align-center gap-2">
            <i data-lucide="trending-up" class="text-success" style="width: 20px; height: 20px;"></i>
            <div>
                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 600;">Total Carbon Footprint Trajectory (tCO₂e)</h3>
                <span class="text-xs text-muted">Scope 1 & Scope 2 aggregated emissions across historical reporting periods</span>
            </div>
        </div>
    </div>
    <div class="card__body" style="height: 350px;">
        <canvas id="carbonChart"></canvas>
    </div>
</div>

<!-- Recent Submissions Table -->
<div class="card mb-6">
    <div class="card__header">
        <div class="flex align-center gap-2">
            <i data-lucide="history" class="text-secondary" style="width: 18px; height: 18px;"></i>
            <h3 style="margin: 0; font-size: 1rem; font-weight: 600;">Recent Monthly Submissions</h3>
        </div>
        <a href="<?= BASE_URL ?>/hospital/submission" class="btn btn--outline btn--sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
            View All
        </a>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Reporting Month</th>
                    <th>Status</th>
                    <th>Submitted On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dashboardData['recentSubmissions'])): ?>
                    <?php foreach (array_slice($dashboardData['recentSubmissions'], 0, 6) as $sub): ?>
                        <tr>
                            <td>
                                <strong><?= date('F Y', strtotime($sub['month'])) ?></strong>
                            </td>
                            <td>
                                <?php if ($sub['status'] === 'approved'): ?>
                                    <span class="badge badge--success">Approved</span>
                                <?php elseif ($sub['status'] === 'submitted'): ?>
                                    <span class="badge badge--info">Submitted</span>
                                <?php elseif ($sub['status'] === 'draft'): ?>
                                    <span class="badge badge--warning">Draft (Step <?= (int)($sub['current_step'] ?? 1) ?>/6)</span>
                                <?php else: ?>
                                    <span class="badge badge--danger"><?= htmlspecialchars($sub['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted text-sm">
                                <?= !empty($sub['submitted_at']) ? date('d M Y, h:i A', strtotime($sub['submitted_at'])) : '—' ?>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <?php if ($sub['status'] === 'draft'): ?>
                                        <a href="<?= BASE_URL ?>/hospital/submission/create" class="btn btn--outline" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;">
                                            <i data-lucide="edit-2" style="width: 12px; height: 12px;"></i> Resume
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= BASE_URL ?>/hospital/submission/view/<?= $sub['id'] ?>" class="btn btn--outline" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;">
                                            <i data-lucide="eye" style="width: 12px; height: 12px;"></i> View
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted" style="padding: 2rem;">No submissions found yet. Click "New Monthly Submission" to start.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script id="dashboard-data" type="application/json">
    <?= json_encode($dashboardData['chart_data'] ?? []) ?>
</script>
