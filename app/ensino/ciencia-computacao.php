<?php
declare(strict_types=1);

require __DIR__ . '/../includes/config.php';

$courseA = course_data('ciencia-da-computacao');
$courseB = course_data('inteligencia-artificial');
$activeTab = (string)($_GET['curso'] ?? 'cc');
if (!in_array($activeTab, ['cc', 'ia'], true)) {
    $activeTab = 'cc';
}

page_header('Graduacao - Cursos');
?>
<div class="container py-4">
    <h1 class="section-title h3 mb-3">Graduacao</h1>
    <p class="lead mb-4">Pagina modelo para apresentar os cursos de graduacao do departamento.</p>

    <ul class="nav nav-tabs mb-4" id="graduacaoTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link<?= $activeTab === 'cc' ? ' active' : '' ?>" id="tab-cc" data-bs-toggle="tab" data-bs-target="#pane-cc" type="button" role="tab" aria-controls="pane-cc" aria-selected="<?= $activeTab === 'cc' ? 'true' : 'false' ?>">
                <?= e($courseA['name']) ?>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link<?= $activeTab === 'ia' ? ' active' : '' ?>" id="tab-ia" data-bs-toggle="tab" data-bs-target="#pane-ia" type="button" role="tab" aria-controls="pane-ia" aria-selected="<?= $activeTab === 'ia' ? 'true' : 'false' ?>">
                <?= e($courseB['name']) ?>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="graduacaoTabsContent">
        <div class="tab-pane fade<?= $activeTab === 'cc' ? ' show active' : '' ?>" id="pane-cc" role="tabpanel" aria-labelledby="tab-cc" tabindex="0">
            <div class="card shadow-sm mb-4"><div class="card-body"><h2 class="h5 mb-3"><?= e($courseA['name']) ?></h2><p class="mb-2"><?= e($courseA['summary']) ?></p><p class="mb-0"><?= e($courseA['content']) ?></p></div></div>
            <div class="row g-3 mb-4">
                <div class="col-md-4"><div class="card shadow-sm h-100"><div class="card-body"><strong>Modalidade</strong><p class="mb-0"><?= e($courseA['modality']) ?></p></div></div></div>
                <div class="col-md-4"><div class="card shadow-sm h-100"><div class="card-body"><strong>Duracao</strong><p class="mb-0"><?= e($courseA['duration']) ?></p></div></div></div>
                <div class="col-md-4"><div class="card shadow-sm h-100"><div class="card-body"><strong>Turno</strong><p class="mb-0"><?= e($courseA['shift']) ?></p></div></div></div>
            </div>
        </div>

        <div class="tab-pane fade<?= $activeTab === 'ia' ? ' show active' : '' ?>" id="pane-ia" role="tabpanel" aria-labelledby="tab-ia" tabindex="0">
            <div class="card shadow-sm mb-4"><div class="card-body"><h2 class="h5 mb-3"><?= e($courseB['name']) ?></h2><p class="mb-2"><?= e($courseB['summary']) ?></p><p class="mb-0"><?= e($courseB['content']) ?></p></div></div>
            <div class="row g-3 mb-4">
                <div class="col-md-4"><div class="card shadow-sm h-100"><div class="card-body"><strong>Modalidade</strong><p class="mb-0"><?= e($courseB['modality']) ?></p></div></div></div>
                <div class="col-md-4"><div class="card shadow-sm h-100"><div class="card-body"><strong>Duracao</strong><p class="mb-0"><?= e($courseB['duration']) ?></p></div></div></div>
                <div class="col-md-4"><div class="card shadow-sm h-100"><div class="card-body"><strong>Turno</strong><p class="mb-0"><?= e($courseB['shift']) ?></p></div></div></div>
            </div>
        </div>
    </div>

    <div class="alert alert-info mb-0">Use esta estrutura como base e personalize os blocos por curso, campus e coordenacao.</div>
</div>
<?php page_footer(); ?>
