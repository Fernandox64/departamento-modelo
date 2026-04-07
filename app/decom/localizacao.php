<?php
require __DIR__ . '/../includes/config.php';
page_header('Localizacao');
?>
<div class="container py-4">
    <h1 class="section-title h3 mb-3">Localizacao do departamento</h1>
    <p class="text-muted mb-4">Pagina modelo para endereco, referencia de acesso e canais de atendimento presencial.</p>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card news-card overflow-hidden">
                <div class="ratio ratio-16x9">
                    <iframe
                        src="https://www.google.com/maps?q=-20.396606,-43.509722&z=17&output=embed"
                        title="Mapa institucional"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                    ></iframe>
                </div>
                <div class="card-body">
                    <h2 class="h5 mb-2">Endereco</h2>
                    <p class="mb-3"><?= e(SITE_ADDRESS) ?></p>
                    <a class="btn btn-outline-primary btn-sm" href="https://maps.google.com/maps?q=-20.396606,-43.509722" target="_blank" rel="noopener">Abrir no Google Maps</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5 mb-3">Atendimento</h2>
                    <p class="mb-2"><strong>E-mail:</strong> <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></p>
                    <p class="mb-2"><strong>Telefone:</strong> <?= e(SITE_PHONE) ?></p>
                    <p class="mb-0 text-muted">Atualize com os horarios oficiais da secretaria.</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Links uteis</h2>
                    <div class="d-grid gap-2">
                        <a class="btn btn-outline-primary btn-sm" href="/contato/index.php">Contato</a>
                        <a class="btn btn-outline-primary btn-sm" href="/decom/mapa-campus.php">Mapa do campus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php page_footer(); ?>
