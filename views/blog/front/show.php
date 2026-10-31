<div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
    <?= $viewEngine->partial('partials/astral-template/breadcrumb', [
        'items' => array_values(array_filter([
            ['label' => 'Blog', 'href' => '/blog'],
            $category !== null
                ? ['label' => $category->name, 'href' => '/blog/category/' . $category->slug]
                : null,
            ['label' => $post->title],
        ])),
    ]) ?>

    <article class="mt-6 rounded-3xl bg-white border border-sand-deep/80 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-br from-ember to-ember-light px-8 py-10 text-white">
            <p class="text-sm text-white/70 mb-3">
                <?= htmlspecialchars($post->published_at ?? $post->created_at, ENT_QUOTES, 'UTF-8') ?>
            </p>
            <h1 class="font-display text-3xl sm:text-4xl font-bold leading-tight">
                <?= htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8') ?>
            </h1>
            <?php if ($post->excerpt !== ''): ?>
                <p class="mt-4 text-lg text-white/90 leading-relaxed">
                    <?= htmlspecialchars($post->excerpt, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif ?>
            <?php if (!empty($postTags)): ?>
                <div class="mt-6 flex flex-wrap gap-2">
                    <?php foreach ($postTags as $t): ?>
                        <a href="/blog/tag/<?= htmlspecialchars($t->slug, ENT_QUOTES, 'UTF-8') ?>"
                           class="text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-white/15 text-white hover:bg-white/25 transition">
                            <?= htmlspecialchars($t->name, ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        </div>
        <div class="px-8 py-10 text-ink leading-relaxed text-[1.05rem] space-y-4">
            <?= nl2br(htmlspecialchars($post->body, ENT_QUOTES, 'UTF-8')) ?>
        </div>
    </article>

    <section id="comments" class="mt-12 scroll-mt-24">
        <h2 class="font-display text-2xl font-bold text-ink mb-6">
            Commentaires
            <span class="text-base font-sans font-normal text-ink-muted">(<?= count($comments ?? []) ?>)</span>
        </h2>

        <?php if (!empty($commentSuccess)): ?>
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
                <?= htmlspecialchars((string) $commentSuccess, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif ?>
        <?php if (!empty($commentError)): ?>
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
                <?= htmlspecialchars((string) $commentError, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif ?>

        <?php if (!empty($comments)): ?>
            <ul class="space-y-4 mb-10">
                <?php foreach ($comments as $comment): ?>
                    <li class="rounded-2xl bg-white border border-sand-deep/80 p-5 shadow-sm">
                        <div class="flex items-baseline justify-between gap-3 mb-2">
                            <p class="font-semibold text-ink"><?= htmlspecialchars($comment->author, ENT_QUOTES, 'UTF-8') ?></p>
                            <time class="text-xs text-ink-muted whitespace-nowrap">
                                <?= htmlspecialchars($comment->created_at, ENT_QUOTES, 'UTF-8') ?>
                            </time>
                        </div>
                        <p class="text-sm text-ink-muted leading-relaxed">
                            <?= nl2br(htmlspecialchars($comment->body, ENT_QUOTES, 'UTF-8')) ?>
                        </p>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php else: ?>
            <p class="text-sm text-ink-muted mb-8">Aucun commentaire pour le moment — soyez le premier.</p>
        <?php endif ?>

        <?php
        $errors = $commentErrors ?? [];
        $old = $commentOld ?? [];
        ?>
        <div class="rounded-2xl bg-white border border-sand-deep/80 p-6 shadow-sm">
            <h3 class="font-display text-xl font-semibold text-ink mb-1">Laisser un commentaire</h3>
            <p class="text-sm text-ink-muted mb-5 leading-relaxed">
                Les commentaires sont soumis à modération. Ils sont publiés s’ils restent respectueux,
                pertinents et sans spam. Après envoi, votre message est enregistré et publié dès validation.
            </p>

            <form method="POST" action="/blog/<?= htmlspecialchars($post->slug, ENT_QUOTES, 'UTF-8') ?>/comments" class="space-y-4">
                <?= $csrf->field() ?>
                <!-- Honeypot -->
                <div class="hidden" aria-hidden="true">
                    <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="author" class="block text-sm font-medium text-ink mb-1">Nom <span class="text-red-600">*</span></label>
                        <input type="text" id="author" name="author" required maxlength="120"
                               value="<?= htmlspecialchars((string) ($old['author'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                               class="w-full rounded-xl border border-sand-deep px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ember/30 focus:border-ember <?= !empty($errors['author']) ? 'border-red-400 bg-red-50' : '' ?>">
                        <?php if (!empty($errors['author'][0])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['author'][0], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif ?>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-ink mb-1">E-mail <span class="text-ink-muted font-normal">(optionnel)</span></label>
                        <input type="email" id="email" name="email" maxlength="180"
                               value="<?= htmlspecialchars((string) ($old['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                               class="w-full rounded-xl border border-sand-deep px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ember/30 focus:border-ember <?= !empty($errors['email']) ? 'border-red-400 bg-red-50' : '' ?>">
                        <?php if (!empty($errors['email'][0])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['email'][0], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif ?>
                    </div>
                </div>

                <div>
                    <label for="body" class="block text-sm font-medium text-ink mb-1">Message <span class="text-red-600">*</span></label>
                    <textarea id="body" name="body" required rows="4" maxlength="2000"
                              class="w-full rounded-xl border border-sand-deep px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ember/30 focus:border-ember <?= !empty($errors['body']) ? 'border-red-400 bg-red-50' : '' ?>"><?= htmlspecialchars((string) ($old['body'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                    <?php if (!empty($errors['body'][0])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['body'][0], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif ?>
                </div>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-ember text-white text-sm font-semibold hover:bg-ember-light transition">
                    Envoyer
                </button>
            </form>
        </div>
    </section>

    <p class="mt-10 text-center">
        <a href="/blog" class="text-sm font-semibold text-ember hover:underline">← Retour au blog</a>
    </p>
</div>
