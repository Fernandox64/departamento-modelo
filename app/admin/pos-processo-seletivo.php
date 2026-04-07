<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';

require_admin_permission('manage_pos');
ensure_ppgcc_tables();

$type = ppgcc_selection_type_normalize((string)($_GET['tipo'] ?? ($_POST['tipo'] ?? 'informacao')));
$typeLabel = ppgcc_selection_type_label($type);
$error = null;
$success = null;
$editing = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!is_valid_csrf_token($_POST['csrf_token'] ?? null)) {
        $error = 'Token CSRF invalido.';
    } else {
        $action = (string)($_POST['action'] ?? 'save');
        try {
            if ($action === 'import') {
                $result = ppgcc_import_selection_page();
                if (($result['ok'] ?? false) === true) {
                    $success = 'Importacao concluida. Itens inseridos: ' . (string)($result['inserted'] ?? 0) . '.';
                } else {
                    $error = (string)($result['message'] ?? 'Falha na importacao.');
                }
            } elseif ($action === 'save') {
                $id = (int)($_POST['id'] ?? 0);
                $groupTitle = trim((string)($_POST['group_title'] ?? 'Processo Seletivo'));
                $itemTitle = trim((string)($_POST['item_title'] ?? ''));
                $itemUrl = trim((string)($_POST['item_url'] ?? ''));
                $publishedAt = trim((string)($_POST['published_at'] ?? ''));
                $sortOrder = (int)($_POST['sort_order'] ?? 0);
                $isActive = isset($_POST['is_active']) ? 1 : 0;
                if ($itemTitle === '' || $itemUrl === '') {
                    $error = 'Titulo e URL sao obrigatorios.';
                } else {
                    $published = $publishedAt !== '' ? str_replace('T', ' ', $publishedAt) . ':00' : date('Y-m-d H:i:s');
                    if ($id > 0) {
                        $stmt = db()->prepare(
                            'UPDATE ppgcc_selection_items
                             SET group_title = :g, item_title = :t, item_url = :u, item_type = :type,
                                 is_active = :active, published_at = :published, sort_order = :sort
                             WHERE id = :id'
                        );
                        $stmt->execute([
                            ':g' => $groupTitle,
                            ':t' => $itemTitle,
                            ':u' => $itemUrl,
                            ':type' => $type,
                            ':active' => $isActive,
                            ':published' => $published,
                            ':sort' => $sortOrder,
                            ':id' => $id,
                        ]);
                        $success = 'Item atualizado com sucesso.';
                    } else {
                        $hash = hash('sha256', $groupTitle . '|' . $itemTitle . '|' . $itemUrl . '|' . microtime(true));
                        $stmt = db()->prepare(
                            'INSERT INTO ppgcc_selection_items (group_title, item_title, item_url, item_type, is_active, published_at, item_hash, sort_order)
                             VALUES (:g, :t, :u, :type, :active, :published, :hash, :sort)'
                        );
                        $stmt->execute([
                            ':g' => $groupTitle,
                            ':t' => $itemTitle,
                            ':u' => $itemUrl,
                            ':type' => $type,
                            ':active' => $isActive,
                            ':published' => $published,
                            ':hash' => $hash,
                            ':sort' => $sortOrder,
                        ]);
                        $success = 'Item adicionado com sucesso.';
                    }
                }
            } elseif ($action === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id > 0) {
                    $stmt = db()->prepare('DELETE FROM ppgcc_selection_items WHERE id = :id');
                    $stmt->execute([':id' => $id]);
                    $success = 'Item removido com sucesso.';
                }
            }
        } catch (Throwable $e) {
            $error = 'Falha ao processar processo seletivo da pos.';
            error_log('Admin pos-processo-seletivo error: ' . $e->getMessage());
        }
    }
}

$editId = (int)($_GET['edit'] ?? 0);
if ($editId > 0) {
    $stmt = db()->prepare('SELECT * FROM ppgcc_selection_items WHERE id = :id');
    $stmt->execute([':id' => $editId]);
    $editing = $stmt->fetch() ?: null;
    if ($editing) {
        $type = ppgcc_selection_type_normalize((string)$editing['item_type']);
        $typeLabel = ppgcc_selection_type_label($type);
    }
}

