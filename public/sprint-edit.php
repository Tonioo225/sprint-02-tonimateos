<?php

declare(strict_types=1);

use App\Validation\SprintValidator;

require __DIR__ . '/../includes/bootstrap.php';

requireAuth();

if (!isTeacher()) {
    http_response_code(403);
    exit('No tens permisos per accedir a esta pàgina.');
}

$id = positiveId($_GET['id'] ?? null);
$db = getDB();

// Traure les dades del sprint
$stmt = $db->prepare('SELECT * FROM sprints WHERE id = ?');
$stmt->execute([$id]);
$values = $stmt->fetch();

if ($values === false) {
    http_response_code(404);
    exit('Esprint no trobat.');
}

$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    requireCsrfToken();
    foreach (['name', 'goal', 'start_date', 'end_date', 'status'] as $key){
        $values[$key] = is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
    }
    $errors = (new SprintValidator())->validate($values);
    if($errors === []){
        $stmt = $db->prepare('UPDATE sprints SET name = ?, goal = ?, start_date = ?, end_date = ?, status = ? WHERE id = ?');
        $stmt -> execute([$values['name'], $values['goal'], $values['start_date'],$values['end_date'],$values['status'], $id]);
        setFlash('success', 'Esprint editat correctament.');
        redirect('sprint.php?id=' . $id);
    }
}

// TODO (alumnat): carregar les dades actuals, validar el formulari d'edició i guardar els canvis amb SQL preparat i CSRF.
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../templates/sprints/edit.php';
require __DIR__ . '/../includes/footer.php';
?>