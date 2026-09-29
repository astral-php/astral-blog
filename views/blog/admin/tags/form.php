<?php
/** @var \App\Models\Tag $tag */
/** @var \Astral\Form\FormBuilder|null $form */
$isEdit = $tag->isPersisted();
$action = $isEdit ? '/admin/blog/tags/' . $tag->id : '/admin/blog/tags';
$nameVal = (string) ($old['name'] ?? $tag->name);
$slugVal = (string) ($old['slug'] ?? $tag->slug);
$errors  = $errors ?? [];
?>
<div>
    <?= $viewEngine->partial('partials/astral-template/page-header', [
        'heading'  => $title,
        'subtitle' => $isEdit ? 'Renommer ou changer le slug' : 'Ajouter un tag',
    ]) ?>

    <form method="POST" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>"
          class="max-w-lg bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-5">
        <?= $csrf->field() ?>

        <?php if (isset($form)): ?>
            <?= $form->field('name', 'Nom', $nameVal, $errors, ['required' => true]) ?>
            <?= $form->field('slug', 'Slug', $slugVal, $errors, ['help' => 'Optionnel — généré depuis le nom']) ?>
        <?php else: ?>
            <div>
                <label class="block text-sm font-medium mb-1">Nom</label>
                <input name="name" value="<?= htmlspecialchars($nameVal, ENT_QUOTES, 'UTF-8') ?>" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Slug</label>
                <input name="slug" value="<?= htmlspecialchars($slugVal, ENT_QUOTES, 'UTF-8') ?>" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
        <?php endif ?>

        <div class="flex gap-3">
            <button type="submit" class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Enregistrer</button>
            <a href="/admin/blog/tags" class="px-5 py-2.5 rounded-lg bg-slate-100 text-sm font-medium text-slate-700 hover:bg-slate-200">Annuler</a>
        </div>
    </form>
</div>
