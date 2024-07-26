<?php
session_start();
include_once("../connection/connection.php");

// Verifica se o usuário está logado e se é um usuário comum
if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'usuario') {
    header('Location: index.html');
    exit();
}

$id = $_POST['id'] ?? '';
$nome = $_POST['nome'];

if (!empty($id)) {
    // Atualiza o dentista
    $sql = "UPDATE dentistas SET nome = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nome, $id);
    $stmt->execute();
} else {
    // Adiciona um novo dentista
    $sql = "INSERT INTO dentistas (nome) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome);
    $stmt->execute();
}

header('Location: dentistas_list.php');
exit();
?>
