<?php
require __DIR__ . '/../includes/config.php';

$items = demo_editais();
page_header('Editais');
?>
<section class="section section-lg pt-5">
<div class="container">
    <h1 class="section-title h3 mb-4">Editais</h1>

    <div class="row">
        <?php foreach ($items as $item): ?>
            <div class="col-md-6 col-xl-4 mb-4">
                <a class="card card-link news-card h-100" href="/noticias/ver.php?slug=<?= urlencode($item['slug']) ?>">
                    <img class="news-card-cover" src="<?= e(content_image($item)) ?>" alt="<?= e($item['title']) ?>">
                    <div class="card-body">
                        <span class="badge badge-secondary mb-2"><?= e($item['category']) ?></span>
                        <h2 class="h5 mb-2"><?= e($item['title']) ?></h2>
                        <p class="news-summary mb-3"><?= e($item['summary']) ?></p>
                        <span class="news-cta">Ver edital</span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</section>
<?php page_footer(); ?>
