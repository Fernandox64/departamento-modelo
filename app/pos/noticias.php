<?php
require __DIR__ . '/../includes/config.php';

$perPage = 12;
$page = max(1, (int)($_GET['pagina'] ?? 1));
$total = ppgcc_notices_count_by_type('informacao', true);
$totalPages = max(1, (int)ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;
$items = ppgcc_notices_by_type('informacao', $perPage, true, $offset);

page_header('PPGCC - Noticias');
?>
<div class="container py-4">
    <h1 class="section-title h3 mb-3">Noticias da Pos-graduacao</h1>
    <p class="text-muted mb-4">Publicacoes especificas da pos em subsite proprio.</p>

    <div class="row g-3">
        <?php foreach ($items as $it): ?>
            <div class="col-md-6">
                <div class="card news-card h-100">
                    <div class="card-body">
                        <span class="badge text-bg-primary mb-2">Noticia da Pos</span>
                        <h2 class="h5"><?= e((string)$it['title']) ?></h2>
                        <p class="news-summary mb-2"><?= e((string)$it['summary']) ?></p>
                        <small class="text-muted d-block mb-2">Publicado em <?= e(date('d/m/Y', strtotime((string)$it['published_at']))) ?></small>
                        <?php if (!empty($it['notice_url'])): ?>
                            <a href="<?= e((string)$it['notice_url']) ?>" target="_blank" rel="noopener">Acessar publicacao</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($items)): ?>
            <div class="col-12"><div class="alert alert-warning mb-0">Nenhuma noticia da pos publicada.</div></div>
        <?php endif; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <?php
            $maxVisiblePages = 10;
            $windowStart = max(1, $page - intdiv($maxVisiblePages, 2));
            $windowEnd = min($totalPages, $windowStart + $maxVisiblePages - 1);
            if (($windowEnd - $windowStart + 1) < $maxVisiblePages) {
                $windowStart = max(1, $windowEnd - $maxVisiblePages + 1);
            }
        ?>
        <nav class="mt-4" aria-label="Paginacao de noticias da pos">
            <ul class="pagination">
                <li class="page-item<?= $page <= 1 ? ' disabled' : '' ?>">
                    <a class="page-link" href="/pos/noticias.php?pagina=<?= e((string)max(1, $page - 1)) ?>">Anterior</a>
                </li>
                <?php if ($windowStart > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="/pos/noticias.php?pagina=1">1</a>
                    </li>
                <?php endif; ?>
                <?php if ($windowStart > 2): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <?php for ($p = $windowStart; $p <= $windowEnd; $p++): ?>
                    <li class="page-item<?= $p === $page ? ' active' : '' ?>">
                        <a class="page-link" href="/pos/noticias.php?pagina=<?= e((string)$p) ?>"><?= e((string)$p) ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($windowEnd < $totalPages - 1): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <?php if ($windowEnd < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="/pos/noticias.php?pagina=<?= e((string)$totalPages) ?>"><?= e((string)$totalPages) ?></a>
                    </li>
                <?php endif; ?>
                <li class="page-item<?= $page >= $totalPages ? ' disabled' : '' ?>">
                    <a class="page-link" href="/pos/noticias.php?pagina=<?= e((string)min($totalPages, $page + 1)) ?>">Proxima</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>
<?php page_footer(); ?>
