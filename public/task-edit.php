<?php

declare(strict_types=1);

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
$stmt = $db-> prepare('SELECT id, name FROM users');
$stmt -> execute();
$users = $stmt -> fetchAll();



// TODO (alumnat): carregar les dades actuals, validar el formulari d'edició i guardar els canvis amb SQL preparat i CSRF.
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../templates/tasks/edit.php';
require __DIR__ . '/../includes/footer.php';
