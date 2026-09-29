<?php
/** @var \App\Models\Post $post */
/** @var \Astral\Form\FormBuilder|null $form */
$isEdit = $post->isPersisted();
$action = $isEdit ? '/admin/blog/posts/' . $post->id : '/admin/blog/posts';
$titleVal   = (string) ($old['title'] ?? $post->title);
$slugVal    = (string) ($old['slug'] ?? $post->slug);
$excerptVal = (string) ($old['excerpt'] ?? $post->excerpt);
$bodyVal    = (string) ($old['body'] ?? $post->body);
$statusVal  = (string) ($old['status'] ?? $post->status);
$catVal     = (string) ($old['category_id'] ?? ($post->category_id ?? ''));
$errors     = $errors ?? [];

$catChoices = ['' => '— aucune —'];
foreach ($categories as $cat) {
    $catChoices[(string) $cat->id] = $cat->name;
}

$allTags = $allTags ?? [];
$selectedTags = $selectedTags ?? [];
if (isset($old['tag_ids']) && is_array($old['tag_ids'])) {
    $selectedTags = array_map('intval', $old['tag_ids']);
}
?>
<div>
    <?= $viewEngine->partial('partials/astral-template/page-header', [
        'heading'  => $title,
        'subtitle' => $isEdit ? 'Modifier la publication' : 'Créer une nouvelle publication',
    ]) ?>

    <form method="POST" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>"
          class="max-w-2xl bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-5">
        <?= $csrf->field() ?>

        <?php if (isset($form)): ?>
            <?= $form->field('title', 'Titre', $titleVal, $errors, ['required' => true, 'placeholder' => 'Titre de l’article']) ?>
            <?= $form->field('slug', 'Slug', $slugVal, $errors, ['help' => 'Laisser vide pour générer depuis le titre', 'placeholder' => 'mon-article']) ?>
            <div class="mb-4">
                <?= $form->label('category_id', 'Catégorie') ?>
                <?= $form->select('category_id', $catChoices, $catVal, $errors) ?>
                <?= $form->error('category_id', $errors) ?>
            </div>
            <?= $form->field('excerpt', 'Extrait', $excerptVal, $errors, ['type' => 'textarea', 'rows' => 2]) ?>
            <?= $form->field('body', 'Contenu', $bodyVal, $errors, ['type' => 'textarea', 'required' => true, 'rows' => 12, 'class' => 'font-mono text-sm']) ?>
            <div class="mb-4">
                <span class="block text-sm font-medium text-gray-700 mb-2">Tags</span>
                <?php if ($allTags === []): ?>
                    <p class="text-xs text-slate-500">Aucun tag — <a href="/admin/blog/tags/create" class="text-indigo-600 hover:underline">en créer</a>.</p>
                <?php else: ?>
                    <div class="flex flex-wrap gap-3">
                        <?php foreach ($allTags as $t): ?>
                            <label class="inline-flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                                <input type="checkbox" name="tag_ids[]" value="<?= (int) $t->id ?>"
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                       <?= in_array((int) $t->id, $selectedTags, true) ? 'checked' : '' ?>>
                                <?= htmlspecialchars($t->name, ENT_QUOTES, 'UTF-8') ?>
                            </label>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
            </div>
            <div class="mb-4">
                <?= $form->label('status', 'Statut', ['required' => true]) ?>
                <?= $form->select('status', ['draft' => 'Brouillon', 'published' => 'Publié'], $statusVal, $errors, ['required' => true]) ?>
                <?= $form->error('status', $errors) ?>
            </div>
        <?php else: ?>
            <p class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                Installez <code>astral-form</code> pour les champs soignés. Formulaire HTML de secours actif.
            </p>
            <div>
                <label class="block text-sm font-medium mb-1">Titre</label>
                <input name="title" value="<?= htmlspecialchars($titleVal, ENT_QUOTES, 'UTF-8') ?>" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Slug</label>
                <input name="slug" value="<?= htmlspecialchars($slugVal, ENT_QUOTES, 'UTF-8') ?>" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Catégorie</label>
                <select name="category_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <?php foreach ($catChoices as $val => $label): ?>
                        <option value="<?= htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8') ?>" <?= (string) $val === $catVal ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Extrait</label>
                <textarea name="excerpt" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm"><?= htmlspecialchars($excerptVal, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Contenu</label>
                <textarea name="body" rows="12" class="w-full border rounded-lg px-3 py-2 text-sm font-mono"><?= htmlspecialchars($bodyVal, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div>
                <span class="block text-sm font-medium mb-2">Tags</span>
                <?php foreach ($allTags as $t): ?>
                    <label class="inline-flex items-center gap-2 text-sm mr-3 mb-1">
                        <input type="checkbox" name="tag_ids[]" value="<?= (int) $t->id ?>"
                               <?= in_array((int) $t->id, $selectedTags, true) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($t->name, ENT_QUOTES, 'UTF-8') ?>
                    </label>
                <?php endforeach ?>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Statut</label>
                <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="draft" <?= $statusVal === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                    <option value="published" <?= $statusVal === 'published' ? 'selected' : '' ?>>Publié</option>
                </select>
            </div>
        <?php endif ?>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">
                Enregistrer
            </button>
            <a href="/admin/blog/posts" class="px-5 py-2.5 rounded-lg bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">Annuler</a>
        </div>
    </form>
</div>
