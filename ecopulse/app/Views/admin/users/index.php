<div class="page-header">
    <h1 class="page-header__title">Hospital Users</h1>
</div>

<div class="card">
    <div class="card__body" style="padding: 0; overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Hospital</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($user['name'] ?? 'N/A') ?></strong></td>
                            <td><?= htmlspecialchars($user['username'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['hospital_name'] ?? 'Unknown Hospital') ?></td>
                            <td>
                                <?php if (($user['status'] ?? 'active') === 'active'): ?>
                                    <span class="badge badge--success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge--danger">Suspended</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="<?= BASE_URL ?>/admin/users/delete/<?= $user['id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Delete this user?');">
                                    <?= Session::csrfField() ?>
                                    <button type="submit" class="btn btn--danger btn--sm" style="padding: 0.25rem 0.5rem;">
                                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem;">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
