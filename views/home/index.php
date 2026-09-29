<section style="padding: 1.5rem 0 0.5rem">
    <p style="font-size:0.8rem;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin:0 0 0.75rem">
        Application blog · v<?= htmlspecialchars((string) $version, ENT_QUOTES, 'UTF-8') ?>
    </p>
    <h1 style="font-family:Fraunces,Georgia,serif;font-size:clamp(2rem,5vw,2.75rem);line-height:1.15;margin:0 0 1rem">
        Astral Blog
    </h1>
    <p style="font-size:1.1rem;color:var(--muted);max-width:36rem;line-height:1.55;margin:0 0 1.75rem">
        Blog complet sur <strong style="color:var(--ink)">astral-core</strong> :
        articles, catégories, tags, commentaires modérés, auth et admin.
    </p>

    <div style="display:flex;flex-wrap:wrap;gap:0.75rem;margin-bottom:2.5rem">
        <a class="btn btn-primary" href="/blog">Lire le blog</a>
        <?php if (empty($hasUsers)): ?>
            <a class="btn btn-ghost" href="/register">Créer le compte admin</a>
        <?php elseif (!$auth->check()): ?>
            <a class="btn btn-ghost" href="/login">Se connecter</a>
        <?php elseif ($auth->is(\Core\Auth\Role::ADMIN)): ?>
            <a class="btn btn-ghost" href="/admin/blog/posts">Admin articles</a>
        <?php else: ?>
            <a class="btn btn-ghost" href="/profile">Mon profil</a>
        <?php endif ?>
    </div>
</section>

<?php if (!empty($posts)): ?>
<section style="margin-bottom:2.5rem">
    <h2 style="font-family:Fraunces,Georgia,serif;font-size:1.35rem;margin:0 0 1rem">Derniers articles</h2>
    <div class="grid gap-4 sm:grid-cols-3">
        <?php foreach ($posts as $post): ?>
            <article class="rounded-2xl bg-white border border-sand-deep p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wider text-ink-muted mb-2">
                    <?= htmlspecialchars($post->published_at ?? $post->created_at, ENT_QUOTES, 'UTF-8') ?>
                </p>
                <h3 class="font-display text-lg font-semibold text-ink leading-snug">
                    <a href="/blog/<?= htmlspecialchars($post->slug, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </h3>
                <?php if ($post->excerpt !== ''): ?>
                    <p class="mt-2 text-sm text-ink-muted leading-relaxed">
                        <?= htmlspecialchars($post->excerpt, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endif ?>
            </article>
        <?php endforeach ?>
    </div>
    <p style="margin-top:1rem">
        <a href="/blog" class="text-sm font-semibold">Tous les articles →</a>
    </p>
</section>
<?php else: ?>
<section class="rounded-2xl border border-dashed border-sand-deep bg-white/70 px-6 py-10 text-center mb-8">
    <h2 class="font-display text-xl font-semibold text-ink">Aucun article publié</h2>
    <p class="mt-2 text-sm text-ink-muted">Créez un compte admin, puis publiez depuis l’admin blog.</p>
    <?php if (!empty($hasUsers) && $auth->check() && $auth->is(\Core\Auth\Role::ADMIN)): ?>
        <a href="/admin/blog/posts/create" class="inline-flex mt-5 px-4 py-2 rounded-full bg-pine text-white text-sm font-semibold no-underline">
            Nouvel article
        </a>
    <?php endif ?>
</section>
<?php endif ?>
