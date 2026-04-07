<?php
require __DIR__ . '/includes/config.php';

$news = array_slice(demo_news(), 0, 6);
$editais = array_slice(demo_editais(), 0, 6);
$defesas = demo_defesas();
$jobs = demo_jobs();
$menuGraduacao = primary_menu_item('graduacao');
$menuPosGraduacao = primary_menu_item('pos_graduacao');
$heroSlides = hero_carousel_get();
$showStudentCalendar = site_setting_get('show_student_calendar', '1') !== '0';
$studentCalendar = $showStudentCalendar ? ufop_student_calendar() : null;

page_header('Inicio');
?>
<section class="hero py-5">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-7">
                <div id="heroCarousel" class="carousel slide hero-carousel shadow-lg" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <?php foreach ($heroSlides as $idx => $slide): ?>
                            <button
                                type="button"
                                data-bs-target="#heroCarousel"
                                data-bs-slide-to="<?= e((string)$idx) ?>"
                                class="<?= $idx === 0 ? 'active' : '' ?>"
                                <?= $idx === 0 ? 'aria-current="true"' : '' ?>
                                aria-label="Slide <?= e((string)($idx + 1)) ?>">
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <div class="carousel-inner rounded-4 overflow-hidden">
                        <?php foreach ($heroSlides as $idx => $slide): ?>
                            <div class="carousel-item<?= $idx === 0 ? ' active' : '' ?>">
                                <img src="<?= e((string)$slide['image']) ?>" class="d-block w-100 hero-slide-image" alt="<?= e((string)$slide['title']) ?>">
                                <div class="carousel-caption text-start">
                                    <span class="badge hero-badge mb-2"><?= e((string)$slide['badge']) ?></span>
                                    <?php if ($idx === 0): ?>
                                        <h1 class="display-6 fw-bold mb-2"><?= e((string)$slide['title']) ?></h1>
                                    <?php else: ?>
                                        <h2 class="h2 fw-bold mb-2"><?= e((string)$slide['title']) ?></h2>
                                    <?php endif; ?>
                                    <p class="lead mb-<?= $idx === 0 ? '3' : '0' ?>"><?= e((string)$slide['text']) ?></p>
                                    <?php if ($idx === 0): ?>
                                        <div class="d-flex flex-wrap gap-2">
                                            <a class="btn btn-light" href="/noticias/index.php">Ultimas noticias</a>
                                            <a class="btn btn-outline-light" href="/noticias/editais.php">Editais</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Proximo</span>
                    </button>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="h5">Acesso rapido</h2>
                        <div class="list-group list-group-flush">
                            <a class="list-group-item list-group-item-action bg-transparent text-white" href="/pessoal/docentes.php">Docentes</a>
                            <a class="list-group-item list-group-item-action bg-transparent text-white" href="/ensino/ciencia-computacao.php">Curso de Graduacao 1</a>
                            <a class="list-group-item list-group-item-action bg-transparent text-white" href="/ensino/inteligencia-artificial.php">Curso de Graduacao 2</a>
                            <a class="list-group-item list-group-item-action bg-transparent text-white" href="<?= e((string)$menuGraduacao['url']) ?>"><?= e((string)$menuGraduacao['label']) ?></a>
                            <a class="list-group-item list-group-item-action bg-transparent text-white" href="<?= e((string)$menuPosGraduacao['url']) ?>"><?= e((string)$menuPosGraduacao['label']) ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-8">
            <h2 class="section-title h4 mb-3">Noticias</h2>
            <div class="row g-3">
                <?php foreach ($news as $item): ?>
                    <div class="col-md-6">
                        <a class="card card-link h-100 shadow-sm overflow-hidden" href="/noticias/ver.php?slug=<?= urlencode($item['slug']) ?>">
                            <img class="news-card-cover" src="<?= e(content_image($item)) ?>" alt="<?= e($item['title']) ?>">
                            <div class="card-body">
                                <span class="badge text-bg-primary"><?= e($item['category']) ?></span>
                                <h3 class="h5 mt-2"><?= e($item['title']) ?></h3>
                                <p class="text-muted mb-0"><?= e($item['summary']) ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4 side-widget">
                <div class="card-body">
                    <h2 class="h5">Acesso do Aluno</h2>
                    <div class="d-grid gap-2">
                        <a class="btn btn-primary btn-sm" href="/pessoal/atendimento-docentes.php">Atendimento Docentes</a>
                        <a class="btn btn-outline-secondary btn-sm" href="/ensino/horarios-de-aula.php">Horarios de Aula</a>
                    </div>
                    <?php if ($showStudentCalendar && is_array($studentCalendar)): ?>
                    <div class="calendar-01 mt-3">
                        <div class="calendar-wrap card border-0 shadow-sm">
                            <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button type="button" class="btn btn-link p-0 calendar-nav-disabled" aria-label="Mes anterior">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <h3 class="h5 mb-0 calendar-month-title"><?= e((string)$studentCalendar['month_name']) ?> <?= e((string)$studentCalendar['year']) ?></h3>
                                <button type="button" class="btn btn-link p-0 calendar-nav-disabled" aria-label="Proximo mes">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small text-muted"><?= e((string)($studentCalendar['source_label'] ?? '')) ?></span>
                                <a href="<?= e((string)$studentCalendar['source_url']) ?>" class="btn btn-outline-secondary btn-sm py-1 px-2" target="_blank" rel="noopener">Abrir PROGRAD</a>
                            </div>
                            <?php
                            $calendarDayMap = (array)($studentCalendar['days'] ?? []);
                            $calendarDaysInMonth = (int)($studentCalendar['days_in_month'] ?? 0);
                            $selectedCalendarDay = 0;
                            $todayDay = ((int)date('Y') === (int)$studentCalendar['year'] && (int)date('n') === (int)$studentCalendar['month']) ? (int)date('j') : 0;
                            if ($todayDay > 0) {
                                $selectedCalendarDay = $todayDay;
                            } else {
                                for ($dd = 1; $dd <= $calendarDaysInMonth; $dd++) {
                                    if (!empty($calendarDayMap[$dd])) {
                                        $selectedCalendarDay = $dd;
                                        break;
                                    }
                                }
                                if ($selectedCalendarDay === 0) {
                                    $selectedCalendarDay = $todayDay > 0 ? $todayDay : 1;
                                }
                            }
                            ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered align-middle text-center mb-3 student-calendar-bs">
                                    <thead>
                                        <tr>
                                            <?php foreach ($studentCalendar['weekdays'] as $w): ?>
                                                <th class="small fw-semibold"><?= e((string)$w) ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $day = 1;
                                        $firstDow = (int)$studentCalendar['first_dow'];
                                        $daysInMonth = (int)$studentCalendar['days_in_month'];
                                        for ($row = 0; $row < 6 && $day <= $daysInMonth; $row++):
                                        ?>
                                            <tr>
                                                <?php for ($col = 0; $col < 7; $col++): ?>
                                                    <?php if (($row === 0 && $col < $firstDow) || $day > $daysInMonth): ?>
                                                        <td class="student-day-empty"></td>
                                                    <?php else: ?>
                                                        <?php
                                                        $items = (array)($studentCalendar['days'][$day] ?? []);
                                                        $hasHoliday = false;
                                                        $hasEvent = false;
                                                        foreach ($items as $it) {
                                                            $t = (string)($it['type'] ?? '');
                                                            if ($t === 'holiday') {
                                                                $hasHoliday = true;
                                                            } elseif ($t === 'event') {
                                                                $hasEvent = true;
                                                            }
                                                        }
                                                        ?>
                                                        <td class="<?= $hasHoliday ? 'student-day-holiday' : '' ?> <?= $hasEvent ? 'student-day-event' : '' ?> <?= $day === $todayDay ? 'student-day-today' : '' ?>">
                                                            <button type="button"
                                                                class="btn btn-link btn-sm p-0 text-decoration-none student-day-btn <?= $day === $selectedCalendarDay ? 'is-selected' : '' ?>"
                                                                data-calendar-day="<?= e((string)$day) ?>">
                                                                <span><?= e((string)$day) ?></span>
                                                            </button>
                                                            <?php if ($hasHoliday || $hasEvent): ?>
                                                                <span class="student-day-markers">
                                                                    <?php if ($hasHoliday): ?><i class="marker-holiday"></i><?php endif; ?>
                                                                    <?php if ($hasEvent): ?><i class="marker-event"></i><?php endif; ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <?php $day++; ?>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex gap-2 flex-wrap mb-2">
                                <span class="badge text-bg-danger">Feriado</span>
                                <span class="badge text-bg-warning">Evento</span>
                            </div>
                            <div class="small fw-semibold mb-1 text-secondary">Detalhes do dia <span id="calendar-selected-day-label"><?= e((string)$selectedCalendarDay) ?></span></div>
                            <ul class="list-group list-group-flush small student-calendar-feed mb-0" id="calendar-selected-day-list">
                                <?php
                                $selectedItems = (array)($calendarDayMap[$selectedCalendarDay] ?? []);
                                if (empty($selectedItems)):
                                ?>
                                    <li class="list-group-item px-0 py-1 bg-transparent border-light">Sem eventos/feriados neste dia.</li>
                                <?php else: ?>
                                    <?php foreach ($selectedItems as $it): ?>
                                        <li class="list-group-item px-0 py-1 bg-transparent border-light">
                                            <?php if (((string)($it['type'] ?? '')) === 'holiday'): ?>
                                                <span class="badge text-bg-danger">Feriado</span>
                                            <?php else: ?>
                                                <span class="badge text-bg-warning">Evento do departamento</span>
                                            <?php endif; ?>
                                            <?= e((string)($it['title'] ?? '')) ?>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                            <script type="application/json" id="calendar-day-data"><?= e((string)json_encode($calendarDayMap, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?></script>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php foreach (['Editais' => $editais, 'Defesas' => $defesas, 'Estagios e Empregos' => $jobs] as $title => $items): ?>
                <div class="card shadow-sm mb-4 side-widget">
                    <div class="card-body">
                        <h2 class="h5"><?= e($title) ?></h2>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($items as $item): ?>
                                <li class="list-group-item px-0">
                                    <a class="side-widget-link" href="/noticias/ver.php?slug=<?= urlencode($item['slug']) ?>"><?= e($item['title']) ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card news-card mt-4">
        <div class="card-body d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h2 class="h4 mb-2">Quero ingressar em um curso de graduacao</h2>
                <p class="mb-0 text-muted">
                    Veja um apanhado geral do curso com descricao, eixos da grade curricular,
                    avaliacao institucional e orientacoes de ingresso.
                </p>
            </div>
            <a class="btn btn-primary" href="/ensino/ciencia-computacao.php">Ver guia do ingressante</a>
        </div>
    </div>

    <div class="card news-card mt-4">
        <div class="card-body d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h2 class="h4 mb-2">Pos-graduacao do departamento</h2>
                <p class="mb-0 text-muted">
                    As noticias e editais da pos agora ficam em paginas separadas, exclusivas da pos-graduacao.
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a class="btn btn-outline-primary btn-sm" href="/ensino/pos-noticias.php">Noticias da Pos</a>
                <a class="btn btn-outline-danger btn-sm" href="/ensino/pos-editais.php">Editais da Pos</a>
                <a class="btn btn-primary btn-sm" href="/ensino/pos-graduacao.php">Pagina da Pos</a>
            </div>
        </div>
    </div>
</div>
<?php if ($showStudentCalendar && is_array($studentCalendar)): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var dataEl = document.getElementById('calendar-day-data');
    var dayLabel = document.getElementById('calendar-selected-day-label');
    var list = document.getElementById('calendar-selected-day-list');
    if (!dataEl || !dayLabel || !list) {
        return;
    }
    var map = {};
    try {
        map = JSON.parse(dataEl.textContent || '{}') || {};
    } catch (e) {
        map = {};
    }
    function renderDay(day) {
        dayLabel.textContent = String(day);
        var items = map[String(day)] || map[day] || [];
        list.innerHTML = '';
        if (!Array.isArray(items) || items.length === 0) {
            var liEmpty = document.createElement('li');
            liEmpty.className = 'list-group-item px-0 py-1 bg-transparent border-light';
            liEmpty.textContent = 'Sem eventos/feriados neste dia.';
            list.appendChild(liEmpty);
            return;
        }
        items.forEach(function (it) {
            var li = document.createElement('li');
            li.className = 'list-group-item px-0 py-1 bg-transparent border-light';
            var badge = document.createElement('span');
            if ((it.type || '') === 'holiday') {
                badge.className = 'badge text-bg-danger';
                badge.textContent = 'Feriado';
            } else {
                badge.className = 'badge text-bg-warning';
                badge.textContent = 'Evento do departamento';
            }
            li.appendChild(badge);
            li.appendChild(document.createTextNode(' ' + (it.title || '')));
            list.appendChild(li);
        });
    }
    document.querySelectorAll('.student-day-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.student-day-btn.is-selected').forEach(function (b) {
                b.classList.remove('is-selected');
            });
            btn.classList.add('is-selected');
            var day = parseInt(btn.getAttribute('data-calendar-day') || '1', 10);
            renderDay(day);
        });
    });
});
</script>
<?php endif; ?>
<?php page_footer(); ?>
