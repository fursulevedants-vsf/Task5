<?php

include "config.php";

Auth::guard('delete');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id AND user_id = :user_id");
$stmt->execute([
    'id'      => $id,
    'user_id' => $_SESSION['user_id']
]);

header("Location: index.php?msg=deleted");
exit;
?>