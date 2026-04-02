<?php
require __DIR__ . '/../includes/config.php';

ensure_ppgcc_tables();
$slug = trim((string)($_GET['slug'] ?? ''));
$page = $slug !== '' ? ppgcc_page_by_slug($slug) : null;

if (!$page) {
    http_response_code(404);
    page_header('Pagina da Pos nao encontrada');
    echo '<div class="container py-4"><div class="alert alert-danger">Pagina da pos nao encontrada.</div></div>';
    page_footer();
    exit;
}

page_header((string)$page['title']);
?>
<div class="container py-4">
    <a class="btn btn-outline-secondary btn-sm mb-3" href="/pos/inicio.php">Voltar ao subsite da Pos</a>
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h3 mb-3"><?= e((string)$page['title']) ?></h1>
            <?php if (!empty($page['summary'])): ?><p class="lead text-muted"><?= e((string)$page['summary']) ?></p><?php endif; ?>
            <div><?= render_rich_text((string)$page['content_html']) ?></div>
            <?php if (!empty($page['source_url'])): ?>
                <hr>
                <small class="text-muted">
                    Fonte original:
                    <a href="<?= e((string)$page['source_url']) ?>" target="_blank" rel="noopener"><?= e((string)$page['source_url']) ?></a>
                </small>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php page_footer(); ?>
