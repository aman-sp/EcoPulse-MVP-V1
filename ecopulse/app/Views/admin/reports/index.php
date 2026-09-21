<div class="page-header">
    <h1 class="page-header__title">Reports</h1>
</div>

<div class="card">
    <div class="card__header" style="background: #f8fafc;">
        <h3 style="margin: 0; font-size: 1.125rem;">Generate New Report</h3>
    </div>
    <div class="card__body">
        <form action="<?= BASE_URL ?>/admin/reports/generate" method="POST" class="grid grid--4" style="align-items: flex-end;">
            <?= Session::csrfField() ?>
            
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="hospital_id">Hospital</label>
                <select id="hospital_id" name="hospital_id" class="form-select" required>
                    <option value="">Select Hospital</option>
                    <?php if (!empty($hospitals)): ?>
                        <?php foreach ($hospitals as $hospital): ?>
                            <option value="<?= htmlspecialchars($hospital['id']) ?>"><?= htmlspecialchars($hospital['name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="month">Month</label>
                <input type="month" id="month" name="month" class="form-control" required value="<?= date('Y-m') ?>">
            </div>
            
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="report_type">Report Type</label>
                <select id="report_type" name="report_type" class="form-select" required>
                    <option value="sustainability">Sustainability Report</option>
                    <option value="utility">Utility Report</option>
                    <option value="carbon">Carbon Report</option>
                    <option value="waste">Waste Report</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn--primary" style="height: 42px;">
                <i data-lucide="file-text"></i> Generate
            </button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card__header">
        <h3 style="margin: 0; font-size: 1.125rem;">Generated Reports</h3>
        <div class="flex gap-2">
            <button class="btn btn--outline btn--sm"><i data-lucide="download"></i> Export List</button>
        </div>
    </div>
    <div class="card__body" style="padding: 0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Hospital Name</th>
                    <th>Report Type</th>
                    <th>Month</th>
                    <th>Generated Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reports)): ?>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($report['hospital_name'] ?? 'Unknown') ?></strong></td>
                            <td><span class="badge badge--info" style="text-transform: capitalize;"><?= htmlspecialchars($report['report_type']) ?></span></td>
                            <td><?= htmlspecialchars($report['month']) ?></td>
                            <td><?= htmlspecialchars($report['created_at']) ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>/admin/reports/download/<?= $report['id'] ?>" class="btn btn--outline btn--sm" style="padding: 0.25rem 0.5rem;" title="Download PDF">
                                    <i data-lucide="download" style="width: 16px; height: 16px;"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem;">No reports generated yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
