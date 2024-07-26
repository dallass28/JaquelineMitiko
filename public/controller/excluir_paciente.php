<?php
session_start();
include_once("../connection/connection.php");

// Verifica se o usuário está logado e se é um usuário comum
if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'usuario') {
    header('Location: index.html');
    exit();
}

// Obtém o ID do paciente a ser excluído
$id = $_GET['id'];

// Deleta o paciente
$sql = "DELETE FROM pacientes WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "Paciente excluído com sucesso!";
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

$conn->close();
header('Location: pacientes_cadastrados.php');
exit();
?>
