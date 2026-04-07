<?php
declare(strict_types=1);

require __DIR__ . '/../includes/config.php';

$projects = research_projects_data();
$slug = trim((string)($_GET['slug'] ?? ''));
$selected = null;
if ($slug !== '') {
    foreach ($projects as $item) {
        if ((string)$item['slug'] === $slug) {
            $selected = $item;
            break;
        }
    }
}

page_header($selected ? (string)$selected['title'] : 'Projetos de Pesquisa e Extensao');
?>
<div class="container py-4">
    <?php if ($selected): ?>
        <?php $isExt = (($selected['project_type'] ?? '') === 'extensao'); ?>
        <a class="btn btn-outline-secondary btn-sm mb-3" href="/pesquisa/projetos.php">Voltar para projetos</a>
        <div class="card shadow-sm">
            <div class="card-body">
                <span class="badge <?= $isExt ? 'text-bg-secondary' : 'text-bg-primary' ?> mb-2"><?= $isExt ? 'Extensao' : 'Pesquisa' ?></span>
                <h1 class="h3 mb-3"><?= e((string)$selected['title']) ?></h1>
                <p class="lead"><?= e((string)($selected['summary'] ?? '')) ?></p>
                <?php if (!empty($selected['coordinator'])): ?>
                    <p><strong>Coordenacao:</strong> <?= e((string)$selected['coordinator']) ?></p>
                <?php endif; ?>
                <?php if (!empty($selected['site_url'])): ?>
                    <a class="btn btn-primary btn-sm" href="<?= e((string)$selected['site_url']) ?>" target="_blank" rel="noopener">Site do projeto</a>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <h1 class="section-title h3 mb-2">Projetos de Pesquisa e Extensao</h1>
        <p class="text-muted mb-4">Catalogo de iniciativas do departamento com pagina propria para cada projeto.</p>

        <div class="row g-4">
            <?php foreach ($projects as $project): ?>
                <?php $isExt = (($project['project_type'] ?? '') === 'extensao'); ?>
                <div class="col-md-6 col-xl-4">
                    <div class="card news-card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <span class="badge <?= $isExt ? 'text-bg-secondary' : 'text-bg-primary' ?> mb-2"><?= $isExt ? 'Extensao' : 'Pesquisa' ?></span>
                            <h2 class="h5 mb-2"><?= e((string)$project['title']) ?></h2>
                            <p class="news-summary mb-3"><?= e((string)$project['summary']) ?></p>
                            <?php if (!empty($project['coordinator'])): ?>
                                <p class="mb-3"><strong>Coordenacao:</strong> <?= e((string)$project['coordinator']) ?></p>
                            <?php endif; ?>
                            <div class="mt-auto d-flex flex-wrap gap-2">
                                <a class="btn btn-outline-primary btn-sm" href="/pesquisa/projetos.php?slug=<?= e((string)$project['slug']) ?>">Ver detalhes</a>
                                <?php if (!empty($project['site_url'])): ?>
                                    <a class="btn btn-outline-dark btn-sm" href="<?= e((string)$project['site_url']) ?>" target="_blank" rel="noopener">Site</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php page_footer(); ?>
