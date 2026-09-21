<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin Login') ?> | EcoPulse</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --bg: #f8fafc;
            --surface: #ffffff;
            --text: #0f172a;
            --text-light: #64748b;
            --border: #e2e8f0;
        }
        body { margin: 0; padding: 0; font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }
        
        .login-layout { display: flex; width: 100%; }
        
        .login-sidebar { flex: 1; background: linear-gradient(135deg, #064e3b 0%, #10b981 100%); color: white; display: flex; flex-direction: column; justify-content: center; padding: 4rem; position: relative; overflow: hidden; }
        .login-sidebar h1 { font-size: 3rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 1rem; }
        .login-sidebar p { font-size: 1.25rem; opacity: 0.9; line-height: 1.6; max-width: 400px; }
        .eco-bg-icon { position: absolute; opacity: 0.1; right: -50px; bottom: -50px; width: 400px; height: 400px; }
        
        .login-content { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem; background: var(--surface); }
        .login-box { width: 100%; max-width: 400px; }
        
        .login-box h2 { font-size: 2rem; margin-bottom: 0.5rem; color: var(--text); }
        .login-box p { color: var(--text-light); margin-bottom: 2rem; }
        
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 1rem; box-sizing: border-box; transition: all 0.2s; }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }
        
        .btn { display: inline-flex; justify-content: center; align-items: center; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.2s; width: 100%; gap: 0.5rem; }
        .btn--primary { background: var(--primary); color: white; }
        .btn--primary:hover { background: var(--primary-dark); }
        
        .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        
        @media (max-width: 768px) {
            .login-sidebar { display: none; }
        }
    </style>
</head>
<body>
    <div class="login-layout">
        <div class="login-sidebar">
            <i data-lucide="leaf" class="eco-bg-icon"></i>
            <h1><i data-lucide="leaf" style="width: 48px; height: 48px;"></i> EcoPulse</h1>
            <p>Indian Healthcare Sustainability Management Platform. Access the administrative portal to manage hospitals, sustainability reports, and emission factors.</p>
        </div>
        <div class="login-content">
            <div class="login-box">
                <h2>Admin Login</h2>
                <p>Welcome back! Please enter your details.</p>
                
                <?php $flash = Session::getFlash(); ?>
                <?php if ($flash): ?>
                    <div class="alert">
                        <i data-lucide="alert-circle"></i>
                        <?= htmlspecialchars($flash['message']) ?>
                    </div>
                <?php endif; ?>
                
                <form action="<?= BASE_URL ?>/admin/login" method="POST">
                    <?= Session::csrfField() ?>
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required autofocus placeholder="admin@ecopulse.in" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••" autocomplete="new-password">
                    </div>
                    <button type="submit" class="btn btn--primary">
                        Sign In <i data-lucide="arrow-right"></i>
                    </button>
                </form>

                <div style="margin-top: 1.5rem; padding: 1rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; font-size: 0.85rem; color: #166534;">
                    <strong>🔑 Demo Admin Credentials:</strong><br>
                    Email: <code>admin@ecopulse.in</code> | Pass: <code>Admin@123</code>
                </div>

                <div style="margin-top: 1.25rem; text-align: center; font-size: 0.9rem;">
                    <a href="<?= BASE_URL ?>/hospital/login" style="color: var(--primary-dark); font-weight: 600; text-decoration: none;">
                        🏥 Looking for Hospital Login? Click here &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
