<?php
declare(strict_types = 1);

require __DIR__ . '/../includes/bootstrap.php';

requireAuth();
//requireCsrfToken();

$id = positiveId($_GET['id'] ?? null);
if(!isTeacher()){
    http_response_code(403);
    exit('No tens permisos per accedir a esta pàgina.');
}


$db = getDB();

$stmt = $db->prepare('SELECT * FROM sprints WHERE id=?');
$stmt ->execute([$id]);
$sprint = $stmt->fetch();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if($_POST['button'] === 'confirm'){
    $stmt=$db->prepare('DELETE FROM sprints WHERE id=?');
    $stmt-> execute([$id]);
    setFlash('success', 'Esprint esborrat correctament.');
    redirect('sprints.php');
    }
    else{
        redirect('sprint.php?id=' . $id);
    }
}

require __DIR__ . '/../includes/header.php';
?>

<p>Segur que vols esborrar el <?= $sprint['name'] ?>?</p>
<form method="post">
    <button class="btn btn-primary" value="confirm"  name="button">Confirmar</button>
    <button class="btn btn-secondary" value="cancel" name="button">Cancelar</button>
</form>
<?php require __DIR__ . '/../includes/footer.php'; ?>