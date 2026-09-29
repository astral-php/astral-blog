<?php
/**
 * Carte article front.
 * @var \App\Models\Post $post
 * @var list<\App\Models\Tag> $tagsForPost
 */
$tagsForPost = $tagsForPost ?? [];
?>
<article class="group flex flex-col rounded-2xl bg-white border border-sand-deep/80 overflow-hidden shadow-sm hover:shadow-md hover:border-pine/30 transition">
    <div class="h-1.5 bg-gradient-to-r from-pine to-pine-light opacity-80 group-hover:opacity-100 transition"></div>
    <div class="flex flex-1 flex-col p-6 sm:p-7">
        <p class="text-xs font-medium uppercase tracking-wider text-ink-muted mb-3">
            <?= htmlspecialchars($post->published_at ?? $post->created_at, ENT_QUOTES, 'UTF-8') ?>
        </p>
        <h2 class="font-display text-2xl font-semibold text-ink leading-snug group-hover:text-pine transition">
            <a href="/blog/<?= htmlspecialchars($post->slug, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8') ?>
            </a>
        </h2>
        <?php if ($post->excerpt !== ''): ?>
            <p class="mt-3 text-sm text-ink-muted leading-relaxed flex-1">
                <?= htmlspecialchars($post->excerpt, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif ?>
        <?php if ($tagsForPost !== []): ?>
            <div class="mt-4 flex flex-wrap gap-1.5">
                <?php foreach ($tagsForPost as $t): ?>
                    <a href="/blog/tag/<?= htmlspecialchars($t->slug, ENT_QUOTES, 'UTF-8') ?>"
                       class="text-[11px] font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-sand text-pine hover:bg-pine hover:text-white transition">
                        <?= htmlspecialchars($t->name, ENT_QUOTES, 'UTF-8') ?>
                    </a>
                <?php endforeach ?>
            </div>
        <?php endif ?>
        <a href="/blog/<?= htmlspecialchars($post->slug, ENT_QUOTES, 'UTF-8') ?>"
           class="inline-flex items-center gap-1.5 mt-5 text-sm font-semibold text-pine hover:underline">
            Lire la suite
            <span aria-hidden="true">→</span>
        </a>
    </div>
</article>
