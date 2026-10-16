<?php

declare(strict_types=1);

use App\Validation\TaskValidator;

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
$id   = positiveId($_GET['id'] ?? null);
$db   = getDB();
$stmt = getDB()->prepare('SELECT id FROM tasks t WHERE id = ?
    AND (CAST(? AS INTEGER) = 1 OR EXISTS (SELECT 1 FROM sprint_team_members stm
        WHERE stm.sprint_id = t.sprint_id AND stm.team_id = t.team_id AND stm.user_id = ?))');
        
$stmt->execute([$id, (int) isTeacher(), $_SESSION['user_id']]);

$stmt   = $db->prepare ('SELECT * FROM tasks WHERE id = ?');
$stmt   -> execute([$id]);
$values = $stmt -> fetch();
$errors = [];

if ($values == false) {
    http_response_code(404);
    exit('Tasca no trobada.');
}

// Traure les dades del sprint
$stmt = $db-> prepare('SELECT id, name FROM sprints ');
$stmt -> execute();
$sprints = $stmt -> fetchAll();

// Traure les dades dels equips
$stmt = $db-> prepare('SELECT id, name FROM teams');
$stmt -> execute();
$teams = $stmt -> fetchAll();

// Traure les dades dels usuaris
$stmt  = $db-> prepare('SELECT id, name FROM users');
$stmt -> execute();
$users = $stmt -> fetchAll();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    requireCsrfToken();
    foreach (['title', 'description', 'sprint_id', 'team_id', 'user_id'] as $key){
        $values[$key] = is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
    }

    $errors = (new TaskValidator())->validate(
        $values,
        array_map('intval', array_column($sprints, 'id')),
        array_map('intval', array_column($teams, 'id')),
        array_map('intval', array_column($users, 'id')),
    );

    if($errors === [] && $values['team_id'] !== ''){
        $stmt = $db->prepare('SELECT 1 FROM sprint_team_members WHERE sprint_id = ? AND team_id = ?');
        $stmt -> execute([(int) $values['sprint_id'], (int) $values['team_id']]);
        if(!$stmt -> fetch()){
            $errors[] = 'L’equip no participa en este esprint.';
        };
    }

    if($errors === [] && $values['team_id'] !== '' && $values['user_id'] !== ''){
        $stmt = $db->prepare('SELECT 1 FROM sprint_team_members WHERE sprint_id = ? AND team_id = ? AND user_id = ?');
        $stmt -> execute([(int) $values['sprint_id'], (int) $values['team_id'], (int) $values['user_id']]);
        if(!$stmt -> fetch()){
            $errors[] = 'El responsable ha de pertànyer a l’equip en este esprint.';
        };
    }
    if($errors === []){
        $stmt = $db->prepare('UPDATE tasks SET title = ?, description = ?, sprint_id = ?, team_id = ?, user_id = ? WHERE id = ?');
        $stmt -> execute([$values['title'], $values['description'], (int) $values['sprint_id'], $values['team_id'] === '' ? null : (int) $values['team_id'], $values['user_id'] === '' ? null : (int) $values['user_id'],$id ]);
        header('Location: task.php?id=' . $id);
        exit;
    }
}



// TODO (alumnat): carregar les dades actuals, validar el formulari d'edició i guardar els canvis amb SQL preparat i CSRF.
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../templates/tasks/edit.php';
require __DIR__ . '/../includes/footer.php';
