<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

const SITE_NAME = 'Departamento de Computação';
const SITE_SIGLA = 'DECOM';
const SITE_UNIVERSITY = 'Universidade Federal de Ouro Preto';
const SITE_EMAIL = 'decom@ufop.edu.br';
const SITE_PHONE = '+55 31 3559-1692';
const SITE_ADDRESS = 'Campus Morro do Cruzeiro, Ouro Preto - MG';

const ADMIN_MAX_LOGIN_ATTEMPTS = 5;
const ADMIN_LOCKOUT_SECONDS = 900;

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $host = getenv('DB_HOST') ?: 'db';
    $port = getenv('DB_PORT') ?: '3306';
    $database = getenv('DB_DATABASE') ?: 'newsdb';
    $username = getenv('DB_USERNAME') ?: 'newsuser';
    $password = getenv('DB_PASSWORD') ?: 'newspass';
    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 5,
    ]);
    return $pdo;
}
function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function sanitize_rich_text(string $html): string {
    $allowed = '<p><br><strong><b><em><i><u><ul><ol><li><a><h2><h3><h4><blockquote><img><table><thead><tbody><tr><td><th><hr>';
    $clean = strip_tags($html, $allowed);
    $clean = preg_replace('/\sstyle\s*=\s*("|\').*?\1/iu', '', $clean) ?? $clean;
    $clean = preg_replace('/\son\w+\s*=\s*("|\').*?\1/iu', '', $clean) ?? $clean;
    $clean = preg_replace('/href\s*=\s*("|\')\s*javascript:[^"\']*\1/iu', 'href="#"', $clean) ?? $clean;
    return trim($clean);
}
function render_rich_text(string $content): string {
    $safe = sanitize_rich_text($content);
    if ($safe === '') {
        return '';
    }
    $hasTag = preg_match('/<\s*[a-z][^>]*>/i', $safe) === 1;
    if (!$hasTag) {
        return nl2br(e($safe));
    }
    return $safe;
}
function page_header(string $title): void { $pageTitle = $title; require __DIR__ . '/header.php'; }
function page_footer(): void { require __DIR__ . '/footer.php'; }
function is_admin_logged_in(): bool { return !empty($_SESSION['admin_ok']); }
function redirect(string $path): void { header("Location: {$path}"); exit; }
function require_admin(): void { if (!is_admin_logged_in()) { redirect('/admin/login.php'); } }
function admin_email_config(): string {
    return trim((string)(getenv('ADMIN_EMAIL') ?: ''));
}
function admin_password_hash_config(): string {
    return trim((string)(getenv('ADMIN_PASSWORD_HASH') ?: ''));
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return (string)$_SESSION['csrf_token'];
}
function is_valid_csrf_token(?string $token): bool {
    if (!is_string($token) || $token === '' || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals((string)$_SESSION['csrf_token'], $token);
}
function admin_is_login_locked(): bool {
    $lockUntil = (int)($_SESSION['admin_lock_until'] ?? 0);
    return $lockUntil > time();
}
function admin_register_login_failure(): void {
    $attempts = (int)($_SESSION['admin_login_attempts'] ?? 0) + 1;
    $_SESSION['admin_login_attempts'] = $attempts;
    if ($attempts >= ADMIN_MAX_LOGIN_ATTEMPTS) {
        $_SESSION['admin_lock_until'] = time() + ADMIN_LOCKOUT_SECONDS;
    }
}
function admin_clear_login_failures(): void {
    unset($_SESSION['admin_login_attempts'], $_SESSION['admin_lock_until']);
}
function admin_login(string $email, string $password): bool {
    $adminEmail = admin_email_config();
    $adminPasswordHash = admin_password_hash_config();
    if ($adminEmail === '' || $adminPasswordHash === '') {
        return false;
    }
    $isValidEmail = hash_equals($adminEmail, $email);
    $isValidPassword = password_verify($password, $adminPasswordHash);
    if (!$isValidEmail || !$isValidPassword || admin_is_login_locked()) {
        return false;
    }
    session_regenerate_id(true);
    $_SESSION['admin_ok'] = true;
    admin_clear_login_failures();
    return true;
}
function admin_logout(): void {
    $_SESSION = [];
    session_destroy();
}

function fetch_content_items(string $table): array {
    if (!in_array($table, ['news_items', 'edital_items', 'defesa_items', 'job_items'], true)) {
        return [];
    }
    $sql = "SELECT slug, title, summary, category, content, image FROM {$table} ORDER BY published_at DESC, id DESC";
    return db()->query($sql)->fetchAll();
}

function demo_news(): array {
    try {
        $items = fetch_content_items('news_items');
        if (!empty($items)) {
            return $items;
        }
    } catch (Throwable $e) {
        error_log('Failed loading news_items: ' . $e->getMessage());
    }
    return [];
}
function demo_editais(): array {
    try {
        $items = fetch_content_items('edital_items');
        if (!empty($items)) {
            return $items;
        }
    } catch (Throwable $e) {
        error_log('Failed loading edital_items: ' . $e->getMessage());
    }
    return [];
}
function demo_defesas(): array {
    try {
        $items = fetch_content_items('defesa_items');
        if (!empty($items)) {
            return $items;
        }
    } catch (Throwable $e) {
        error_log('Failed loading defesa_items: ' . $e->getMessage());
    }
    return [
      ['slug'=>'defesas-monografia-2026-1','title'=>'Defesas de monografia 2026/1','summary'=>'Agenda de bancas de monografia do semestre.','category'=>'Defesas','content'=>'Conteúdo demonstrativo para defesas.','image'=>'/assets/cards/noticia-pesquisa.svg']
    ];
}
function demo_jobs(): array {
    try {
        $items = fetch_content_items('job_items');
        if (!empty($items)) {
            return $items;
        }
    } catch (Throwable $e) {
        error_log('Failed loading job_items: ' . $e->getMessage());
    }
    return [
      ['slug'=>'vaga-estagio-web','title'=>'Vaga de estágio em desenvolvimento web','summary'=>'Empresa parceira busca estudante com noções de PHP e banco de dados.','category'=>'Carreiras','content'=>'Conteúdo demonstrativo para estágios e empregos.','image'=>'/assets/cards/noticia-portal.svg']
    ];
}
function find_demo_item(string $slug): ?array {
    foreach (array_merge(demo_news(), demo_editais(), demo_defesas(), demo_jobs()) as $item) {
        if ($item['slug'] === $slug) return $item;
    }
    return null;
}
function card_image_for_slug(string $slug): string {
    $map = [
        'portal-em-teste' => '/assets/cards/noticia-portal.svg',
        'horarios-de-aula-disponiveis' => '/assets/cards/noticia-horarios.svg',
        'grupo-de-pesquisa-abre-chamada' => '/assets/cards/noticia-pesquisa.svg',
        'edital-monitoria-2026-1' => '/assets/cards/edital-monitoria.svg',
        'edital-bolsas-extensao' => '/assets/cards/edital-extensao.svg',
        'qualificacao-mestrado-eduardo-henke-2026-03-26' => '/assets/cards/noticia-pesquisa.svg',
        'horario-aulas-decom-2026-1' => '/assets/cards/noticia-horarios.svg',
        'defesa-doutorado-guilherme-augusto-2026-03-20' => '/assets/cards/noticia-portal.svg',
        'inicio-matriculas-isoladas-ppgcc-2026-1' => '/assets/cards/edital-extensao.svg',
        'grade-disciplinas-matricula-2026-1' => '/assets/cards/edital-monitoria.svg',
        'horarios-monitorias-decom' => '/assets/cards/edital-monitoria.svg',
        'defesas-monografia-2026-1' => '/assets/cards/noticia-pesquisa.svg',
        'vaga-estagio-web' => '/assets/cards/noticia-portal.svg',
    ];
    return $map[$slug] ?? '/assets/cards/noticia-default.svg';
}
function content_image(array $item): string {
    $image = trim((string)($item['image'] ?? ''));
    if ($image !== '') {
        return $image;
    }
    return card_image_for_slug((string)($item['slug'] ?? ''));
}
function fetch_people_items(string $type): array {
    if (!in_array($type, ['docente', 'funcionario'], true)) {
        return [];
    }
    $sql = "SELECT slug, name, role_type, position, degree, website_url, lattes_url, email, phone, room, interests, bio, photo_url
            FROM people_items
            WHERE role_type = :role_type
            ORDER BY sort_order ASC, name ASC, id ASC";
    $stmt = db()->prepare($sql);
    $stmt->execute([':role_type' => $type]);
    return $stmt->fetchAll();
}
function person_initials(string $name): string {
    $name = trim($name);
    if ($name === '') {
        return 'DE';
    }
    $parts = preg_split('/\s+/', $name) ?: [];
    $first = mb_substr($parts[0] ?? '', 0, 1, 'UTF-8');
    $last = mb_substr($parts[count($parts) - 1] ?? '', 0, 1, 'UTF-8');
    $initials = mb_strtoupper($first . $last, 'UTF-8');
    return $initials !== '' ? $initials : 'DE';
}
function person_photo_placeholder(string $name): string {
    $palette = [
        ['#0f4c81', '#0f8ccf'],
        ['#1f6f5f', '#2bb673'],
        ['#6c3a9c', '#8b5cf6'],
        ['#7a2e2e', '#ef4444'],
        ['#3f3f46', '#71717a'],
    ];
    $index = abs(crc32($name)) % count($palette);
    [$from, $to] = $palette[$index];
    $initials = person_initials($name);
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="320" height="320" viewBox="0 0 320 320">'
        . '<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">'
        . '<stop offset="0%" stop-color="' . $from . '"/><stop offset="100%" stop-color="' . $to . '"/>'
        . '</linearGradient></defs>'
        . '<rect width="320" height="320" fill="url(#g)"/>'
        . '<text x="50%" y="53%" dominant-baseline="middle" text-anchor="middle"'
        . ' fill="#ffffff" font-family="Arial, Helvetica, sans-serif" font-size="108" font-weight="700">'
        . htmlspecialchars($initials, ENT_QUOTES, 'UTF-8')
        . '</text></svg>';
    return 'data:image/svg+xml;utf8,' . rawurlencode($svg);
}
function person_photo_url(array $item): string {
    $photo = trim((string)($item['photo_url'] ?? ''));
    if ($photo !== '') {
        return $photo;
    }
    return person_photo_placeholder((string)($item['name'] ?? 'DECOM'));
}
function docentes(): array {
    try {
        $items = fetch_people_items('docente');
        if (!empty($items)) {
            return $items;
        }
    } catch (Throwable $e) {
        error_log('Failed loading docente profiles: ' . $e->getMessage());
    }
    return [
        ['name'=>'Ana Paula Ribeiro','position'=>'Professora Adjunta','degree'=>'Doutora em Ciência da Computação','website_url'=>'','lattes_url'=>'','email'=>'ana.ribeiro@ufop.edu.br','phone'=>'(31) 3559-1601','room'=>'Instituto de Ciências Exatas e Biológicas','interests'=>'Engenharia de software e sistemas distribuídos.','bio'=>'Atua em engenharia de software e sistemas distribuídos.','photo_url'=>''],
        ['name'=>'Bruno Carvalho Mendes','position'=>'Professor Associado','degree'=>'Doutor em Ciência da Computação','website_url'=>'','lattes_url'=>'','email'=>'bruno.mendes@ufop.edu.br','phone'=>'(31) 3559-1602','room'=>'Instituto de Ciências Exatas e Biológicas','interests'=>'Inteligência artificial e mineração de dados.','bio'=>'Atua em inteligência artificial e mineração de dados.','photo_url'=>''],
        ['name'=>'Camila Freitas Lopes','position'=>'Professora Adjunta','degree'=>'Doutora em Computação','website_url'=>'','lattes_url'=>'','email'=>'camila.lopes@ufop.edu.br','phone'=>'(31) 3559-1603','room'=>'Instituto de Ciências Exatas e Biológicas','interests'=>'Computação gráfica e IHC.','bio'=>'Atua em computação gráfica e interação humano-computador.','photo_url'=>''],
    ];
}
function funcionarios(): array {
    try {
        $items = fetch_people_items('funcionario');
        if (!empty($items)) {
            return $items;
        }
    } catch (Throwable $e) {
        error_log('Failed loading funcionario profiles: ' . $e->getMessage());
    }
    return [
        ['name'=>'Mariana Souza Almeida','position'=>'Secretária Administrativa','degree'=>'','website_url'=>'','lattes_url'=>'','email'=>'mariana.almeida@ufop.edu.br','phone'=>'(31) 3559-1692','room'=>'Instituto de Ciências Exatas e Biológicas','interests'=>'Atendimento acadêmico e administrativo.','bio'=>'Atendimento acadêmico e administrativo do departamento.','photo_url'=>''],
        ['name'=>'Paulo Henrique Silva','position'=>'Técnico em TI','degree'=>'','website_url'=>'','lattes_url'=>'','email'=>'paulo.silva@ufop.edu.br','phone'=>'(31) 3559-1693','room'=>'Instituto de Ciências Exatas e Biológicas','interests'=>'Infraestrutura e suporte de laboratórios.','bio'=>'Suporte de laboratórios, sistemas e infraestrutura local.','photo_url'=>''],
    ];
}
function course_data(string $slug): array {
    $courses = [
        'ciencia-da-computacao' => ['name'=>'Bacharelado em Ciência da Computação','summary'=>'Curso voltado à formação sólida em fundamentos da computação, algoritmos, software e sistemas.','content'=>'A proposta curricular contempla programação, algoritmos, estruturas de dados, arquitetura de computadores, bancos de dados, engenharia de software, redes e teoria da computação.','modality'=>'Bacharelado','duration'=>'8 semestres','shift'=>'Integral'],
        'inteligencia-artificial' => ['name'=>'Bacharelado em Inteligência Artificial','summary'=>'Curso voltado à formação em aprendizado de máquina, ciência de dados e sistemas inteligentes.','content'=>'A matriz curricular inclui fundamentos matemáticos, programação, otimização, aprendizado de máquina, mineração de dados e visão computacional.','modality'=>'Bacharelado','duration'=>'8 semestres','shift'=>'Integral'],
    ];
    return $courses[$slug] ?? ['name'=>'Curso','summary'=>'','content'=>'','modality'=>'','duration'=>'','shift'=>''];
}
function page_data(string $slug): array {
    $pages = [
      'quem-somos'=>['title'=>'Quem somos','summary'=>'Apresentação institucional do departamento, sua trajetória e suas áreas de atuação.','content'=>'O Departamento de Computação atua em ensino, pesquisa e extensão, oferecendo cursos de graduação e desenvolvendo ações acadêmicas e tecnológicas.'],
      'comunicacao-logo'=>['title'=>'Comunicação e logo','summary'=>'Diretrizes para uso do nome, identidade visual e materiais institucionais.','content'=>'Esta página pode concentrar versões do logotipo e padrões de comunicação institucional do departamento.'],
      'localizacao'=>['title'=>'Localização','summary'=>'Informações de localização física, acesso e referência institucional.','content'=>'O departamento está localizado no campus universitário, com atendimento presencial em dias úteis.'],
      'mapa-campus'=>['title'=>'Mapa do campus','summary'=>'Mapa de acesso e referência espacial da unidade acadêmica.','content'=>'Página preparada para receber mapa interativo ou orientações de deslocamento.'],
      'horarios-de-aula'=>['title'=>'Horários de Aula','summary'=>'Consulta organizada dos horários de aula por curso, período ou turma.','content'=>'Esta página concentra quadros de horários dos alunos, horários por docente ou planilhas por semestre letivo.'],
      'informacoes-uteis'=>['title'=>'Informações Úteis','summary'=>'Orientações acadêmicas, formulários e instruções operacionais para estudantes.','content'=>'Inclua aqui calendários, orientações de matrícula, aproveitamento de estudos, equivalências e monitorias.'],
      'monografias'=>['title'=>'Monografias','summary'=>'Informações sobre disciplinas de monografia, banca, documentação e cronogramas.','content'=>'Esta página centraliza regulamentos, modelos de documentos, agendas de defesas e orientações para discentes e orientadores.'],
      'pesquisa'=>['title'=>'Pesquisa','summary'=>'Apresentação de linhas de pesquisa, grupos, projetos e produção científica.','content'=>'Esta seção organiza laboratórios, grupos de pesquisa, projetos financiados e oportunidades de iniciação científica.'],
      'extensao'=>['title'=>'Extensão','summary'=>'Catálogo de projetos e ações de extensão vinculados ao departamento.','content'=>'Esta seção apresenta programas, projetos, oficinas, cursos e ações extensionistas.'],
      'cocic'=>['title'=>'COCIC','summary'=>'Página do colegiado com apresentação de membros, competências e informações úteis aos alunos.','content'=>'O colegiado pode publicar aqui suas atribuições, composição, matriz curricular, atas, orientações acadêmicas e formulários.'],
    ];
    return $pages[$slug] ?? ['title'=>'Página','summary'=>'','content'=>''];
}
