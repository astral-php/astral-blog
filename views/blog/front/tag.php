<div class="max-w-5xl mx-auto px-4 sm:px-6 py-12">
    <?= $viewEngine->partial('partials/astral-template/breadcrumb', [
        'items' => [
            ['label' => 'Blog', 'href' => '/blog'],
            ['label' => '#' . $tag->name],
        ],
    ]) ?>

    <div class="mb-10">
        <p class="text-sm font-semibold text-ember uppercase tracking-[0.15em] mb-2">Tag</p>
        <h1 class="font-display text-4xl font-bold text-ink">#<?= htmlspecialchars($tag->name, ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="mt-2 text-ink-muted text-sm"><?= (int) ($pagination['total'] ?? count($posts)) ?> article(s)</p>
    </div>

    <?php if ($posts === []): ?>
        <?= $viewEngine->partial('partials/astral-template/empty-state', [
            'title'       => 'Aucun article',
            'message'     => 'Aucun article publié avec ce tag.',
            'actionLabel' => 'Retour au blog',
            'actionHref'  => '/blog',
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
        $baseUrl = '/blog/tag/' . $tag->slug;
        $q = '';
        require __DIR__ . '/partials/pagination.php';
        ?>
    <?php endif ?>
</div>
