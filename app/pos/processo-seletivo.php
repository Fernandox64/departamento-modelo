<?php
require __DIR__ . '/../includes/config.php';

$tipo = ppgcc_selection_type_normalize((string)($_GET['tipo'] ?? 'informacao'));
$tipoLabel = ppgcc_selection_type_label($tipo);
$perPage = 10;
$page = max(1, (int)($_GET['pagina'] ?? 1));
$total = ppgcc_selection_items_count($tipo, true);
$totalPages = max(1, (int)ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;
$items = ppgcc_selection_items_paginated($tipo, $perPage, $offset, true);

page_header('Pos-graduacao - Processo Seletivo');
?>
<div class="container py-4">
    <h1 class="section-title h3 mb-3">Processo Seletivo da Pos-graduacao</h1>
    <p class="text-muted mb-4">Informacoes e resultados com paginacao.</p>

    <div class="card news-card mb-4">
        <div class="card-body d-flex flex-wrap gap-2">
            <a class="btn btn-sm <?= $tipo === 'informacao' ? 'btn-primary' : 'btn-outline-primary' ?>" href="/pos/processo-seletivo.php?tipo=informacao">Informacoes</a>
            <a class="btn btn-sm <?= $tipo === 'resultado' ? 'btn-warning' : 'btn-outline-warning' ?>" href="/pos/processo-seletivo.php?tipo=resultado">Resultados</a>
        </div>
    </div>

    <?php if (empty($items)): ?>
        <div class="alert alert-warning">Nenhum item ativo em <?= e(mb_strtolower($tipoLabel, 'UTF-8')) ?>.</div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($items as $it): ?>
                <div class="col-md-6">
                    <div class="card news-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                <span class="badge <?= $tipo === 'resultado' ? 'text-bg-warning' : 'text-bg-primary' ?>"><?= e($tipoLabel) ?></span>
                                <small class="text-muted"><?= e(date('d/m/Y', strtotime((string)$it['published_at']))) ?></small>
                            </div>
                            <h2 class="h5 mb-2"><?= e((string)$it['item_title']) ?></h2>
                            <p class="text-muted mb-2"><?= e((string)$it['group_title']) ?></p>
                            <a href="<?= e((string)$it['item_url']) ?>" target="_blank" rel="noopener">Abrir documento</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <nav class="mt-4" aria-label="Paginacao do processo seletivo">
                <ul class="pagination justify-content-center mb-0">
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <li class="page-item<?= $p === $page ? ' active' : '' ?>">
                            <a class="page-link" href="/pos/processo-seletivo.php?tipo=<?= e($tipo) ?>&pagina=<?= e((string)$p) ?>"><?= e((string)$p) ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php page_footer(); ?>
