<?php require __DIR__ . '/../includes/config.php'; $page = page_data('pesquisa'); page_header('Pesquisa'); ?>
<div class="container py-4"><h1 class="section-title h3 mb-4"><?= e($page['title']) ?></h1><div class="card shadow-sm"><div class="card-body"><p class="lead"><?= e($page['summary']) ?></p><div><?= nl2br(e($page['content'])) ?></div></div></div></div><?php page_footer(); ?>
