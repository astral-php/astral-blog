<?php
/**
 * En-tête de page admin/front (compat partials).
 *
 * @var string $heading
 * @var string|null $subtitle
 * @var string|null $actionLabel
 * @var string|null $actionHref
 */
$heading     = (string) ($heading ?? '');
$subtitle    = isset($subtitle) ? (string) $subtitle : null;
$actionLabel = isset($actionLabel) ? (string) $actionLabel : null;
$actionHref  = isset($actionHref) ? (string) $actionHref : null;
?>
<div class="flex flex-wrap items-start justify-between gap-4 mb-8">
    <div>
        <h1 class="font-display text-3xl font-bold text-ink tracking-tight">
            <?= htmlspecialchars($heading, ENT_QUOTES, 'UTF-8') ?>
        </h1>
        <?php if ($subtitle !== null && $subtitle !== ''): ?>
            <p class="mt-1 text-sm text-ink-muted"><?= htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif ?>
    </div>
    <?php if ($actionLabel !== null && $actionHref !== null): ?>
        <a href="<?= htmlspecialchars($actionHref, ENT_QUOTES, 'UTF-8') ?>"
           class="inline-flex items-center px-4 py-2 rounded-full bg-pine text-white text-sm font-semibold hover:bg-pine-light transition no-underline">
            <?= htmlspecialchars($actionLabel, ENT_QUOTES, 'UTF-8') ?>
        </a>
    <?php endif ?>
</div>
