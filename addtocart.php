<?php
session_start();
require 'config/config.php';

if ($_POST) {
    $id = $_POST['id'];
    $qty = $_POST['qty'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE id=" . $id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


    if ($qty > $result[0]['quantity']) {
        echo "<script>alert('Not enough stock'); window.location.href='project_detail.php?id=" . $id . "';</script>";
    } else {
        if (isset($_SESSION['cart']['id' . $id])) {
            $_SESSION['cart']['id' . $id] += $qty;
        } else {
            $_SESSION['cart']['id' . $id] = $qty;
        }

        header('location: cart.php');
    }
}
