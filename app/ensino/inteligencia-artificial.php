<?php
declare(strict_types=1);

require __DIR__ . '/../includes/config.php';

$course = course_data('inteligencia-artificial');
page_header($course['name']);
?>
<div class="container py-4">
    <h1 class="section-title h3 mb-4"><?= e($course['name']) ?></h1>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p class="lead mb-2"><?= e($course['summary']) ?></p>
            <p class="mb-0"><?= e($course['content']) ?></p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4"><div class="card shadow-sm h-100"><div class="card-body"><h2 class="h6 text-uppercase text-muted mb-2">Modalidade</h2><p class="mb-0"><?= e($course['modality']) ?></p></div></div></div>
        <div class="col-lg-4"><div class="card shadow-sm h-100"><div class="card-body"><h2 class="h6 text-uppercase text-muted mb-2">Duracao</h2><p class="mb-0"><?= e($course['duration']) ?></p></div></div></div>
        <div class="col-lg-4"><div class="card shadow-sm h-100"><div class="card-body"><h2 class="h6 text-uppercase text-muted mb-2">Turno</h2><p class="mb-0"><?= e($course['shift']) ?></p></div></div></div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="h5 mb-3">Perfil de formacao</h2>
            <ul class="mb-0">
                <li>Fundamentos teoricos e praticos da area.</li>
                <li>Projetos integradores com ensino, pesquisa e extensao.</li>
                <li>Atuacao profissional alinhada a demandas sociais e tecnologicas.</li>
            </ul>
        </div>
    </div>
</div>
<?php page_footer(); ?>
