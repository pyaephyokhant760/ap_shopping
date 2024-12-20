<?php
require '../config/config.php';
$pdoStatement = $conn->prepare("DELETE FROM products WHERE id=".$_GET['id']);
$pdoStatement->execute();

header("Location: index.php");

?>