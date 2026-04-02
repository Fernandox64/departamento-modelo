<?php
require __DIR__ . '/../includes/config.php';

$items = funcionarios();
page_header('Funcionarios');
?>
<div class="container py-4">
    <h1 class="section-title h3 mb-4">Funcionarios</h1>
    <div class="row g-3">
        <?php foreach ($items as $item): ?>
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h5 mb-1"><?= e($item['name'] ?? '') ?></h2>
                        <p class="mb-2 text-muted"><?= e($item['position'] ?? '') ?></p>

                        <?php if (!empty($item['email'])): ?>
                            <p class="mb-1"><strong>E-mail:</strong> <a href="mailto:<?= e($item['email']) ?>"><?= e($item['email']) ?></a></p>
                        <?php endif; ?>
                        <?php if (!empty($item['phone'])): ?>
                            <p class="mb-1"><strong>Telefone:</strong> <?= e($item['phone']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($item['room'])): ?>
                            <p class="mb-1"><?= e($item['room']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($item['bio'])): ?>
                            <p class="mb-0 text-muted"><?= e($item['bio']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php page_footer(); ?>
