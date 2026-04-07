<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !is_valid_csrf_token($_POST['csrf_token'] ?? null)) {
    http_response_code(405);
    exit('Metodo nao permitido.');
}

admin_logout();
redirect('/admin/login.php');

