<?php
    session_start();
    include_once("../connection/connection.php");

    if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'usuario') {
        header('Location: index.html');
        exit();
    }

    $action = $_GET['action'] ?? 'add';
    $id = $_GET['id'] ?? '';

    if ($action === 'edit' && !empty($id)) {
        $sql = "SELECT * FROM dentistas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dentista = $result->fetch_assoc();
        $nome = $dentista['nome'];
    } else {
        $nome = '';
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $action === 'edit' ? 'Editar' : 'Adicionar'; ?> Dentista</title>
        <link rel='stylesheet' type='text/css' media='screen' href='../../src/css/styles_default.css'>
        <link rel='stylesheet' type='text/css' media='screen' href='../../src/css/styles_cad_pasc.css'>
    </head>
    <body>
        <header>
            <?php
                include_once("./header.html");
            ?>
        </header>
        <main class="content">
            <section class="form-container">
                <h1 class="title_form"><?php echo $action === 'edit' ? 'Editar' : 'Adicionar'; ?> Dentista</h1>
                <form id="cadastroForm" action="dentista_save.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <section class="form-group">
                        <label class="label_form" for="nome">Nome:</label>
                        <input class="input_form" type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
                    </section>
                    <section class="form-group">
                        <input id="save" type="submit" value="<?php echo $action === 'edit' ? 'Atualizar' : 'Adicionar'; ?>">
                    </section>
                </form>
            </section>     
            <a href="dentistas_list.php">Voltar</a> 
        </main>
    </body>
</html>
<?php
    $conn->close();
?>  