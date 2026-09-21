<div class="toast-container" id="toastContainer"></div>
<?php $flash = Session::getFlash(); ?>
<?php if ($flash): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof showToast === 'function') {
            showToast('<?= addslashes($flash['message']) ?>', '<?= $flash['type'] ?>');
        }
    });
</script>
<?php endif; ?>
