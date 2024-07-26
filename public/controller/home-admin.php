<?php
session_start();
include_once("../connection/connection.php");

if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'admin') {
    header('Location: index.html');
    exit();
}

// Adicionar Despesa Externa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_despesa'])) {
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $data = $_POST['data'];

    $sql = "INSERT INTO despesas_externas (descricao, valor, data) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sds", $descricao, $valor, $data);
    $stmt->execute();
    
    // Redirecionar para evitar duplicação ao recarregar a página
    header('Location: home-admin.php');
    exit();
}

// Adicionar Despesa do Dentista
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_despesa_dentista'])) {
    $dentista_id = $_POST['dentista_id'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $data = $_POST['data'];

    $sql = "INSERT INTO despesas_dentista (dentista_id, descricao, valor, data) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isds", $dentista_id, $descricao, $valor, $data);
    $stmt->execute();
    
    // Redirecionar para evitar duplicação ao recarregar a página
    header('Location: home-admin.php');
    exit();
}

// Excluir Despesa Externa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_despesa'])) {
    $despesa_id = $_POST['despesa_id'];

    $sql = "DELETE FROM despesas_externas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $despesa_id);
    $stmt->execute();
    
    // Redirecionar para evitar duplicação ao recarregar a página
    header('Location: home-admin.php');
    exit();
}

// Excluir Despesa do Dentista
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_despesa_dentista'])) {
    $despesa_dentista_id = $_POST['despesa_dentista_id'];

    $sql = "DELETE FROM despesas_dentista WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $despesa_dentista_id);
    $stmt->execute();
    
    // Redirecionar para evitar duplicação ao recarregar a página
    header('Location: home-admin.php');
    exit();
}

// Excluir Todas as Consultas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_all_consultas'])) {
    $sql = "DELETE FROM consultas";
    $conn->query($sql);
    
    // Redirecionar para evitar duplicação ao recarregar a página
    header('Location: home-admin.php');
    exit();
}

// Excluir Todas as Despesas do Mês
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_all_despesas'])) {
    $sql = "DELETE FROM despesas_externas";
    $conn->query($sql);

    $sql = "DELETE FROM despesas_dentista";
    $conn->query($sql);
    
    // Redirecionar para evitar duplicação ao recarregar a página
    header('Location: home-admin.php');
    exit();
}

// Calcular Totais
$sql = "SELECT valor, metodo_pagamento, dentista_id FROM consultas";
$result = $conn->query($sql);

$total_recebido = 0;
$total_despesas = 0;
$dentistas = [];

while ($row = $result->fetch_assoc()) {
    $valor = $row['valor'];
    $metodo_pagamento = $row['metodo_pagamento'];
    $dentista_id = $row['dentista_id'];
    
    $total_recebido += $valor;
    
    if ($metodo_pagamento == 'Dinheiro') {
        $total_despesas += $valor * 0.60;
        $valor_dentista = $valor * 0.40;
    } else {
        $total_despesas += $valor * 0.65;
        $valor_dentista = $valor * 0.35;
    }
    
    if (!isset($dentistas[$dentista_id])) {
        $dentistas[$dentista_id] = ['total' => 0, 'despesas' => 0];
    }
    $dentistas[$dentista_id]['total'] += $valor_dentista;
}

$sql = "SELECT valor FROM despesas_externas";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $total_despesas += $row['valor'];
}

$sql = "SELECT dentista_id, valor FROM despesas_dentista";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $dentista_id = $row['dentista_id'];
    $valor_despesa = $row['valor'];
    
    if (isset($dentistas[$dentista_id])) {
        $dentistas[$dentista_id]['despesas'] += $valor_despesa;
    }
}

