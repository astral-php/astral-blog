<div>
    <?= $viewEngine->partial('partials/astral-template/page-header', [
        'heading'     => 'Articles',
        'subtitle'    => 'Gérer les publications du blog',
        'actionLabel' => 'Nouvel article',
        'actionHref'  => '/admin/blog/posts/create',
    ]) ?>

    <?php require __DIR__ . '/../partials/usage-tips.php'; ?>

    <?php if ($posts === []): ?>
        <?= $viewEngine->partial('partials/astral-template/empty-state', [
            'title'       => 'Aucun article',
            'message'     => 'Créez votre premier article pour alimenter le blog.',
            'actionLabel' => 'Créer un article',
            'actionHref'  => '/admin/blog/posts/create',
        ]) ?>
    <?php else: ?>
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-slate-500">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Titre</th>
                        <th class="px-4 py-3 font-semibold">Statut</th>
                        <th class="px-4 py-3 font-semibold">Mis à jour</th>
                        <th class="px-4 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($posts as $post): ?>
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-3 font-medium text-slate-900">
                                <?= htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8') ?>
                                <div class="text-xs text-slate-400 font-normal"><?= htmlspecialchars($post->slug, ENT_QUOTES, 'UTF-8') ?></div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex text-xs font-semibold px-2.5 py-0.5 rounded-full <?= $post->isPublished() ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-800' ?>">
                                    <?= $post->isPublished() ? 'Publié' : 'Brouillon' ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-500 whitespace-nowrap"><?= htmlspecialchars($post->updated_at, ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="px-4 py-3 text-right whitespace-nowrap space-x-3">
                                <a href="/blog/<?= htmlspecialchars($post->slug, ENT_QUOTES, 'UTF-8') ?>" class="text-slate-500 hover:text-indigo-600" target="_blank" rel="noopener">Voir</a>
                                <a href="/admin/blog/posts/<?= (int) $post->id ?>/edit" class="text-indigo-600 hover:underline font-medium">Éditer</a>
                                <form method="POST" action="/admin/blog/posts/<?= (int) $post->id ?>/delete" class="inline"
                                      onsubmit="return confirm('Supprimer cet article ?');">
                                    <?= $csrf->field() ?>
                                    <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php endif ?>
</div>
