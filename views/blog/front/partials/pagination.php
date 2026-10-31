<?php
/**
 * Pagination marketing (ember).
 * @var array{current:int,pages:int,q?:string} $pagination
 * @var string $baseUrl
 */
$current = (int) ($pagination['current'] ?? 1);
$pages   = (int) ($pagination['pages'] ?? 0);
$q       = (string) ($pagination['q'] ?? ($q ?? ''));
$baseUrl = $baseUrl ?? '/blog';

if ($pages <= 1) {
    return;
}

$query = [];
if ($q !== '') {
    $query['q'] = $q;
}

$url = static function (int $page) use ($baseUrl, $query): string {
    $params = $query;
    if ($page > 1) {
        $params['page'] = $page;
    }
    $qs = http_build_query($params);

    return $qs === '' ? $baseUrl : $baseUrl . '?' . $qs;
};
?>
<nav class="mt-12 flex items-center justify-center gap-2" aria-label="Pagination">
    <?php if ($current > 1): ?>
        <a href="<?= htmlspecialchars($url($current - 1), ENT_QUOTES, 'UTF-8') ?>"
           class="px-3 py-2 rounded-lg text-sm font-medium bg-white border border-sand-deep text-ink-muted hover:border-ember hover:text-ember transition">←</a>
    <?php endif ?>

    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="<?= htmlspecialchars($url($i), ENT_QUOTES, 'UTF-8') ?>"
           class="min-w-[2.25rem] px-3 py-2 rounded-lg text-sm font-semibold text-center transition <?= $i === $current ? 'bg-ember text-white shadow-sm' : 'bg-white border border-sand-deep text-ink-muted hover:border-ember hover:text-ember' ?>">
            <?= $i ?>
        </a>
    <?php endfor ?>

    <?php if ($current < $pages): ?>
        <a href="<?= htmlspecialchars($url($current + 1), ENT_QUOTES, 'UTF-8') ?>"
           class="px-3 py-2 rounded-lg text-sm font-medium bg-white border border-sand-deep text-ink-muted hover:border-ember hover:text-ember transition">→</a>
    <?php endif ?>
</nav>
