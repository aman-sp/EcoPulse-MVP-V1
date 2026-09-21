<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Login | EcoPulse</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: #f4f6f8;
        }

        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 16px;
            padding: 3rem 2.5rem;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07), 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .login-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            margin-bottom: 2.25rem;
        }

        .login-logo svg {
            width: 26px;
            height: 26px;
            color: #0d9488;
        }

        .login-logo span {
            font-size: 1.3rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.3px;
        }

        .login-heading {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 0.35rem;
            text-align: center;
            letter-spacing: -0.4px;
        }

        .login-subheading {
            font-size: 0.88rem;
            color: #94a3b8;
            text-align: center;
            margin: 0 0 2.25rem;
            font-weight: 400;
        }

        .field {
            margin-bottom: 1.25rem;
        }

        .field label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.45rem;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .field input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            color: #0f172a;
            background: #f8fafc;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .field input:focus {
            border-color: #0d9488;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }

        .field input::placeholder {
            color: #cbd5e1;
        }

        .btn-signin {
            width: 100%;
            margin-top: 0.5rem;
            padding: 0.8rem 1rem;
            background: #0d9488;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
            letter-spacing: 0.01em;
        }

        .btn-signin:hover {
            background: #0f766e;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
        }

        .btn-signin:active {
            transform: translateY(1px);
        }

        .flash-message {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            text-align: center;
        }

        .flash-message.error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .flash-message.success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .admin-link {
            display: block;
            margin-top: 1.75rem;
            text-align: center;
            font-size: 0.82rem;
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s;
        }

        .admin-link:hover {
            color: #0d9488;
        }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="login-card">

            <div class="login-logo">
                <i data-lucide="leaf"></i>
                <span>EcoPulse</span>
            </div>

            <h1 class="login-heading">Hospital Portal</h1>
            <p class="login-subheading">Sign in to your account</p>

            <?php if (Session::has('flash')): $flash = Session::getFlash(); ?>
                <div class="flash-message <?= htmlspecialchars($flash['type']) ?>">
                    <?= htmlspecialchars($flash['message']) ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/hospital/login" method="POST">
                <?= Session::csrfField() ?>

                <div class="field">
                    <label for="username">Username or Email</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username or email" required autocomplete="username">
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                </div>

                <button type="submit" class="btn-signin">Sign In</button>
            </form>

            <a href="<?= BASE_URL ?>/admin/login" class="admin-link">Administrator? Switch to Admin Login →</a>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
