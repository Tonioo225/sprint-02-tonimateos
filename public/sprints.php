<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
$sprints = getDB()->query('SELECT * FROM sprints ORDER BY start_date DESC')->fetchAll();
require __DIR__ . '/../includes/header.php';
?>
<h1>Esprints</h1>
<div class="list-group">
<?php foreach ($sprints as $sprint): ?>
    <a class="list-group-item list-group-item-action" href="sprint.php?id=<?= (int) $sprint['id'] ?>">
        <strong><?= h($sprint['name']) ?></strong> <?= statusBadge($sprint['status']) ?>
        <span class="d-block text-muted"><?= h($sprint['start_date']) ?> — <?= h($sprint['end_date']) ?></span>
    </a>
<?php endforeach; ?>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