$perPage = 12;
$page = max(1, (int)($_GET['pagina'] ?? 1));
$total = ppgcc_selection_items_count($type, false);
$totalPages = max(1, (int)ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;
$items = ppgcc_selection_items_paginated($type, $perPage, $offset, false);
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Processo Seletivo da Pos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/css/adminlte.min.css">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">Menu</a></li>
                <li class="nav-item d-none d-md-block"><a href="/admin/dashboard.php" class="nav-link">Dashboard</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <form method="post" action="/admin/logout.php" class="m-0">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <button type="submit" class="btn btn-outline-danger btn-sm">Sair</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <?php render_admin_sidebar('pos_processo'); ?>

    <main class="app-main">
        <div class="app-content-header"><div class="container-fluid"><h3 class="mb-0">Processo Seletivo da Pos - <?= e($typeLabel) ?></h3></div></div>
        <div class="app-content"><div class="container-fluid">
            <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0"><?= $editing ? 'Editar item' : 'Adicionar item' ?> (<?= e($typeLabel) ?>)</h3>
                    <div class="d-flex gap-2">
                        <a class="btn btn-outline-primary btn-sm" href="/admin/pos-processo-seletivo.php?tipo=informacao">Informacoes</a>
                        <a class="btn btn-outline-warning btn-sm" href="/admin/pos-processo-seletivo.php?tipo=resultado">Resultados</a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="action" value="save">
                        <input type="hidden" name="tipo" value="<?= e($type) ?>">
                        <input type="hidden" name="id" value="<?= e((string)($editing['id'] ?? 0)) ?>">
                        <div class="row g-3">
                            <div class="col-md-8"><label class="form-label">Grupo/Titulo da secao</label><input class="form-control" name="group_title" value="<?= e((string)($editing['group_title'] ?? 'Processo Seletivo')) ?>"></div>
                            <div class="col-md-4"><label class="form-label">Ordem</label><input class="form-control" type="number" name="sort_order" value="<?= e((string)($editing['sort_order'] ?? 0)) ?>"></div>
                            <div class="col-md-8"><label class="form-label">Titulo do item</label><input class="form-control" name="item_title" required value="<?= e((string)($editing['item_title'] ?? '')) ?>"></div>
                            <div class="col-md-4"><label class="form-label">Data de publicacao</label><input class="form-control" type="datetime-local" name="published_at" value="<?= e(isset($editing['published_at']) ? str_replace(' ', 'T', substr((string)$editing['published_at'], 0, 16)) : '') ?>"></div>
                            <div class="col-12"><label class="form-label">URL</label><input class="form-control" name="item_url" required placeholder="https://..." value="<?= e((string)($editing['item_url'] ?? '')) ?>"></div>
                            <div class="col-12">
                                <?php $active = !isset($editing['is_active']) || (int)$editing['is_active'] === 1; ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="isActive" name="is_active"<?= $active ? ' checked' : '' ?>>
                                    <label class="form-check-label" for="isActive">Item ativo</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-primary" type="submit"><?= $editing ? 'Salvar alteracoes' : 'Adicionar item' ?></button>
                            <?php if ($editing): ?><a class="btn btn-outline-secondary" href="/admin/pos-processo-seletivo.php?tipo=<?= e($type) ?>">Cancelar</a><?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h3 class="card-title">Importar da pagina oficial</h3></div>
                <div class="card-body">
                    <p class="mb-2">Importa itens automaticamente da fonte oficial da Pos.</p>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="action" value="import">
                        <input type="hidden" name="tipo" value="<?= e($type) ?>">
                        <button class="btn btn-dark" type="submit">Importar dados agora</button>
                    </form>
                    <a class="btn btn-outline-primary ms-2" href="/pos/processo-seletivo.php" target="_blank" rel="noopener">Ver pagina publica</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Itens cadastrados (<?= e((string)$total) ?>)</h3></div>
                <div class="card-body table-responsive">
                    <table class="table table-sm align-middle">
                        <thead><tr><th>Data</th><th>Grupo</th><th>Titulo</th><th>Status</th><th class="text-end">Acoes</th></tr></thead>
                        <tbody>
                            <?php foreach ($items as $it): ?>
                                <tr>
                                    <td><?= e(date('d/m/Y', strtotime((string)$it['published_at']))) ?></td>
                                    <td><?= e((string)$it['group_title']) ?></td>
                                    <td><a target="_blank" rel="noopener" href="<?= e((string)$it['item_url']) ?>"><?= e((string)$it['item_title']) ?></a></td>
                                    <td><?= (int)$it['is_active'] === 1 ? 'Ativo' : 'Oculto' ?></td>
                                    <td class="text-end">
                                        <a class="btn btn-outline-primary btn-sm" href="/admin/pos-processo-seletivo.php?tipo=<?= e($type) ?>&edit=<?= e((string)$it['id']) ?>">Editar</a>
                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="tipo" value="<?= e($type) ?>">
                                            <input type="hidden" name="id" value="<?= e((string)$it['id']) ?>">
                                            <button class="btn btn-outline-danger btn-sm" type="submit" onclick="return confirm('Excluir este item?');">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($items)): ?>
                                <tr><td colspan="5" class="text-center text-muted">Nenhum item cadastrado.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($totalPages > 1): ?>
                    <div class="card-footer">
                        <nav aria-label="Paginacao de processo seletivo">
                            <ul class="pagination pagination-sm mb-0">
                                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                    <li class="page-item<?= $p === $page ? ' active' : '' ?>"><a class="page-link" href="/admin/pos-processo-seletivo.php?tipo=<?= e($type) ?>&pagina=<?= e((string)$p) ?>"><?= e((string)$p) ?></a></li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>
        </div></div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/js/adminlte.min.js"></script>
</body>
</html>
