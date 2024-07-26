<?php
include_once("../connection/connection.php");

// Obtém os dados do formulário
$nome = $_POST['nome'];
$data_nascimento = $_POST['data_nascimento'];
$telefone = $_POST['telefone'];
$cpf = $_POST['cpf'];
$endereco = $_POST['endereco'];

// Insere os dados na tabela
$sql = "INSERT INTO pacientes (nome, data_nascimento, telefone, cpf, endereco) VALUES ('$nome', '$data_nascimento', '$telefone', '$cpf', '$endereco')";

if ($conn->query($sql) === TRUE) {
    header('Location: pacientes_cadastrados.php');
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
