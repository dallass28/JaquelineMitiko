<?php
session_start();
include_once("../connection/connection.php");

// Captura os dados do formulário
$username = $_POST['username'];
$password = $_POST['password'];

// Verifica se o usuário existe e se a senha está correta
$sql = "SELECT * FROM usuarios WHERE username = ? AND senha = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Armazena o nível de acesso na sessão
    $_SESSION['nivel'] = $user['nivel'];
    
    // Redireciona para a página adequada
    if ($_SESSION['nivel'] === 'admin') {
        header('Location: home-admin.php');
    } else {
        header('Location: home-usuario.php');
    }
    exit();
} else {
    echo "Usuário ou senha incorretos.";
}

$conn->close();
?>
