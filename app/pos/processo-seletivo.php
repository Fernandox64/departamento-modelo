<?php
require __DIR__ . '/../includes/config.php';

$groups = ppgcc_selection_items_grouped();
page_header('PPGCC - Processo Seletivo');
?>
<div class="container py-4">
    <h1 class="section-title h3 mb-3">Processo Seletivo do PPGCC</h1>
    <p class="text-muted mb-4">Subsite da pos com consolidacao de editais, formularios e resultados.</p>

    <?php if (empty($groups)): ?>
        <div class="alert alert-warning">Nenhum item de processo seletivo importado.</div>
    <?php else: ?>
        <?php foreach ($groups as $groupTitle => $items): ?>
            <div class="card news-card mb-3">
                <div class="card-body">
                    <h2 class="h5 mb-3"><?= e((string)$groupTitle) ?></h2>
                    <div class="row g-2">
                        <?php foreach ($items as $it): ?>
                            <div class="col-md-6">
                                <a class="d-block border rounded p-2 text-decoration-none" target="_blank" rel="noopener" href="<?= e((string)$it['item_url']) ?>">
                                    <?= e((string)$it['item_title']) ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php page_footer(); ?>
