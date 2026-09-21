<div class="page-header">
    <h1 class="page-header__title">Hospitals</h1>
    <div class="page-header__actions">
        <a href="<?= BASE_URL ?>/admin/hospitals/create" class="btn btn--primary">
            <i data-lucide="plus"></i> Create Hospital
        </a>
    </div>
</div>

<div class="card">
    <div class="card__header">
        <form action="<?= BASE_URL ?>/admin/hospitals" method="GET" class="flex gap-2" style="width: 100%; max-width: 600px;">
            <div style="flex: 1;">
                <input type="text" name="search" class="form-control" placeholder="Search hospitals..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            <div style="width: 200px;">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active" <?= ($_GET['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="suspended" <?= ($_GET['status'] ?? '') === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                </select>
            </div>
            <button type="submit" class="btn btn--outline">Filter</button>
        </form>
    </div>
    <div class="card__body" style="padding: 0; overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Hospital Name</th>
                    <th>Reg. Number</th>
                    <th>City, State</th>
                    <th>Beds</th>
                    <th>NABH Status</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($hospitals)): ?>
                    <?php foreach ($hospitals as $hospital): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($hospital['name']) ?></strong></td>
                            <td><?= htmlspecialchars($hospital['registration_number']) ?></td>
                            <td><?= htmlspecialchars($hospital['city'] ?? 'N/A') ?>, <?= htmlspecialchars($hospital['state'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($hospital['beds'] ?? '0') ?></td>
                            <td>
                                <?php if (($hospital['nabh_status'] ?? '') === 'Accredited'): ?>
                                    <span class="badge badge--success">Accredited</span>
                                <?php else: ?>
                                    <span class="badge badge--warning">Not Accredited</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (($hospital['status'] ?? 'active') === 'active'): ?>
                                    <span class="badge badge--success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge--danger">Suspended</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="<?= BASE_URL ?>/admin/hospitals/edit/<?= $hospital['id'] ?>" class="btn btn--outline" style="padding: 0.25rem 0.5rem;"><i data-lucide="edit" style="width: 16px; height: 16px;"></i></a>
                                    
                                    <form action="<?= BASE_URL ?>/admin/hospitals/suspend/<?= $hospital['id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Toggle hospital status?');">
                                        <?= Session::csrfField() ?>
                                        <button type="submit" class="btn btn--outline" style="padding: 0.25rem 0.5rem;" title="Suspend/Activate">
                                            <i data-lucide="power" style="width: 16px; height: 16px;"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="<?= BASE_URL ?>/admin/hospitals/reset-password/<?= $hospital['id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Reset password for this hospital?');">
                                        <?= Session::csrfField() ?>
                                        <button type="submit" class="btn btn--outline" style="padding: 0.25rem 0.5rem;" title="Reset Password">
                                            <i data-lucide="key" style="width: 16px; height: 16px;"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="<?= BASE_URL ?>/admin/hospitals/delete/<?= $hospital['id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this hospital? This action cannot be undone.');">
                                        <?= Session::csrfField() ?>
                                        <button type="submit" class="btn btn--danger" style="padding: 0.25rem 0.5rem;">
                                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem;">
                            <div style="color: var(--text-light); margin-bottom: 1rem;"><i data-lucide="inbox" style="width: 48px; height: 48px; opacity: 0.5;"></i></div>
                            No hospitals found. <a href="<?= BASE_URL ?>/admin/hospitals/create" style="color: var(--primary);">Create one now</a>.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card__footer" style="padding: 1rem 1.5rem; border-top: 1px solid var(--border); text-align: center;">
        <!-- Pagination would go here -->
        <span class="text-light text-sm">Showing results for hospitals</span>
    </div>
</div>
