<?php
session_start();
include_once("../connection/connection.php");

// Verifica se o usuário está logado e se é um usuário comum
if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'usuario') {
    header('Location: index.html');
    exit();
}

// Obtém os valores enviados pelo formulário
$valores = $_POST['valor'];

// Atualiza os valores das consultas no banco de dados
foreach ($valores as $id => $valor) {
    $stmt = $conn->prepare("UPDATE consultas SET valor = ? WHERE id = ?");
    $stmt->bind_param("di", $valor, $id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

// Redireciona de volta para a página de consultas marcadas
header('Location: consultas_marcadas.php');
exit();
?>
