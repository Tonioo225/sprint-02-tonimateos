<?php foreach ($flashes as [$type, $message]): ?>
    <?php $class = $type === 'success' ? 'success' : 'danger'; ?>
    <div class="alert alert-<?= $class ?>">
        <?= h($message) ?>
    </div>
<?php endforeach; ?>