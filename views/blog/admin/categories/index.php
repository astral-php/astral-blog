<div>
    <?= $viewEngine->partial('partials/astral-template/page-header', [
        'heading'     => 'Catégories',
        'subtitle'    => 'Organiser les articles du blog',
        'actionLabel' => 'Nouvelle catégorie',
        'actionHref'  => '/admin/blog/categories/create',
    ]) ?>

    <?php if ($categories === []): ?>
        <?= $viewEngine->partial('partials/astral-template/empty-state', [
            'title'       => 'Aucune catégorie',
            'message'     => 'Ajoutez une catégorie pour classer vos articles.',
            'actionLabel' => 'Créer une catégorie',
            'actionHref'  => '/admin/blog/categories/create',
        ]) ?>
    <?php else: ?>
        <ul class="bg-white rounded-xl border border-slate-200 shadow-sm divide-y divide-slate-100">
            <?php foreach ($categories as $cat): ?>
                <li class="flex items-center justify-between px-4 py-3.5 hover:bg-slate-50/80">
                    <div>
                        <p class="font-medium text-slate-900"><?= htmlspecialchars($cat->name, ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="text-xs text-slate-400"><?= htmlspecialchars($cat->slug, ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                    <div class="space-x-3 text-sm">
                        <a href="/admin/blog/categories/<?= (int) $cat->id ?>/edit" class="text-indigo-600 hover:underline font-medium">Éditer</a>
                        <form method="POST" action="/admin/blog/categories/<?= (int) $cat->id ?>/delete" class="inline"
                              onsubmit="return confirm('Supprimer cette catégorie ?');">
                            <?= $csrf->field() ?>
                            <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                        </form>
                    </div>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
</div>
