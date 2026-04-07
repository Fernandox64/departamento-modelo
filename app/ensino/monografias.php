<?php
declare(strict_types=1);

require __DIR__ . '/../includes/config.php';

page_header('Monografias');
?>
<div class="container py-4">
    <h1 class="section-title h3 mb-3">Monografias e TCC</h1>
    <p class="lead mb-4">Modelo para orientar etapas de trabalho de conclusao de curso.</p>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4"><div class="card-body"><h2 class="h5 mb-3">Fluxo sugerido</h2><ol class="mb-0"><li>Definir tema e orientador.</li><li>Aprovar plano de trabalho.</li><li>Executar desenvolvimento e redacao.</li><li>Solicitar banca e defender.</li><li>Entregar versao final.</li></ol></div></div>
            <div class="card shadow-sm"><div class="card-body"><h2 class="h5 mb-3">Defesas publicadas</h2><p class="mb-3">As bancas e avisos podem ser divulgados no modulo de Defesas.</p><a class="btn btn-outline-primary btn-sm" href="/noticias/defesas.php">Ver Defesas</a></div></div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4"><div class="card-body"><h2 class="h6 text-uppercase text-muted mb-3">Links uteis</h2><div class="d-grid gap-2"><a class="btn btn-outline-primary btn-sm" href="/ensino/ciencia-computacao.php">Pagina da Graduacao</a><a class="btn btn-outline-primary btn-sm" href="/ensino/horarios-de-aula.php">Horarios de Aula</a><a class="btn btn-outline-primary btn-sm" href="/contato/index.php">Contato da secretaria</a></div></div></div>
            <div class="card news-card"><div class="card-body"><h2 class="h6 text-uppercase text-muted mb-3">Suporte</h2><p class="mb-2"><strong>E-mail:</strong> <?= e(SITE_EMAIL) ?></p><p class="mb-0"><strong>Telefone:</strong> <?= e(SITE_PHONE) ?></p></div></div>
        </div>
    </div>
</div>
<?php page_footer(); ?>
