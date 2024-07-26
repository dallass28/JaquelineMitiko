<?php
    session_start();
    include_once("../connection/connection.php");

    if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'usuario') {
        header('Location: index.html');
        exit();
    }

    $sql_pacientes = "SELECT id, nome FROM pacientes";
    $result_pacientes = $conn->query($sql_pacientes);

    $sql_dentistas = "SELECT id, nome FROM dentistas";
    $result_dentistas = $conn->query($sql_dentistas);
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet' type='text/css' media='screen' href='../../src/css/styles_default.css'>
        <link rel='stylesheet' type='text/css' media='screen' href='../../src/css/styles_cad_pasc.css'>
        <title>Marcar Consulta</title>
    </head>
    <body>
        <header>
            <?php
                include_once("./header.html");
            ?>
        </header>
        <main class="content">
            <section class="form-container">
                <h2 class="title_form">Marcar Consulta</h2>
                <form id="cadastroForm" action="salvar_consulta.php" method="post">
                    <section class="form-group">
                        <label class="label_form" for="paciente">Paciente:</label>
                        <select class="input_form" id="paciente" name="paciente_id" required>
                            <?php
                            if ($result_pacientes->num_rows > 0) {
                                while($row = $result_pacientes->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>Nenhum paciente cadastrado</option>";
                            }
                            ?>
                        </select>
                    </section>
                    <section class="form-group">
                        <label class="label_form" for="dentista">Dentista:</label>
                        <select class="input_form" id="dentista" name="dentista_id" required>
                            <?php
                            if ($result_dentistas->num_rows > 0) {
                                while($row = $result_dentistas->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>Nenhum dentista cadastrado</option>";
                            }
                            ?>
                        </select>
                    </section>
                    <section class="form-group">
                        <label class="label_form" for="data">Data da Consulta:</label>
                        <input class="input_form" type="date" id="data" name="data_consulta" required>
                    </section>
                    <section class="form-group">
                        <label class="label_form" for="metodo_pagamento">Método de Pagamento:</label>
                        <select class="input_form" id="metodo_pagamento" name="metodo_pagamento" required>
                            <option value="Cartão">Cartão</option>
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="PIX">PIX</option>
                        </select>
                    </section>
                    <section class="form-group">
                        <input class="btn_form confirm" type="submit" value="Marcar Consulta">
                    </section>
                </form>
            </section>
        </main>
    </body>
</html>
<?php
    $conn->close();
?>