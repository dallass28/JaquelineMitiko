<?php
session_start();
include_once("../connection/connection.php");

// Verifica se o usuário está logado e se é um usuário comum
if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'usuario') {
    header('Location: index.html');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paciente_id = $_POST['paciente_id'];
    $data_consulta = $_POST['data_consulta'];
    $metodo_pagamento = $_POST['metodo_pagamento'];
    $dentista_id = $_POST['dentista_id'];

    $sql = "INSERT INTO consultas (paciente_id, data_consulta, metodo_pagamento, dentista_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $paciente_id, $data_consulta, $metodo_pagamento, $dentista_id);

    if ($stmt->execute()) {
        header('Location: consultas_marcadas.php');
        exit();
    } else {
        echo "Erro ao marcar consulta: " . $stmt->error;
    }
}

$conn->close();
?>
