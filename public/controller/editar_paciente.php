<?php
    session_start();
    include_once("../connection/connection.php");

    // Verifica se o usuário está logado e se é um usuário comum
    if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'usuario') {
        header('Location: index.html');
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Atualiza os dados do paciente
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $data_nascimento = $_POST['data_nascimento'];
        $telefone = $_POST['telefone'];
        $cpf = $_POST['cpf'];
        $endereco = $_POST['endereco'];

        $sql = "UPDATE pacientes SET nome='$nome', data_nascimento='$data_nascimento', telefone='$telefone', cpf='$cpf', endereco='$endereco' WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            echo "Paciente atualizado com sucesso!";
        } else {
            echo "Erro: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
        header('Location: pacientes_cadastrados.php');
        exit();
    } else {
        // Obtém os dados do paciente
        $id = $_GET['id'];
        $sql = "SELECT * FROM pacientes WHERE id='$id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet' type='text/css' media='screen' href='../../src/css/styles_default.css'>
        <link rel='stylesheet' type='text/css' media='screen' href='../../src/css/styles_cad_pasc.css'>
        <title>Editar Paciente</title>
    </head>
    <body>
        <main class="content">
            <section class="form-container">
                <h2 class="title_form">Editar Paciente</h2>
                <form id="cadastroForm" action="editar_paciente.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <section class="form-group">
                        <label class="label_form" for="nome">Nome:</label>
                        <input class="input_form" type="text" id="nome" name="nome" value="<?php echo $row['nome']; ?>" required>
                    </section>
                    <section class="form-group">
                        <label class="label_form" for="data_nascimento">Data de Nascimento:</label>
                        <input class="input_form" type="text" id="data_nascimento" name="data_nascimento" maxlength="10"  value="<?php echo $row['data_nascimento']; ?>" required>
                    </section>
                    <section class="form-group">
                        <label class="label_form" for="telefone">Número de Telefone:</label>
                        <input class="input_form" type="text" id="telefone" name="telefone" maxlength="15" value="<?php echo $row['telefone']; ?>" required>
                    </section>
                    <section class="form-group">
                        <label class="label_form" for="cpf">CPF:</label>
                        <input class="input_form" type="text" id="cpf" name="cpf" maxlength="14" value="<?php echo $row['cpf']; ?>" required>
                    </section>
                    <section class="form-group">
                        <label class="label_form" for="endereco">Endereço:</label>
                        <input class="input_form" type="text" id="endereco" name="endereco" value="<?php echo $row['endereco']; ?>" required>
                    </section>
                    <section class="form-group btn-group">
                        <input id="save" type="submit" value="Salvar">
                    </section>
                </form>
            </section>
            <a href="pacientes_cadastrados.php">Voltar</a>
        </main>
    </body>
</html>
<script>
    function clearForm() {
        document.getElementById('cadastroForm').reset();
    }

    const input_date = document.getElementById("data_nascimento");
    const input_tel = document.getElementById("telefone");
    const input_cpf = document.getElementById("cpf");

    input_cpf.addEventListener("keyup", formatarCPF);
    input_tel.addEventListener("keyup", formatarTel);
    input_date.addEventListener("keyup", formatarData);

    function formatarCPF(e){
        var v=e.target.value.replace(/\D/g,"");
        v=v.replace(/(\d{3})(\d)/,"$1.$2");
        v=v.replace(/(\d{3})(\d)/,"$1.$2");
        v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2");
        e.target.value = v;
    }

    function formatarTel(e){
        var v=e.target.value.replace(/\D/g,"");
        v=v.replace(/(\d{2})(\d)/,"($1) $2")
        v=v.replace(/(\d)(\d{4})$/,"$1-$2")
        e.target.value = v;
    }

    function formatarData(e){
        var v=e.target.value.replace(/\D/g,"");
        v=v.replace(/(\d{2})(\d)/,"$1/$2");
        v=v.replace(/(\d{2})(\d)/,"$1/$2"); 
        e.target.value = v;
    }
</script>
<?php
    $conn->close();
?>