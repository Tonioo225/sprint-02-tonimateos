<?php

declare(strict_types=1);

use App\User;
use App\Validation\UserValidator;

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
$db     = getDB();
$values = ['name' => '', 'username' => '', 'email' => '', 'password' => '', 'repeat_password' => ''];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    foreach ($values as $key => $_) {
        $values[$key] = is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
    }
    $errors = (new UserValidator())->validate($values);
    
    if($values['password'] === $values['repeat_password']){

    $stmt = $db->prepare('SELECT 1 FROM users WHERE username = ?');
    $stmt->execute([$values['username']]);
    if ($stmt->fetch()) { $errors[] = 'L’usuari ja existeix.'; }

    $stmt = $db->prepare('SELECT 1 FROM users WHERE email = ?');
    $stmt->execute([$values['email']]);
    if ($stmt->fetch()) { $errors[] = 'El correu ja existeix.'; }

    if ($errors === []) {
        $user = new User($values['username'], $values['password'], $values['name'], $values['email'],);
        $stmt = $db->prepare("INSERT INTO users (username, name, email, password, role) VALUES (?, ?, ?, ?, 'student')");
        $stmt->execute([$user->getUsername(),$user->getName(), $user ->getEmail(), $user ->getPassword()]);

        setFlash('success', 'Usuari creat correctament.');
        redirect('board.php');
    }
    }else{
        $errors[] = 'La contrasenya no coicideix.';
    }
}
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/footer.php';
?>
<h1>Nou usuari</h1>
<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post">
    <?= csrfField() ?>
    <label class="form-label" for="name">Nom *</label>
    <input class="form-control mb-3" id="name" name="name" maxlength="20" value="<?= h($values['name']) ?>" required>

    <label class="form-label" for="username">Usuari *</label>
    <input class="form-control mb-3" id="username" name="username" maxlength="20" value="<?= h($values['username']) ?>" required>

    <label class="form-label" for="email">Correu *</label>
    <input class="form-control mb-3" id="email" name="email" type="email" maxlength="150" value="<?= h($values['email']) ?>" required>

    <label class="form-label" for="password">Contrasenya *</label>
    <input class="form-control mb-3" id="password" name="password" type="password" maxlength="150" required>

    <label class="form-label" for="password">Repeteix la contrasenya *</label>
    <input class="form-control mb-3" id="repeat_password" name="repeat_password" type="password" maxlength="150" required>

    <button class="btn btn-primary" >Crear usuari</button>
</form>