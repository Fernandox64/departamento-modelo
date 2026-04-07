<?php
require __DIR__ . '/../includes/config.php';
$page = page_data('quem-somos');
page_header($page['title']);
?>
<div class="container py-4">
    <h1 class="section-title h3 mb-3"><?= e($page['title']) ?></h1>
    <p class="lead mb-3"><?= e($page['summary']) ?></p>
    <div class="card shadow-sm">
        <div class="card-body">
            <p class="mb-3"><?= e($page['content']) ?></p>
            <p class="mb-0 text-muted">
                Esta pagina serve como modelo institucional. Substitua os textos por historico,
                missao, visao, valores e estrutura do departamento que for utilizar esta base.
            </p>
        </div>
    </div>
</div>
<?php page_footer(); ?>
