<?php
    require '../config/config.php';
    $pdo_statement = $pdo->prepare("DELETE FROM categories WHERE id=".$_GET['id']);
    $pdo_statement->execute();

    header('location: category.php');
?>