<?php
require __DIR__ . '/../includes/config.php';
page_header('Mapa do campus');
?>
<div class="container py-4">
    <h1 class="section-title h3 mb-3">Mapa do campus</h1>
    <p class="text-muted mb-4">Espaco para materiais de orientacao de acesso, blocos e unidades do departamento.</p>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card news-card overflow-hidden">
                <img class="news-card-cover" src="/assets/images/carousel/ufop-campus-map.png" alt="Mapa de referencia" style="height:430px;object-fit:cover;">
                <div class="card-body">
                    <h2 class="h5 mb-2">Mapa principal</h2>
                    <p class="mb-3">Substitua esta imagem pelo mapa oficial do campus ou unidade academica.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h2 class="h5 mb-3">Acesso rapido</h2>
                    <p class="text-muted mb-3">Links de navegacao para visitantes, alunos e novos ingressantes.</p>
                    <div class="d-grid gap-2 mt-auto">
                        <a class="btn btn-primary btn-sm" href="https://maps.google.com/maps?q=-20.396606,-43.509722" target="_blank" rel="noopener">Google Maps</a>
                        <a class="btn btn-outline-primary btn-sm" href="/decom/localizacao.php">Localizacao</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php page_footer(); ?>
