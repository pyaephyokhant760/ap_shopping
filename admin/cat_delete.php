<?php
require '../config/config.php';
$pdoStatement = $conn->prepare("DELETE FROM categories WHERE id=".$_GET['id']);
$pdoStatement->execute();

header("Location: category.php");

?>