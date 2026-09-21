<?php if (isset($pagination) && $pagination['pages'] > 1): ?>
<div class="pagination" style="display: flex; gap: 5px; justify-content: center; margin-top: var(--space-6);">
    <?php if ($pagination['current'] > 1): ?>
        <a href="?page=<?= $pagination['current'] - 1 ?>" class="btn btn--outline btn--sm">Prev</a>
    <?php endif; ?>
    
    <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
        <a href="?page=<?= $i ?>" class="btn btn--sm <?= $i == $pagination['current'] ? 'btn--primary' : 'btn--outline' ?>"><?= $i ?></a>
    <?php endfor; ?>
    
    <?php if ($pagination['current'] < $pagination['pages']): ?>
        <a href="?page=<?= $pagination['current'] + 1 ?>" class="btn btn--outline btn--sm">Next</a>
    <?php endif; ?>
</div>
<?php endif; ?>
