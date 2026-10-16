<?php

declare(strict_types=1);

use App\Sprint;
use App\Validation\SprintValidator;

require __DIR__ . '/../includes/bootstrap.php';

requireAuth();

if(!isTeacher()){
    http_response_code(403);
    exit('No tens els permisos per accedir a esta pàgina.');
}

$db = getDB();
$values = ['name' => '', 'goal' => '', 'start_date' => '', 'end_date' => '', 'status' => 'pending'];
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    requireCsrfToken();
    foreach ($values as $key => $_){
        $values[$key] = is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
    }
    $errors = (new SprintValidator())->validate($values);
    if($errors === []){
        $sprint = new Sprint($values['name'], $values['goal'], $values['start_date'], $values['end_date'],$values['status']);
        $stmt = $db->prepare('INSERT INTO sprints (name, goal, start_date, end_date, status) VALUES (?, ?, ?, ?, ?)');
        $stmt -> execute([$sprint->getName(), $sprint->getGoal(), $sprint->getStartDate(),$sprint->getEndDate(), $sprint->getStatus()
        ]);
        setFlash('success', 'Esprint creat correctament.');
        redirect('board.php');
    }
}
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../templates/sprints/create.php';
require __DIR__ . '/../includes/footer.php';
?>