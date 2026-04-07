<?php
require __DIR__ . '/../includes/config.php';

$perPage = 12;
$page = max(1, (int)($_GET['pagina'] ?? 1));
$total = ppgcc_notices_count_by_type('informacao', true);
$totalPages = max(1, (int)ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;
$items = ppgcc_notices_by_type('informacao', $perPage, true, $offset);

page_header('Pos-graduacao - Noticias');
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
</div>
<?php page_footer(); ?>
