<?php
    session_start();
    include_once("../connection/connection.php");

    if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'usuario') {
        header('Location: index.html');
        exit();
    }

    $sql = "SELECT * FROM dentistas";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lista de Dentistas</title>
        <link rel='stylesheet' type='text/css' media='screen' href='../../src/css/styles_default.css'>
        <link rel='stylesheet' type='text/css' media='screen' href='../../src/css/styles_consul.css'>
    </head>
    <body>
        <header>
            <?php
                include_once("./header.html");
            ?>
        </header>
        <div class="content">
            <h1 class="title">Lista de Dentistas</h1>
            <a href="dentista_form.php?action=add">Adicionar Novo Dentista</a>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th id="title_btns">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['nome']}</td>
                                <td id='cell_btns'>
                                    <a href='dentista_form.php?action=edit&id={$row['id']}'><div class='btn edit'>Editar</div></a>
                                    <a href='dentista_delete.php?id={$row['id']}' onclick='return confirm(\"Tem certeza que deseja excluir?\");'><div class='btn exclude'>Excluir</div></a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Nenhum dentista cadastrado</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>
<?php
    $conn->close();
?>
