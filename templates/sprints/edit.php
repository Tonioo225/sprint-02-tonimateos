<?php
declare(strict_types=1);
?>
<h1>Editar esprint</h1>
<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>

<form method="post">
    <?= csrfField() ?>

    <label class="form-label" for="name">Nom *</label>
    <input class="form-control mb-3" id="name" name="name" maxlength="150" value="<?= h($values['name']) ?>" required>

    <label class="form-label" for="goal">Objectiu *</label>
    <textarea class="form-control mb-3" id="goal" name="goal" maxlength="2000" rows="3" required><?= h($values['goal']) ?></textarea>

    <label class="form-label" for="start_date">Data inici *</label>
    <input class="form-control mb-3" id="start_date" name="start_date" type="date" value="<?= h($values['start_date']) ?>" required>

    <label class="form-label" for="end_date">Data fi *</label>
    <input class="form-control mb-3" id="end_date" name="end_date" type="date" value="<?= h($values['end_date']) ?>" required>

    <label class="form-label" for="status">Estat *</label>
    <select class="form-select mb-3" id="status" name="status" required>
        <option value="pending" <?= $values['status'] === 'pending' ? 'selected' : '' ?>>Pendent</option>
        <option value="active" <?= $values['status'] === 'active' ? 'selected' : '' ?>>Actiu</option>
        <option value="completed" <?= $values['status'] === 'completed' ? 'selected' : '' ?>>Completat</option>
    </select>

    <button class="btn btn-primary">Guardar canvis</button>
</form>

