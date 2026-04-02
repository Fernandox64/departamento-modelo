<?php
declare(strict_types=1);

/**
 * Importa itens da página de notícias do DECOM-UFOP para o banco local.
 *
 * Uso:
 *   php scripts/import_decom_news.php --max-pages=6 --truncate
 */

const DEFAULT_BASE_URL = 'https://www3.decom.ufop.br/decom/noticias/noticias/';
const DEFAULT_MAX_PAGES = 6;

function cli_arg(string $name, ?string $default = null): ?string {
    global $argv;
    foreach ($argv as $arg) {
        if (str_starts_with($arg, "--{$name}=")) {
            return substr($arg, strlen($name) + 3);
        }
    }
    return $default;
}

function cli_flag(string $name): bool {
    global $argv;
    return in_array("--{$name}", $argv, true);
}

function db(): PDO {
    $host = getenv('DB_HOST') ?: 'db';
    $port = getenv('DB_PORT') ?: '3306';
    $database = getenv('DB_DATABASE') ?: 'newsdb';
    $username = getenv('DB_USERNAME') ?: 'newsuser';
    $password = getenv('DB_PASSWORD') ?: 'newspass';
    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

    return new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 10,
    ]);
}

function ensure_schema(PDO $pdo): void {
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS news_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            slug VARCHAR(150) NOT NULL UNIQUE,
            title VARCHAR(255) NOT NULL,
            summary TEXT NOT NULL,
            category VARCHAR(80) NOT NULL,
            content TEXT NOT NULL,
            image VARCHAR(255) DEFAULT NULL,
            published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS edital_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            slug VARCHAR(150) NOT NULL UNIQUE,
            title VARCHAR(255) NOT NULL,
            summary TEXT NOT NULL,
            category VARCHAR(80) NOT NULL,
            content TEXT NOT NULL,
            image VARCHAR(255) DEFAULT NULL,
            published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
    );
}

function fetch_page(string $url, bool $insecure): string {
    $ctx = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 20,
            'header' => "User-Agent: decom-importer/1.0\r\n",
        ],
        'ssl' => [
            'verify_peer' => !$insecure,
            'verify_peer_name' => !$insecure,
        ],
    ]);
    $html = @file_get_contents($url, false, $ctx);
    if ($html === false) {
        throw new RuntimeException("Falha ao baixar {$url}");
    }
    return $html;
}

function month_map(): array {
    return [
        'jan' => 1, 'fev' => 2, 'mar' => 3, 'abr' => 4, 'mai' => 5, 'jun' => 6,
        'jul' => 7, 'ago' => 8, 'set' => 9, 'out' => 10, 'nov' => 11, 'dez' => 12,
    ];
}

function normalize_title(string $title): string {
    $title = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $title = preg_replace('/\s+/u', ' ', trim($title)) ?? trim($title);
    return trim($title, " \t\n\r\0\x0B·");
}

function slugify(string $text): string {
    $text = mb_strtolower($text, 'UTF-8');
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    $text = trim($text, '-');
    if ($text === '') {
        return 'item-' . bin2hex(random_bytes(4));
    }
    return substr($text, 0, 140);
}

function classify_item(string $title): array {
    $t = mb_strtolower($title, 'UTF-8');

    if (preg_match('/edital|matricul|monitoria|resultado|processo seletivo|bolsista/u', $t)) {
        return ['table' => 'edital_items', 'category' => 'Editais', 'image' => '/assets/cards/edital-monitoria.svg'];
    }
    if (preg_match('/defesa|qualific|semin[aá]rio|mestrado|doutorado|monografia/u', $t)) {
        return ['table' => 'news_items', 'category' => 'Pesquisa', 'image' => '/assets/cards/noticia-pesquisa.svg'];
    }
    if (preg_match('/aula|disciplina|grade|hor[áa]rio/u', $t)) {
        return ['table' => 'news_items', 'category' => 'Ensino', 'image' => '/assets/cards/noticia-horarios.svg'];
    }
    return ['table' => 'news_items', 'category' => 'Departamento', 'image' => '/assets/cards/noticia-portal.svg'];
}

function published_from_context(string $monthYear, string $title): string {
    $monthYear = mb_strtolower(trim($monthYear), 'UTF-8');
    $parts = preg_split('/[-\/]/', $monthYear);
    $monthName = trim((string)($parts[0] ?? ''));
    $year = (int)trim((string)($parts[1] ?? date('Y')));
    $months = month_map();
    $month = $months[substr($monthName, 0, 3)] ?? (int)date('n');
    $day = 1;

    if (preg_match('/\b(\d{1,2})\/(\d{1,2})(?:\/(\d{2,4}))?\b/u', $title, $m)) {
        $day = max(1, min(28, (int)$m[1]));
        $month = max(1, min(12, (int)$m[2]));
        if (!empty($m[3])) {
            $y = (int)$m[3];
            $year = $y < 100 ? 2000 + $y : $y;
        }
    }

    return sprintf('%04d-%02d-%02d 09:00:00', $year, $month, $day);
}

