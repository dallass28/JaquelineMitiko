<?php
session_start();
include_once("../connection/connection.php");

if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'usuario') {
    header('Location: index.html');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_consulta'])) {
        $id = $_POST['consulta_id'];
        $valor = $_POST['valor'];
        $metodo_pagamento = $_POST['metodo_pagamento'];

        $sql = "UPDATE consultas SET valor = ?, metodo_pagamento = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dsi", $valor, $metodo_pagamento, $id);
        $stmt->execute();
    } elseif (isset($_POST['delete_consulta'])) {
        $id = $_POST['consulta_id'];

        $sql = "DELETE FROM consultas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif (isset($_POST['update_status'])) {
        $id = $_POST['consulta_id'];
        $status = $_POST['status'];

        $sql = "UPDATE consultas SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
    }
}

$sql = "SELECT c.id, p.nome AS paciente, c.data_consulta, c.valor, c.metodo_pagamento, c.status, d.nome AS dentista 
        FROM consultas c 
        JOIN pacientes p ON c.paciente_id = p.id
        JOIN dentistas d ON c.dentista_id = d.id";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' type='text/css' media='screen' href='../../src/css/styles_default.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='../../src/css/styles_consul.css'>
    <title>Consultas Marcadas</title>
    <script>
        function toggleEditForm(id) {
            var form = document.getElementById('edit-form-' + id);
            form.classList.toggle('active');
        }
    </script>
</head>
<body>
    <header>
        <?php include_once("./header.html"); ?>
    </header>
    <section class="content">
        <h1 class="title">Consultas Marcadas</h1>
        <p class="sub_title">Lista de consultas marcadas.</p>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Data da Consulta</th>
                        <th>Valor</th>
                        <th>Método de Pagamento</th>
                        <th>Status</th>
                        <th>Dentista</th>
                        <th>Valor Final para o Dentista</th>
                        <th id="title_btns">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                            $valor_final = 0;
                            if ($row['metodo_pagamento'] == 'Dinheiro') {
                                $valor_final = $row['valor'] * 0.40;
                            } elseif ($row['metodo_pagamento'] == 'Cartao' || $row['metodo_pagamento'] == 'Pix') {
                                $valor_final = $row['valor'] * 0.35;
                            }
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['paciente']; ?></td>
                            <td><?php echo $row['data_consulta']; ?></td>
                            <td>R$ <?php echo number_format($row['valor'], 2, ',', '.'); ?></td>
                            <td><?php echo $row['metodo_pagamento']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['dentista']; ?></td>
                            <td>R$ <?php echo number_format($valor_final, 2, ',', '.'); ?></td>
                            <td id="cell_btns">
                                <button class="btn edit" onclick="toggleEditForm(<?php echo $row['id']; ?>)">Editar</button>
                                <form action="consultas_marcadas.php" method="post" style="display:inline;">
                                    <input type="hidden" name="consulta_id" value="<?php echo $row['id']; ?>">
                                    <input class="btn exclude" type="submit" name="delete_consulta" value="Excluir" onclick="return confirm('Tem certeza que deseja excluir esta consulta?');">
                                </form>
                                <form action="consultas_marcadas.php" method="post" class="situ" style="display:inline;" >
                                    <input type="hidden" name="consulta_id" value="<?php echo $row['id']; ?>">
                                    <?php if ($row['status'] == 'Pendente'): ?>
                                        <input type="hidden" name="status" value="Finalizada">
                                        <input class="btn" type="submit" name="update_status" value="Finalizar" class="sit" onclick="return confirm('Tem certeza que deseja finalizar esta consulta?');">
                                    <?php else: ?>
                                        <input type="hidden" name="status" value="Pendente">
                                        <input class="btn" type="submit" name="update_status" value="Marcar como Pendente" class="sit" onclick="return confirm('Tem certeza que deseja marcar esta consulta como pendente?');">
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                        <tr class="edit-form" id="edit-form-<?php echo $row['id']; ?>">
                            <td class="cell_form" colspan="9">
                                <form class="form_edit" action="consultas_marcadas.php" method="post">
                                    <section>
                                        <input type="hidden" name="consulta_id" value="<?php echo $row['id']; ?>">
                                        <label for="valor">Valor:</label>
                                        <input type="number" step="0.01" name="valor" value="<?php echo $row['valor']; ?>" required>
                                    </section>
                                    <section>
                                        <label for="metodo_pagamento">Pagamento:</label>
                                        <select name="metodo_pagamento" required>
                                            <option value="Dinheiro" <?php echo $row['metodo_pagamento'] == 'Dinheiro' ? 'selected' : ''; ?>>Dinheiro</option>
                                            <option value="Cartao" <?php echo $row['metodo_pagamento'] == 'Cartao' ? 'selected' : ''; ?>>Cartão</option>
                                            <option value="Pix" <?php echo $row['metodo_pagamento'] == 'Pix' ? 'selected' : ''; ?>>Pix</option>
                                        </select>
                                        <input type="submit" name="update_consulta" value="Salvar">
                                    </section>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma consulta marcada.</p>
        <?php endif; ?>
    </section>
</body>
</html>
