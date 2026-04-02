<?php
require __DIR__ . '/../includes/config.php';

$item = isset($_GET['slug']) ? find_demo_item((string)$_GET['slug']) : null;

if (!$item) {
    http_response_code(404);
    page_header('Conteudo nao encontrado');
    echo '<section class="section"><div class="container"><div class="alert alert-danger">Conteudo nao encontrado.</div></div></section>';
    page_footer();
    exit;
}

page_header((string)$item['title']);
?>
<section class="section section-lg pt-5">
<div class="container">
    <div class="card shadow border-0 overflow-hidden">
        <img
            class="news-card-cover"
            style="height:320px;border-radius:0"
            src="<?= e(content_image($item)) ?>"
            alt="<?= e($item['title']) ?>"
        >
        <div class="card-body p-4 p-md-5">
            <span class="badge badge-primary mb-3"><?= e($item['category']) ?></span>
            <h1 class="display-4 mb-3"><?= e($item['title']) ?></h1>
            <p class="lead text-muted mb-4"><?= e($item['summary']) ?></p>
            <div><?= render_rich_text((string)$item['content']) ?></div>
        </div>
    </div>
</div>
</section>
<?php page_footer(); ?>