function parse_items_from_html(string $html): array {
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);
    $h4Nodes = $xpath->query('//h4');
    if ($h4Nodes === false) {
        return [];
    }

    $items = [];
    foreach ($h4Nodes as $h4) {
        $monthYear = normalize_title($h4->textContent);
        $next = $h4->nextSibling;
        while ($next !== null && !($next instanceof DOMElement && strtolower($next->nodeName) === 'ul')) {
            $next = $next->nextSibling;
        }
        if (!($next instanceof DOMElement)) {
            continue;
        }

        foreach ($next->getElementsByTagName('li') as $li) {
            $title = normalize_title($li->textContent);
            if ($title === '') {
                continue;
            }
            $items[] = ['month_year' => $monthYear, 'title' => $title];
        }
    }

    return $items;
}

function upsert_items(PDO $pdo, array $items, bool $truncate): array {
    if ($truncate) {
        $pdo->exec('TRUNCATE TABLE news_items');
        $pdo->exec('TRUNCATE TABLE edital_items');
    }

    $sqlNews = "INSERT INTO news_items (slug, title, summary, category, content, image, published_at)
                VALUES (:slug, :title, :summary, :category, :content, :image, :published_at)
                ON DUPLICATE KEY UPDATE
                    title=VALUES(title),
                    summary=VALUES(summary),
                    category=VALUES(category),
                    content=VALUES(content),
                    image=VALUES(image),
                    published_at=VALUES(published_at)";

    $sqlEdital = "INSERT INTO edital_items (slug, title, summary, category, content, image, published_at)
                  VALUES (:slug, :title, :summary, :category, :content, :image, :published_at)
                  ON DUPLICATE KEY UPDATE
                    title=VALUES(title),
                    summary=VALUES(summary),
                    category=VALUES(category),
                    content=VALUES(content),
                    image=VALUES(image),
                    published_at=VALUES(published_at)";

    $stmtNews = $pdo->prepare($sqlNews);
    $stmtEdital = $pdo->prepare($sqlEdital);

    $insertedNews = 0;
    $insertedEditais = 0;
    foreach ($items as $item) {
        $title = $item['title'];
        $class = classify_item($title);
        $slug = slugify($title);
        $publishedAt = published_from_context($item['month_year'], $title);
        $summary = mb_substr($title, 0, 180, 'UTF-8');
        $content = 'Importado automaticamente do portal oficial do DECOM-UFOP.';

        $params = [
            ':slug' => $slug,
            ':title' => $title,
            ':summary' => $summary,
            ':category' => $class['category'],
            ':content' => $content,
            ':image' => $class['image'],
            ':published_at' => $publishedAt,
        ];

        if ($class['table'] === 'edital_items') {
            $stmtEdital->execute($params);
            $insertedEditais++;
        } else {
            $stmtNews->execute($params);
            $insertedNews++;
        }
    }

    return [$insertedNews, $insertedEditais];
}

function main(): void {
    $baseUrl = cli_arg('base-url', DEFAULT_BASE_URL) ?? DEFAULT_BASE_URL;
    $maxPages = max(1, (int)(cli_arg('max-pages', (string)DEFAULT_MAX_PAGES) ?? DEFAULT_MAX_PAGES));
    $truncate = cli_flag('truncate');
    $insecure = cli_flag('insecure');

    $pdo = db();
    ensure_schema($pdo);

    $all = [];
    $emptyPages = 0;
    for ($page = 1; $page <= $maxPages; $page++) {
        $url = $page === 1 ? $baseUrl : $baseUrl . '?page=' . $page;
        echo "Baixando: {$url}\n";
        $html = fetch_page($url, $insecure);
        $items = parse_items_from_html($html);
        echo "Itens encontrados na pagina {$page}: " . count($items) . "\n";

        if (count($items) === 0) {
            $emptyPages++;
            if ($emptyPages >= 2) {
                break;
            }
            continue;
        }

        $emptyPages = 0;
        $all = array_merge($all, $items);
    }

    if (empty($all)) {
        throw new RuntimeException('Nenhum item encontrado para importar.');
    }

    $unique = [];
    foreach ($all as $item) {
        $key = slugify($item['title']);
        if (!isset($unique[$key])) {
            $unique[$key] = $item;
        }
    }
    $all = array_values($unique);

    [$n, $e] = upsert_items($pdo, $all, $truncate);
    echo "Importacao concluida. Processados: " . count($all) . " | noticias={$n} | editais={$e}\n";
}

try {
    main();
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, 'Erro: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
