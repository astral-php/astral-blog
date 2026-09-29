<div class="relative overflow-hidden">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-72 bg-gradient-to-b from-pine/10 via-sand to-transparent"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-12 sm:py-16">
        <div class="mb-10 text-center sm:text-left max-w-2xl">
            <p class="text-sm font-semibold text-pine uppercase tracking-[0.2em] mb-3">Journal</p>
            <h1 class="font-display text-4xl sm:text-5xl font-bold text-ink tracking-tight">Blog</h1>
            <p class="mt-4 text-ink-muted text-lg leading-relaxed">
                Articles, catégories, tags et recherche.
            </p>
        </div>

        <form method="GET" action="/blog" class="mb-10 max-w-xl">
            <label for="q" class="sr-only">Rechercher</label>
            <div class="flex gap-2 rounded-2xl bg-white border border-sand-deep shadow-sm p-1.5 focus-within:border-pine focus-within:ring-2 focus-within:ring-pine/20 transition">
                <input type="search" id="q" name="q" value="<?= htmlspecialchars($q ?? '', ENT_QUOTES, 'UTF-8') ?>"
                       placeholder="Rechercher un article…"
                       class="flex-1 min-w-0 bg-transparent border-0 px-4 py-2.5 text-sm text-ink placeholder:text-ink-muted focus:outline-none focus:ring-0">
                <button type="submit"
                        class="shrink-0 px-5 py-2.5 rounded-xl bg-pine text-white text-sm font-semibold hover:bg-pine-light transition">
                    Chercher
                </button>
            </div>
        </form>

        <?php if (!empty($q)): ?>
            <p class="mb-6 text-sm text-ink-muted">
                <?= (int) ($pagination['total'] ?? 0) ?> résultat(s) pour
                « <strong class="text-ink"><?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?></strong> »
                — <a href="/blog" class="text-pine font-medium hover:underline">effacer</a>
            </p>
        <?php endif ?>

        <?php if (!empty($categories) || !empty($tags)): ?>
            <div class="mb-10 space-y-4">
                <?php if (!empty($categories)): ?>
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="text-xs font-semibold uppercase tracking-wider text-ink-muted mr-1">Catégories</span>
                        <a href="/blog"
                           class="text-sm px-3.5 py-1.5 rounded-full <?= empty($q) ? 'bg-pine text-white font-medium shadow-sm' : 'bg-white text-ink-muted border border-sand-deep hover:border-pine hover:text-pine' ?> transition">Tous</a>
                        <?php foreach ($categories as $cat): ?>
                            <a href="/blog/category/<?= htmlspecialchars($cat->slug, ENT_QUOTES, 'UTF-8') ?>"
                               class="text-sm px-3.5 py-1.5 rounded-full bg-white text-ink-muted border border-sand-deep hover:border-pine hover:text-pine transition">
                                <?= htmlspecialchars($cat->name, ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
                <?php if (!empty($tags)): ?>
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="text-xs font-semibold uppercase tracking-wider text-ink-muted mr-1">Tags</span>
                        <?php foreach ($tags as $t): ?>
                            <a href="/blog/tag/<?= htmlspecialchars($t->slug, ENT_QUOTES, 'UTF-8') ?>"
                               class="text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-sand text-pine hover:bg-pine hover:text-white transition">
                                <?= htmlspecialchars($t->name, ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
            </div>
        <?php endif ?>

        <?php if ($posts === []): ?>
            <?= $viewEngine->partial('partials/astral-template/empty-state', [
                'title'       => !empty($q) ? 'Aucun résultat' : 'Aucun article publié',
                'message'     => !empty($q) ? 'Essayez un autre mot-clé.' : 'Créez un article depuis l’admin pour commencer.',
                'actionLabel' => !empty($q) ? 'Voir tout le blog' : 'Admin articles',
                'actionHref'  => !empty($q) ? '/blog' : '/admin/blog/posts',
            ]) ?>
        <?php else: ?>
            <div class="grid gap-6 sm:grid-cols-2">
                <?php foreach ($posts as $post): ?>
                    <?php
                    $tagsForPost = $postTags[$post->id] ?? [];
                    require __DIR__ . '/partials/post-card.php';
                    ?>
                <?php endforeach ?>
            </div>
            <?php
            $baseUrl = '/blog';
            require __DIR__ . '/partials/pagination.php';
            ?>
        <?php endif ?>
    </div>
</div>
