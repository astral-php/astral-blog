<?php
/** @var list<array{href: string, label: string}> $adminNav */
$adminNav = $adminNav ?? [];
if ($adminNav === []) {
    return;
}
$current = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
?>
<nav class="blog-admin-nav" aria-label="Admin blog">
    <?php foreach ($adminNav as $item): ?>
        <?php
        $href = (string) ($item['href'] ?? '');
        $active = $href !== '' && str_starts_with($current, $href);
        ?>
        <a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"
           class="<?= $active ? 'is-active' : '' ?>">
            <?= htmlspecialchars((string) ($item['label'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
        </a>
    <?php endforeach ?>
</nav>