$total_liquido = $total_recebido - $total_despesas;
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet' type='text/css' media='screen' href='../../src/css/styles_default.css'>
        <link rel='stylesheet' type='text/css' media='screen' href='../../src/css/styles_adm.css'>
        <title>Home - Admin</title>
    </head>
    <body>
        <main class="content">
            <h1 class="title_form">Bem-vinda, Leticia!</h1>
            <section class="card_adm">
                <h2 class="title_form">Resumo Financeiro</h2>
                <section class="summary_datas">
                    <p class="indice">Total Recebido:</p>
                    <p class="ind_data">R$ <?php echo number_format($total_recebido, 2, ',', '.'); ?></p>
                </section>
                <section class="summary_datas">
                    <p class="indice">Total Despesas:</p>
                    <p class="ind_data">R$ <?php echo number_format($total_liquido, 2, ',', '.'); ?></p>
                </section>
                <section class="summary_datas total_summary">
                    <p class="indice">Total Líquido:</p>
                    <p class="ind_data total_liq">R$ <?php echo number_format($total_despesas, 2, ',', '.'); ?></p>
                </section>
            </section>
            <section class="card_adm">
                <h2 class="title_form">Adicionar Despesa Externa</h2>
                <form id="cadastroForm" action="home-admin.php" method="post">
                    <section class="form-group">
                        <label class="label_form" for="descricao">Descrição:</label>
                        <input class="input_form" type="text" name="descricao" required>
                    </section>
                    <section class="form-group">
                        <label class="label_form" for="valor">Valor:</label>
                        <input class="input_form" type="number" step="0.01" name="valor" required>
                    </section>
                    <section class="form-group">
                        <label class="label_form" for="data">Data:</label>
                        <input class="input_form" type="date" name="data" required>
                    </section>
                    <section class="form-group btn-group">
                        <input id="save" type="submit" name="add_despesa" value="Adicionar Despesa">
                    </section>
                </form>
            </section>
            <section class="card_adm">
                <h2 class="title_form">Adicionar Despesa do Dentista</h2>
                <form id="cadastroForm" action="home-admin.php" method="post">
                    <section class="form-group">
                        <label class="label_form" for="dentista_id">Dentista:</label>
                        <select class="input_form" name="dentista_id" required>
                            <?php
                            $sql = "SELECT id, nome FROM dentistas";
                            $result = $conn->query($sql);
                            
                            while ($row = $result->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nome']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </section>
                    <section class="form-group">
                        <label class="label_form" for="descricao">Descrição:</label>
                        <input class="input_form" type="text" name="descricao" required>
                    </section>
                    <section class="form-group">
                        <label class="label_form" for="valor">Valor:</label>
                        <input class="input_form" type="number" step="0.01" name="valor" required>
                    </section>
                    <section class="form-group">
                        <label class="label_form" for="data">Data:</label>
                        <input class="input_form" type="date" name="data" required>
                    </section>
                    <section class="form-group btn-group">
                        <input id="save" type="submit" name="add_despesa_dentista" value="Adicionar Despesa do Dentista">
                    </section>
                </form>
            </section>
            <section class="card_adm">
                <h2 class="title_form">Despesas Externas</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Data</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id, descricao, valor, data FROM despesas_externas";
                        $result = $conn->query($sql);
                        
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['descricao']); ?></td>
                                <td>R$ <?php echo number_format($row['valor'], 2, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($row['data']); ?></td>
                                <td>
                                    <form action="home-admin.php" method="post">
                                        <input type="hidden" name="despesa_id" value="<?php echo $row['id']; ?>">
                                        <input type="submit" name="delete_despesa" value="Excluir" class="btn_excluir" onclick="return confirm('Tem certeza que deseja excluir esta despesa?');">
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
            <section class="card_adm">
                <h2 class="title_form">Despesas do Dentista</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Dentista</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Data</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT despesas_dentista.id, dentistas.nome, despesas_dentista.descricao, despesas_dentista.valor, despesas_dentista.data 
                                FROM despesas_dentista 
                                INNER JOIN dentistas ON despesas_dentista.dentista_id = dentistas.id";
                        $result = $conn->query($sql);
                        
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nome']); ?></td>
                                <td><?php echo htmlspecialchars($row['descricao']); ?></td>
                                <td>R$ <?php echo number_format($row['valor'], 2, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($row['data']); ?></td>
                                <td>
                                    <form action="home-admin.php" method="post">
                                        <input type="hidden" name="despesa_dentista_id" value="<?php echo $row['id']; ?>">
                                        <input type="submit" name="delete_despesa_dentista" value="Excluir" class="btn_excluir" onclick="return confirm('Tem certeza que deseja excluir esta despesa?');">
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
            <section class="card_adm">
                <section class="card_valor_final">
                    <h2 class="title_form">Despesas por Dentista</h2>
                        <?php foreach ($dentistas as $dentista_id => $dados): ?>
                            <?php
                            $sql = "SELECT nome FROM dentistas WHERE id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $dentista_id);
                            $stmt->execute();
                            $stmt->bind_result($nome_dentista);
                            $stmt->fetch();
                            $stmt->close();
                            
                            $total_dentista = $dados['total'];
                            $total_despesas_dentista = $dados['despesas'];
                            $total_liquido_dentista = $total_dentista - $total_despesas_dentista;
                            ?>
                            <section class="summary dentista-summary">
                                <h3 class="title_form"><?php echo htmlspecialchars($nome_dentista); ?></h3>
                                <p class="indice">Total para o dentista: R$ <?php echo number_format($total_dentista, 2, ',', '.'); ?></p>
                                <p class="indice">Total de despesas: R$ <?php echo number_format($total_despesas_dentista, 2, ',', '.'); ?></p>
                                <p class="indice">Total líquido: R$ <?php echo number_format($total_liquido_dentista, 2, ',', '.'); ?></p>
                            </section>
                        <?php endforeach; ?>
                </section>
            </section>
            <section class="card_adm">
    <h2 class="title_form">Excluir Dados</h2>
    <form action="home-admin.php" method="post" onsubmit="return confirm('Tem certeza que deseja excluir todas as consultas marcadas?');">
        <section class="form-group btn-group">
            <input id="delete_all_consultas" type="submit" name="delete_all_consultas" value="Excluir Todas as Consultas" class="btn_excluir">
        </section>
    </form>
    <form action="home-admin.php" method="post" onsubmit="return confirm('Tem certeza que deseja excluir todas as despesas do mês?');">
        <section class="form-group btn-group">
            <input id="delete_all_despesas" type="submit" name="delete_all_despesas" value="Excluir Todas as Despesas do Mês" class="btn_excluir">
        </section>
    </form>
</section>

            <a id="a_btn_exit" href="logout.php"><div class="btn_exit_adm">Sair</div></a>
        </main>
    </body>
</html>
