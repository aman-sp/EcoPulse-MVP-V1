<div class="page-header">
    <div>
        <h1 class="page-header__title">Generated Reports</h1>
        <p class="text-muted text-sm">Download and manage your official hospital sustainability and carbon footprint reports.</p>
    </div>
</div>

<div class="card mb-6">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Reporting Month</th>
                    <th>Report Type</th>
                    <th>File Name</th>
                    <th>Generated Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reports)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted" style="padding: 3rem;">
                        <i data-lucide="file-text" style="width: 40px; height: 40px; margin-bottom: 0.5rem; opacity: 0.4;"></i>
                        <p>No generated reports available yet.</p>
                        <p class="text-xs text-muted">Reports are automatically created when your monthly submissions are completed or approved.</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($reports as $report): 
                        $reportMonth = !empty($report['month']) ? $report['month'] : $report['created_at'];
                        $formattedMonth = date('F Y', strtotime($reportMonth));
                    ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($formattedMonth) ?></strong>
                        </td>
                        <td>
                            <span class="badge badge--success" style="text-transform: capitalize;">
                                <?= htmlspecialchars($report['report_type'] ?? 'Sustainability') ?>
                            </span>
                        </td>
                        <td class="text-secondary text-sm">
                            <?= htmlspecialchars($report['file_name'] ?? ('Sustainability_Report_' . date('MY', strtotime($reportMonth)) . '.pdf')) ?>
                        </td>
                        <td class="text-muted text-sm">
                            <?= htmlspecialchars(date('d M Y, h:i A', strtotime($report['created_at']))) ?>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="<?= BASE_URL ?>/hospital/reports/download/<?= $report['id'] ?>" class="btn btn--primary" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                                    <i data-lucide="download" style="width: 14px; height: 14px;"></i> Download PDF
                                </a>
                                
                                <?php if (!empty($report['submission_id'])): ?>
                                <form action="<?= BASE_URL ?>/hospital/reports/generate/<?= $report['submission_id'] ?>" method="POST" style="margin: 0; display: inline-block;">
                                    <?= Session::csrfField() ?>
                                    <button type="submit" class="btn btn--outline" title="Re-generate Report" style="padding: 0.35rem 0.5rem; font-size: 0.8rem;">
                                        <i data-lucide="refresh-cw" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

