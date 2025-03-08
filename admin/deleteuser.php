<?php
require_once 'dbaccess.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM User WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: http://localhost/hotelseite_php/index.php?page=benutzerverwaltung");
    exit();
}
?>