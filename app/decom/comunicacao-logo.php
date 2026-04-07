<?php
require __DIR__ . '/../includes/config.php';
page_header('Comunicacao e identidade visual');
?>
<div class="container py-4">
    <h1 class="section-title h3 mb-4">Comunicacao e identidade visual</h1>
    <p class="lead mb-4">Espaco para centralizar logotipos, materiais de divulgacao e orientacoes de uso da marca do departamento.</p>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5 mb-3">Logotipo institucional</h2>
                    <div class="border rounded p-4 bg-light text-center mb-3">
                        <div class="display-6 fw-bold text-secondary"><?= e(SITE_SIGLA) ?></div>
                        <p class="text-muted mb-0">Substitua por uma imagem oficial do departamento.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="btn btn-primary btn-sm" href="#">Baixar logo (SVG)</a>
                        <a class="btn btn-outline-primary btn-sm" href="#">Baixar logo (PNG)</a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Diretrizes de uso</h2>
                    <ul class="mb-0">
                        <li>Manter proporcao e area de respiro da marca.</li>
                        <li>Evitar mudancas de cor, tipografia e elementos oficiais.</li>
                        <li>Usar versoes aprovadas para materiais digitais e impressos.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5 mb-3">Contato</h2>
                    <p class="mb-2"><strong>E-mail:</strong> <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></p>
                    <p class="mb-0 text-muted">Canal para alinhamento de campanhas, eventos e comunicados institucionais.</p>
                </div>
            </div>

            <div class="card news-card">
                <div class="card-body">
                    <h2 class="h5 mb-3">Canais oficiais</h2>
                    <div class="d-grid gap-2">
                        <a class="btn btn-outline-primary btn-sm" href="#">Site institucional</a>
                        <a class="btn btn-outline-primary btn-sm" href="#">Instagram</a>
                        <a class="btn btn-outline-primary btn-sm" href="#">YouTube</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php page_footer(); ?>
