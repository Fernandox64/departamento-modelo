<?php
declare(strict_types=1);

require __DIR__ . '/../includes/config.php';

$page = page_data('informacoes-uteis');
page_header('Informacoes Uteis');
?>
<div class="container py-4">
    <h1 class="section-title h3 mb-2"><?= e($page['title']) ?></h1>
    <p class="text-secondary mb-4"><?= e($page['summary']) ?></p>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Calendario academico</h2>
                    <p class="mb-3">Use este bloco para publicar o calendario academico vigente e os links oficiais do semestre.</p>
                    <ul class="mb-0">
                        <li><a href="#">Calendario 2026/1</a></li>
                        <li><a href="#">Calendario 2026/2</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Atendimento academico</h2>
                    <ul class="mb-3">
                        <li>Secretaria: apoio para disciplinas, documentos e fluxos internos.</li>
                        <li>Colegiado: deliberacoes sobre casos academicos e equivalencias.</li>
                        <li>Setor institucional: registro academico e requerimentos.</li>
                    </ul>
                    <p class="mb-1"><strong>Contato:</strong> <?= e(SITE_EMAIL) ?> | <?= e(SITE_PHONE) ?></p>
                    <p class="mb-0 text-muted">Atualize os contatos conforme a estrutura do seu departamento.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Orientacoes de matricula</h2>
                    <ol class="mb-0">
                        <li>Consultar janela de matricula e regras do semestre.</li>
                        <li>Conferir pre-requisitos e possiveis choques de horario.</li>
                        <li>Solicitar ajustes dentro do prazo oficial.</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Aproveitamento e equivalencias</h2>
                    <ul class="mb-0">
                        <li>Protocolar pedido com historico e ementas.</li>
                        <li>Aguardar analise tecnica do colegiado.</li>
                        <li>Conferir registro final no historico academico.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="h5 mb-3">Monitorias e editais</h2>
            <p class="mb-3">Publique aqui os cronogramas e editais internos de monitoria e apoio estudantil.</p>
            <a class="btn btn-outline-primary btn-sm" href="/noticias/editais.php">Ver editais</a>
        </div>
    </div>
</div>
<?php page_footer(); ?>
