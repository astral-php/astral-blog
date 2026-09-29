<?php
$filter = $filter ?? 'pending';
$filters = [
    'pending'  => 'En attente',
    'approved' => 'Approuvés',
    'rejected' => 'Rejetés',
    'all'      => 'Tous',
];
?>
<div>
    <?= $viewEngine->partial('partials/astral-template/page-header', [
        'heading'  => 'Commentaires',
        'subtitle' => ($pendingCount ?? 0) > 0
            ? ((int) $pendingCount) . ' en attente de modération'
            : 'Modération des commentaires publics',
    ]) ?>

    <div class="flex flex-wrap gap-2 mb-6">
        <?php foreach ($filters as $key => $label): ?>
            <a href="/admin/blog/comments?status=<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"
               class="text-sm px-3.5 py-1.5 rounded-full font-medium transition <?= $filter === $key ? 'bg-indigo-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:border-indigo-300' ?>">
                <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                <?php if ($key === 'pending' && ($pendingCount ?? 0) > 0): ?>
                    <span class="ml-1 text-xs opacity-80">(<?= (int) $pendingCount ?>)</span>
                <?php endif ?>
            </a>
        <?php endforeach ?>
    </div>

    <?php if ($comments === []): ?>
        <?= $viewEngine->partial('partials/astral-template/empty-state', [
            'title'   => 'Aucun commentaire',
            'message' => 'Rien à afficher pour ce filtre.',
        ]) ?>
    <?php else: ?>
        <ul class="space-y-3">
            <?php foreach ($comments as $comment): ?>
                <li class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <p class="font-semibold text-slate-900"><?= htmlspecialchars($comment->author, ENT_QUOTES, 'UTF-8') ?></p>
                                <?php
                                $badge = match ($comment->status) {
                                    'approved' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    default    => 'bg-amber-100 text-amber-800',
                                };
                                $label = match ($comment->status) {
                                    'approved' => 'Approuvé',
                                    'rejected' => 'Rejeté',
                                    default    => 'En attente',
                                };
                                ?>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full <?= $badge ?>"><?= $label ?></span>
                            </div>
                            <p class="text-xs text-slate-400 mb-2">
                                Sur « <?= htmlspecialchars((string) ($postTitles[$comment->post_id] ?? ''), ENT_QUOTES, 'UTF-8') ?> »
                                · <?= htmlspecialchars($comment->created_at, ENT_QUOTES, 'UTF-8') ?>
                                <?php if ($comment->email !== ''): ?>
                                    · <?= htmlspecialchars($comment->email, ENT_QUOTES, 'UTF-8') ?>
                                <?php endif ?>
                            </p>
                            <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($comment->body, ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <div class="flex flex-wrap gap-2 shrink-0 text-sm">
                            <?php if ($comment->status !== 'approved'): ?>
                                <form method="POST" action="/admin/blog/comments/<?= (int) $comment->id ?>/approve">
                                    <?= $csrf->field() ?>
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700">Approuver</button>
                                </form>
                            <?php endif ?>
                            <?php if ($comment->status !== 'rejected'): ?>
                                <form method="POST" action="/admin/blog/comments/<?= (int) $comment->id ?>/reject">
                                    <?= $csrf->field() ?>
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-amber-100 text-amber-900 font-medium hover:bg-amber-200">Rejeter</button>
                                </form>
                            <?php endif ?>
                            <form method="POST" action="/admin/blog/comments/<?= (int) $comment->id ?>/delete"
                                  onsubmit="return confirm('Supprimer définitivement ?');">
                                <?= $csrf->field() ?>
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-red-600 hover:bg-red-50 font-medium">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
</div>
