<div class="page-header">
    <h1 class="page-header__title">Dashboard</h1>
    <div class="page-header__actions">
        <p class="text-light">Welcome back, <?= htmlspecialchars(Session::get('user_name') ?? 'Admin') ?>!</p>
    </div>
</div>

<div class="grid grid--3">
    <div class="stat-card">
        <div class="stat-card__icon"><i data-lucide="building-2"></i></div>
        <div>
            <div class="stat-card__value"><?= htmlspecialchars($totalHospitals ?? '0') ?></div>
            <div class="stat-card__label">Total Hospitals</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon" style="background: #e0e7ff; color: #4f46e5;"><i data-lucide="check-square"></i></div>
        <div>
            <div class="stat-card__value"><?= htmlspecialchars($hospitalsSubmitted ?? '0') ?></div>
            <div class="stat-card__label">Submitted (This Month)</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon" style="background: #fef9c3; color: #ca8a04;"><i data-lucide="clock"></i></div>
        <div>
            <div class="stat-card__value"><?= htmlspecialchars($pendingHospitals ?? '0') ?></div>
            <div class="stat-card__label">Pending Hospitals</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon" style="background: #dbeafe; color: #2563eb;"><i data-lucide="file-text"></i></div>
        <div>
            <div class="stat-card__value"><?= htmlspecialchars($reportsGenerated ?? '0') ?></div>
            <div class="stat-card__label">Reports Generated</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon" style="background: #dcfce7; color: #166534;"><i data-lucide="activity"></i></div>
        <div>
            <div class="stat-card__value"><?= htmlspecialchars($avgScore ?? '0') ?>%</div>
            <div class="stat-card__label">Avg. Sustainability Score</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon" style="background: #fee2e2; color: #b91c1c;"><i data-lucide="cloud-rain"></i></div>
        <div>
            <div class="stat-card__value"><?= htmlspecialchars($estimatedCarbon ?? '0') ?></div>
            <div class="stat-card__label">Estimated Carbon (tCO2e)</div>
        </div>
    </div>
</div>

<div class="grid grid--2" style="margin-top: 1.5rem;">
    <div class="card">
        <div class="card__header">
            <h3 style="margin: 0; font-size: 1.125rem;">Monthly Submission Trend</h3>
        </div>
        <div class="card__body">
            <canvas id="monthlySubmissionChart" height="250"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card__header">
            <h3 style="margin: 0; font-size: 1.125rem;">Carbon Trend</h3>
        </div>
        <div class="card__body">
            <canvas id="carbonTrendChart" height="250"></canvas>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <div class="card__header">
        <h3 style="margin: 0; font-size: 1.125rem;">Hospital Registration Trend</h3>
    </div>
    <div class="card__body">
        <canvas id="hospitalTrendChart" height="300"></canvas>
    </div>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <div class="card__header">
        <h3 style="margin: 0; font-size: 1.125rem;">Recent Activities</h3>
    </div>
    <div class="card__body" style="padding: 0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($activities)): ?>
                    <?php foreach ($activities as $activity): ?>
                        <tr>
                            <td><?= htmlspecialchars($activity['created_at']) ?></td>
                            <td><?= htmlspecialchars($activity['user_type']) ?> (#<?= htmlspecialchars($activity['user_id']) ?>)</td>
                            <td><span class="badge badge--info"><?= htmlspecialchars($activity['action']) ?></span></td>
                            <td><?= htmlspecialchars($activity['description']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem;">No recent activities found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script id="dashboard-data" type="application/json">
    <?= json_encode($chart_data ?? []) ?>
</script>
