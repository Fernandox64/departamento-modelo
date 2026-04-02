<?php
require __DIR__ . '/includes/config.php';

$news = array_slice(demo_news(), 0, 6);
$editais = array_slice(demo_editais(), 0, 6);
$defesas = demo_defesas();
$jobs = demo_jobs();

page_header('Inicio');
?>
<section class="hero py-6">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <span class="badge badge-light mb-3">Departamento de Computacao</span>
                <h1 class="display-3 text-white">Portal institucional do DECOM</h1>
                <p class="lead text-white">Noticias, editais, defesas e vagas publicados com atualizacao dinamica.</p>
                <div class="mt-4">
                    <a class="btn btn-white btn-icon mb-2 mr-2" href="/noticias/index.php">
                        <span class="btn-inner--text">Ultimas noticias</span>
                    </a>
                    <a class="btn btn-outline-white mb-2 mr-2" href="/noticias/editais.php">Editais</a>
                    <a class="btn btn-outline-white mb-2" href="/admin/dashboard.php">Area admin</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Acesso rapido</h2>
                        <div class="list-group">
                            <a class="list-group-item list-group-item-action" href="/pessoal/docentes.php">Docentes</a>
                            <a class="list-group-item list-group-item-action" href="/ensino/ciencia-computacao.php">Curso de Ciencia da Computacao</a>
                            <a class="list-group-item list-group-item-action" href="/ensino/inteligencia-artificial.php">Curso de Inteligencia Artificial</a>
                            <a class="list-group-item list-group-item-action" href="/cocic/index.php">COCIC</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section-lg pt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mb-5 mb-lg-0">
                <h2 class="section-title h4 mb-4">Noticias</h2>
                <div class="row">
                <?php foreach ($news as $item): ?>
                    <div class="col-md-6 mb-4">
                        <a class="card card-link news-card h-100" href="/noticias/ver.php?slug=<?= urlencode($item['slug']) ?>">
                            <img class="news-card-cover" src="<?= e(content_image($item)) ?>" alt="<?= e($item['title']) ?>">
                            <div class="card-body">
                                <span class="badge badge-primary"><?= e($item['category']) ?></span>
                                <h3 class="h5 mt-2"><?= e($item['title']) ?></h3>
                                <p class="news-summary mb-0"><?= e($item['summary']) ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>

            <div class="col-lg-4">
            <?php foreach (['Editais' => $editais, 'Defesas' => $defesas, 'Estagios e Empregos' => $jobs] as $title => $items): ?>
                <div class="card shadow border-0 mb-4">
                    <div class="card-body">
                        <h2 class="h5 mb-3"><?= e($title) ?></h2>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($items as $item): ?>
                                <li class="list-group-item px-0 py-2">
                                    <a href="/noticias/ver.php?slug=<?= urlencode($item['slug']) ?>"><?= e($item['title']) ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php page_footer(); ?>
