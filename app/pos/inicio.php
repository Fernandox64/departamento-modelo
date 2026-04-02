<?php
require __DIR__ . '/../includes/config.php';

ensure_ppgcc_tables();
$pages = ppgcc_pages_list(true);

page_header('PPGCC - Subsite da Pos');
?>
<div class="container py-4">
    <h1 class="section-title h3 mb-3">PPGCC - Subsite da Pos-graduacao</h1>
    <p class="text-muted mb-4">
        Subsite interno da pos-graduacao em Ciencia da Computacao dentro do portal DECOM.
        Conteudos institucionais importados da base antiga e administrados no mesmo painel.
    </p>

    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex flex-wrap gap-2">
            <a class="btn btn-primary btn-sm" href="/pos/noticias.php">Noticias da Pos</a>
            <a class="btn btn-danger btn-sm" href="/pos/editais.php">Editais da Pos</a>
            <a class="btn btn-dark btn-sm" href="/pos/processo-seletivo.php">Processo Seletivo</a>
        </div>
    </div>

    <div class="row g-3">
        <?php foreach ($pages as $p): ?>
            <div class="col-md-6 col-xl-4">
                <a class="card card-link news-card h-100" href="/pos/pagina.php?slug=<?= urlencode((string)$p['slug']) ?>">
                    <div class="card-body">
                        <span class="badge text-bg-secondary mb-2">Pagina institucional</span>
                        <h2 class="h5"><?= e((string)$p['title']) ?></h2>
                        <p class="news-summary"><?= e((string)$p['summary']) ?></p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
        <?php if (empty($pages)): ?>
            <div class="col-12"><div class="alert alert-warning mb-0">Nenhuma pagina institucional da pos foi importada ainda.</div></div>
        <?php endif; ?>
    </div>
</div>
<?php page_footer(); ?>
