<?php
    session_start();
    include_once("../connection/connection.php");

    // Verifica se o usuário está logado e se é um usuário comum
    if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'usuario') {
        header('Location: index.html');
        exit();
    }

    // Seleciona todos os pacientes
    $sql = "SELECT * FROM pacientes";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet' type='text/css' media='screen' href='../../src/css/styles_default.css'>
        <link rel='stylesheet' type='text/css' media='screen' href='../../src/css/styles_consul.css'>
        <title>Pacientes Cadastrados</title>
    </head>
    <body>
        <header>
            <?php
                include_once("./header.html");
            ?>
        </header>
        <section class="content">
            <h1 class="title">Pacientes Cadastrados</h1>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Data de Nascimento</th>
                    <th>Telefone</th>
                    <th>CPF</th>
                    <th>Endereço</th>
                    <th id="title_btns">Ações</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['nome'] . "</td>";
                        echo "<td>" . $row['data_nascimento'] . "</td>";
                        echo "<td>" . $row['telefone'] . "</td>";
                        echo "<td>" . $row['cpf'] . "</td>";
                        echo "<td>" . $row['endereco'] . "</td>";
                        echo "<td id='cell_btns'>
                                <a href='editar_paciente.php?id=" . $row['id'] . "'><div class='btn edit'>Editar</div></a>
                                <a href='excluir_paciente.php?id=" . $row['id'] . "' onclick='return confirm(\"Tem certeza que deseja excluir este paciente?\")'><div class='btn exclude'>Excluir</div></a>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Nenhum paciente cadastrado.</td></tr>";
                }
                ?>
            </table>
        <section>
    </body>
</html>
<?php
    $conn->close();
?>
