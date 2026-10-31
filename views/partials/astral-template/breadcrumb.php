<?php
/**
 * Fil d’Ariane (compat partials).
 *
 * @var list<array{label: string, href?: string}> $items
 */
$items = $items ?? [];
if ($items === []) {
    return;
}
?>
<nav aria-label="Fil d’Ariane" class="text-sm text-ink-muted mb-4">
    <ol class="flex flex-wrap items-center gap-1.5">
        <?php foreach ($items as $i => $item): ?>
            <?php if ($i > 0): ?>
                <li aria-hidden="true" class="text-sand-deep">/</li>
            <?php endif ?>
            <li>
                <?php if (!empty($item['href'])): ?>
                    <a href="<?= htmlspecialchars((string) $item['href'], ENT_QUOTES, 'UTF-8') ?>"
                       class="text-ember font-medium hover:underline">
                        <?= htmlspecialchars((string) $item['label'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                <?php else: ?>
                    <span class="text-ink"><?= htmlspecialchars((string) $item['label'], ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif ?>
            </li>
        <?php endforeach ?>
    </ol>
</nav>
