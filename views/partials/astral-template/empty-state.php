<?php
/**
 * État vide (compat partials).
 *
 * @var string $title
 * @var string $message
 * @var string|null $actionLabel
 * @var string|null $actionHref
 */
$title       = (string) ($title ?? 'Rien à afficher');
$message     = (string) ($message ?? '');
$actionLabel = isset($actionLabel) ? (string) $actionLabel : null;
$actionHref  = isset($actionHref) ? (string) $actionHref : null;
?>
<div class="rounded-2xl border border-dashed border-sand-deep bg-white/70 px-6 py-12 text-center">
    <h2 class="font-display text-xl font-semibold text-ink"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h2>
    <?php if ($message !== ''): ?>
        <p class="mt-2 text-sm text-ink-muted max-w-md mx-auto"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif ?>
    <?php if ($actionLabel !== null && $actionHref !== null): ?>
        <a href="<?= htmlspecialchars($actionHref, ENT_QUOTES, 'UTF-8') ?>"
           class="inline-flex mt-6 px-4 py-2 rounded-full bg-ember text-white text-sm font-semibold hover:bg-ember-light transition no-underline">
            <?= htmlspecialchars($actionLabel, ENT_QUOTES, 'UTF-8') ?>
        </a>
    <?php endif ?>
</div>
