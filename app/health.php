<?php
declare(strict_types=1);

require __DIR__ . '/includes/config.php';

header('Content-Type: text/html; charset=UTF-8');

try {
    $count = (int)db()->query('SELECT COUNT(*) FROM test_connection')->fetchColumn();
    $ok = true;
    $msg = "Conexao com MySQL OK · registros de teste={$count}";
} catch (Throwable $e) {
    error_log('Health check failure: ' . $e->getMessage());
    $ok = false;
    $msg = 'Servico temporariamente indisponivel.';
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Health</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <div class="alert <?= $ok ? 'alert-success' : 'alert-danger' ?>"><?= e($msg) ?></div>
        <a class="btn btn-dark" href="/">Voltar</a>
    </div>
</body>
</html>
