<?php header('Content-Type: text/html; charset=UTF-8'); ?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle ?? SITE_NAME) ?></title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/argon-design-system-free@1.2.2/assets/css/argon.min.css" rel="stylesheet">
<style>
body{background:#f6f9fc}
.topbar{font-size:.9rem;background:linear-gradient(90deg,#172b4d,#1a5d9b);color:#fff}
.navbar-main{background:#fff;box-shadow:0 10px 25px rgba(50,50,93,.1)}
.navbar-main .nav-link{font-weight:600;color:#344767!important}
.navbar-main .dropdown-menu{border:0;box-shadow:0 18px 35px rgba(50,50,93,.18)}
.section-title{position:relative;padding-left:1rem}
.section-title:before{content:"";position:absolute;left:0;top:.25rem;bottom:.25rem;width:4px;border-radius:999px;background:linear-gradient(180deg,#5e72e4,#11cdef)}
.hero{background:linear-gradient(150deg,#5e72e4 0%,#825ee4 45%,#11cdef 100%);color:#fff;border-radius:0 0 24px 24px;box-shadow:0 18px 32px rgba(50,50,93,.2)}
.card-link{text-decoration:none;color:inherit}
.news-card{border:0;border-radius:16px;box-shadow:0 12px 26px rgba(50,50,93,.1);transition:transform .2s ease,box-shadow .2s ease;background:#fff}
.news-card:hover{transform:translateY(-4px);box-shadow:0 18px 34px rgba(50,50,93,.16)}
.news-card-cover{width:100%;height:185px;object-fit:cover;border-radius:16px 16px 0 0;background:#e9ecef}
.news-card .card-body{padding:1.2rem}
.news-summary{color:#525f7f;line-height:1.55;min-height:48px}
.news-cta{font-weight:700;color:#5e72e4}
@media (max-width:991px){.hero{border-radius:0}}
</style>
</head>
<body>
<div class="topbar py-2">
  <div class="container d-flex flex-wrap justify-content-between gap-2">
    <div><?= e(SITE_UNIVERSITY) ?> - <?= e(SITE_SIGLA) ?></div>
    <div><?= e(SITE_PHONE) ?> - <?= e(SITE_EMAIL) ?></div>
  </div>
</div>

<nav class="navbar navbar-main navbar-expand-xl navbar-light sticky-top">
  <div class="container">
    <a class="navbar-brand mr-lg-5 font-weight-bold" href="/">DECOM</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menuMain" aria-controls="menuMain" aria-expanded="false" aria-label="Alternar navegacao">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="menuMain" class="collapse navbar-collapse">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item"><a class="nav-link" href="/">Inicio</a></li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">DECOM</a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="/decom/quem-somos.php">Quem somos</a>
            <a class="dropdown-item" href="/decom/comunicacao-logo.php">Comunicacao e logo</a>
            <a class="dropdown-item" href="/decom/localizacao.php">Localizacao</a>
            <a class="dropdown-item" href="/decom/mapa-campus.php">Mapa do campus</a>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Noticias e Eventos</a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="/noticias/index.php">Noticias</a>
            <a class="dropdown-item" href="/noticias/editais.php">Editais</a>
            <a class="dropdown-item" href="/noticias/defesas.php">Defesas</a>
            <a class="dropdown-item" href="/noticias/estagios-empregos.php">Estagios e Empregos</a>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Pessoal</a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="/pessoal/docentes.php">Docentes</a>
            <a class="dropdown-item" href="/pessoal/funcionarios.php">Funcionarios</a>
            <a class="dropdown-item" href="/pessoal/planos-de-trabalho.php">Planos de Trabalho</a>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Ensino</a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="/ensino/ciencia-computacao.php">Ciencia da Computacao</a>
            <a class="dropdown-item" href="/ensino/inteligencia-artificial.php">Inteligencia Artificial</a>
            <a class="dropdown-item" href="/ensino/horarios-de-aula.php">Horarios de Aula</a>
            <a class="dropdown-item" href="/ensino/informacoes-uteis.php">Informacoes Uteis</a>
            <a class="dropdown-item" href="/ensino/monografias.php">Monografias</a>
          </div>
        </li>

        <li class="nav-item"><a class="nav-link" href="/cocic/index.php">COCIC</a></li>
        <li class="nav-item"><a class="nav-link" href="/pesquisa/index.php">Pesquisa</a></li>
        <li class="nav-item"><a class="nav-link" href="/extensao/index.php">Extensao</a></li>
        <li class="nav-item"><a class="nav-link" href="/contato/index.php">Contato</a></li>
      </ul>

      <div class="d-flex gap-2">
        <a class="btn btn-outline-primary btn-sm" href="/health.php">Health</a>
        <a class="btn btn-primary btn-sm" href="/admin/login.php">Admin</a>
      </div>
    </div>
  </div>
</nav>
