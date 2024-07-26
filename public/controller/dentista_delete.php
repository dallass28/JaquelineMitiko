<?php
session_start();
include_once("../connection/connection.php");

// Verifica se o usuário está logado e se é um usuário comum
if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'usuario') {
    header('Location: index.html');
    exit();
}

$id = $_GET['id'] ?? '';

if (!empty($id)) {
    $sql = "DELETE FROM dentistas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header('Location: dentistas_list.php');
exit();
?>
