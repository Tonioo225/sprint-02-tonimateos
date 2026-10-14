<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
$users = getDB()->query("SELECT * FROM users")->fetchAll();
require __DIR__ . '/../includes/header.php';
?>
<h1>Benvingut/da als Usuaris</h1>
<p>Llista els usuaris del sistema.</p>
<a class="btn btn-primary" href="user-create.php">Crear usuari</a>
<?php foreach ($users as $user): ?>
<div class="card mb-3"><div class="card-body">
    <h2>Usuari: <?= h($user['name']) ?></h2>
    <p><?= h($user['email']) ?></p>
</div></div>
<?php endforeach; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>