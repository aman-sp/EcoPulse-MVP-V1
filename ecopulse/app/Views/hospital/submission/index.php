<div class="page-header">
    <div>
        <h1 class="page-header__title">Monthly Submissions</h1>
        <p class="text-muted text-sm">Review past sustainability submissions and create new monthly environmental reports.</p>
    </div>
    <div class="page-header__actions">
        <a href="<?= BASE_URL ?>/hospital/submission/create" class="btn btn--primary">
            <i data-lucide="plus-circle"></i> New Monthly Submission
        </a>
    </div>
</div>

<div class="card mb-6">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Reporting Month</th>
                    <th>Status</th>
                    <th>Carbon Footprint</th>
                    <th>Submitted On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($submissions)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted" style="padding: 3rem;">
                        <i data-lucide="clipboard-list" style="width: 40px; height: 40px; margin-bottom: 0.5rem; opacity: 0.4;"></i>
                        <p>No monthly submissions found yet.</p>
                        <p class="text-xs text-muted">Click "New Monthly Submission" above to start entering your hospital's resource data.</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($submissions as $sub): 
                        $formattedMonth = !empty($sub['month']) ? date('F Y', strtotime($sub['month'])) : '—';
                    ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($formattedMonth) ?></strong>
                        </td>
                        <td>
                            <?php if ($sub['status'] === 'approved'): ?>
                                <span class="badge badge--success"><i data-lucide="check-circle" style="width:12px;height:12px;margin-right:4px;"></i> Approved</span>
                            <?php elseif ($sub['status'] === 'submitted'): ?>
                                <span class="badge badge--info"><i data-lucide="clock" style="width:12px;height:12px;margin-right:4px;"></i> Submitted</span>
                            <?php elseif ($sub['status'] === 'draft'): ?>
                                <span class="badge badge--warning"><i data-lucide="edit-3" style="width:12px;height:12px;margin-right:4px;"></i> Draft (Step <?= (int)($sub['current_step'] ?? 1) ?>/6)</span>
                            <?php else: ?>
                                <span class="badge badge--danger"><?= htmlspecialchars($sub['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (isset($sub['total_carbon']) && $sub['total_carbon'] !== null): ?>
                                <span class="font-semibold text-danger"><?= number_format((float)$sub['total_carbon'], 2) ?></span> <span class="text-xs text-muted">tCO₂e</span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted text-sm">
                            <?= !empty($sub['submitted_at']) ? date('d M Y, h:i A', strtotime($sub['submitted_at'])) : '—' ?>
                        </td>
                        <td>
                            <div class="table-actions">
                                <?php if ($sub['status'] === 'draft'): ?>
                                    <a href="<?= BASE_URL ?>/hospital/submission/create" class="btn btn--primary" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                                        <i data-lucide="edit-2" style="width: 14px; height: 14px;"></i> Continue
                                    </a>
                                <?php else: ?>
                                    <a href="<?= BASE_URL ?>/hospital/submission/view/<?= $sub['id'] ?>" class="btn btn--outline" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                                        <i data-lucide="eye" style="width: 14px; height: 14px;"></i> View Data
                                    </a>
                                    <?php if (!empty($sub['report_id'])): ?>
                                        <a href="<?= BASE_URL ?>/hospital/reports/download/<?= $sub['report_id'] ?>" class="btn btn--outline" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                                            <i data-lucide="file-text" style="width: 14px; height: 14px;"></i> Report
                                        </a>
                                    <?php endif; ?>
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

