<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Astral Blog', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,600;0,9..40,700;1,9..40,400&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        /* Nova orange — tokens Tailwind */
                        ember: { DEFAULT: '#c2410b', light: '#ea580c' },
                        sand: { DEFAULT: '#f5ebe3', deep: '#e8ddd4' },
                        ink: { DEFAULT: '#1c1612', muted: '#6b5e52' },
                    },
                    fontFamily: {
                        display: ['Fraunces', 'Georgia', 'serif'],
                        sans: ['DM Sans', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <style>
        :root {
            /* Nova orange */
            --ink: #1c1612;
            --muted: #6b5e52;
            --paper: #faf7f4;
            --panel: #ffffff;
            --line: #e8ddd4;
            --accent: #c2410b;
            --accent-soft: #ffedd5;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: "DM Sans", system-ui, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(1200px 500px at 10% -10%, #fde8d8 0%, transparent 55%),
                radial-gradient(900px 400px at 100% 0%, #f5ebe3 0%, transparent 50%),
                var(--paper);
        }
        a { color: var(--accent); text-decoration: none; }
        a:hover { text-decoration: underline; }
        .wrap { max-width: 960px; margin: 0 auto; padding: 1.25rem 1.25rem 3rem; }
        header {
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem; padding: 0.5rem 0 1.5rem; border-bottom: 1px solid var(--line);
        }
        .brand {
            font-family: Fraunces, Georgia, serif;
            font-weight: 700; font-size: 1.25rem; color: var(--ink); text-decoration: none;
        }
        .brand:hover { text-decoration: none; color: var(--accent); }
        nav { display: flex; flex-wrap: wrap; gap: 0.85rem; align-items: center; font-size: 0.9rem; }
        nav a { color: var(--muted); text-decoration: none; }
        nav a:hover { color: var(--accent); }
        .btn {
            display: inline-block; border-radius: 999px; padding: 0.55rem 1rem;
            font-weight: 600; font-size: 0.9rem; border: 1px solid transparent;
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { filter: brightness(1.05); text-decoration: none; color: #fff; }
        .btn-ghost { border-color: var(--line); color: var(--ink); background: var(--panel); }
        .btn-ghost:hover { border-color: var(--accent); color: var(--accent); text-decoration: none; }
        main { padding-top: 2rem; }
        .flash-ok, .flash-err {
            border-radius: 12px; padding: 0.75rem 1rem; margin-bottom: 1rem; font-size: 0.9rem;
        }
        .flash-ok { background: var(--accent-soft); color: #9a3412; border: 1px solid #fdba74; }
        .flash-err { background: #fdeceb; color: #8a1f1a; border: 1px solid #f0c2be; }
        footer {
            margin-top: 3rem; padding-top: 1.25rem; border-top: 1px solid var(--line);
            font-size: 0.8rem; color: var(--muted);
        }
        .blog-admin-nav {
            display: flex; flex-wrap: wrap; gap: 0.5rem;
            margin: 0 0 1.5rem; padding: 0.75rem;
            background: var(--panel); border: 1px solid var(--line); border-radius: 1rem;
        }
        .blog-admin-nav a {
            padding: 0.4rem 0.85rem; border-radius: 999px; font-size: 0.85rem;
            color: var(--muted); text-decoration: none; font-weight: 600;
        }
        .blog-admin-nav a:hover { color: var(--accent); background: var(--accent-soft); }
        .blog-admin-nav a.is-active { background: var(--accent); color: #fff; }
        .blog-admin-nav a.is-active:hover { color: #fff; filter: brightness(1.05); }

        .auth-wrap { max-width: 26rem; margin: 0 auto; }
        .auth-card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 1.1rem;
            padding: 1.75rem 1.5rem;
            box-shadow: 0 10px 30px rgba(28, 22, 18, 0.04);
        }
        .auth-card h1 {
            font-family: Fraunces, Georgia, serif;
            font-size: 1.65rem;
            font-weight: 700;
            margin: 0 0 0.35rem;
            color: var(--ink);
            text-align: center;
        }
        .auth-card .auth-lead {
            text-align: center;
            color: var(--muted);
            font-size: 0.9rem;
            margin: 0 0 1.5rem;
            line-height: 1.45;
        }
        .auth-mark {
            width: 3.25rem; height: 3.25rem; margin: 0 auto 1rem;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent);
            display: flex; align-items: center; justify-content: center;
        }
        .auth-mark svg { width: 1.5rem; height: 1.5rem; }
        .form-stack { display: flex; flex-direction: column; gap: 1.1rem; }
        .field label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 0.35rem;
        }
        .field label .hint { font-weight: 400; color: var(--muted); }
        .field-row {
            display: flex; align-items: center; justify-content: space-between;
            gap: 0.75rem; margin-bottom: 0.35rem;
        }
        .field-row label { margin: 0; }
        .input {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 0.85rem;
            padding: 0.65rem 0.9rem;
            font: inherit;
            font-size: 0.9rem;
            color: var(--ink);
            background: #fff;
            transition: border-color .15s, box-shadow .15s;
        }
        .input::placeholder { color: #8a9a91; }
        .input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(194, 65, 11, 0.15);
        }
        .input.is-invalid {
            border-color: #d9776f;
            background: #fdf6f5;
        }
        .field-error { margin: 0.35rem 0 0; font-size: 0.8rem; color: #b42318; }
        .form-errors {
            border-radius: 0.85rem;
            border: 1px solid #f0c2be;
            background: #fdeceb;
            color: #8a1f1a;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }
        .form-errors ul { margin: 0.35rem 0 0; padding-left: 1.1rem; }
        .btn-block {
            width: 100%;
            border: none;
            cursor: pointer;
            border-radius: 999px;
            padding: 0.7rem 1rem;
            font: inherit;
            font-weight: 600;
            font-size: 0.95rem;
            background: var(--accent);
            color: #fff;
        }
        .btn-block:hover { filter: brightness(1.06); }
        .auth-footer {
            margin: 1.35rem 0 0;
            text-align: center;
            font-size: 0.9rem;
            color: var(--muted);
        }
        .link-quiet { font-size: 0.8rem; color: var(--accent); }
        .notice-ok {
            display: flex; gap: 0.65rem; align-items: flex-start;
            border-radius: 0.85rem;
            border: 1px solid #fdba74;
            background: var(--accent-soft);
            color: #9a3412;
            padding: 0.85rem 1rem;
            font-size: 0.875rem;
            line-height: 1.45;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <header>
            <a class="brand" href="/">Astral Blog</a>
            <nav>
                <a href="/blog">Blog</a>
                <?php if ($auth->check()): ?>
                    <span style="color:var(--muted)"><?= htmlspecialchars($auth->name(), ENT_QUOTES, 'UTF-8') ?></span>
                    <a href="/profile">Profil</a>
                    <?php if ($auth->is(\Core\Auth\Role::Admin)): ?>
                        <a href="/admin/blog/posts">Admin blog</a>
                        <a href="/admin/users">Utilisateurs</a>
                    <?php endif ?>
                    <form method="POST" action="/logout" style="display:inline;margin:0">
                        <?= $csrf->field() ?>
                        <button type="submit" class="btn btn-ghost" style="cursor:pointer;font:inherit">Déconnexion</button>
                    </form>
                <?php else: ?>
                    <a href="/login">Connexion</a>
                    <a class="btn btn-primary" href="/register">Inscription</a>
                <?php endif ?>
            </nav>
        </header>

        <main>
            <?php if ($session->hasFlash('success')): ?>
                <div class="flash-ok"><?= htmlspecialchars((string) $session->getFlash('success'), ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif ?>
            <?php if ($session->hasFlash('error')): ?>
                <div class="flash-err"><?= htmlspecialchars((string) $session->getFlash('error'), ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif ?>

            <?php if (!empty($adminNav) && is_array($adminNav)): ?>
                <?php require BASE_PATH . '/views/blog/admin/partials/nav.php'; ?>
            <?php endif ?>

            <?= $content ?? '' ?>
        </main>

        <footer>
            Astral Blog · nova-orange · <a href="https://github.com/astral-php/astral-core" target="_blank" rel="noopener">astral-core</a>
        </footer>
    </div>
</body>
</html>
