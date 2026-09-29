<div>
    <?= $viewEngine->partial('partials/astral-template/page-header', [
        'heading'     => 'Tags',
        'subtitle'    => 'Étiquettes pour filtrer et classer les articles',
        'actionLabel' => 'Nouveau tag',
        'actionHref'  => '/admin/blog/tags/create',
    ]) ?>

    <?php if ($tags === []): ?>
        <?= $viewEngine->partial('partials/astral-template/empty-state', [
            'title'       => 'Aucun tag',
            'message'     => 'Créez des tags puis associez-les aux articles.',
            'actionLabel' => 'Créer un tag',
            'actionHref'  => '/admin/blog/tags/create',
        ]) ?>
    <?php else: ?>
        <ul class="bg-white rounded-xl border border-slate-200 shadow-sm divide-y divide-slate-100">
            <?php foreach ($tags as $tag): ?>
                <li class="flex items-center justify-between px-4 py-3.5 hover:bg-slate-50/80">
                    <div>
                        <p class="font-medium text-slate-900"><?= htmlspecialchars($tag->name, ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="text-xs text-slate-400"><?= htmlspecialchars($tag->slug, ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                    <div class="space-x-3 text-sm">
                        <a href="/blog/tag/<?= htmlspecialchars($tag->slug, ENT_QUOTES, 'UTF-8') ?>" class="text-slate-500 hover:text-indigo-600" target="_blank" rel="noopener">Voir</a>
                        <a href="/admin/blog/tags/<?= (int) $tag->id ?>/edit" class="text-indigo-600 hover:underline font-medium">Éditer</a>
                        <form method="POST" action="/admin/blog/tags/<?= (int) $tag->id ?>/delete" class="inline"
                              onsubmit="return confirm('Supprimer ce tag ?');">
                            <?= $csrf->field() ?>
                            <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                        </form>
                    </div>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
</div>
