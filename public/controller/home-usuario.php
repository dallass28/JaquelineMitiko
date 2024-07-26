<?php
session_start();

// Verifica se o usuário está logado e se é um usuário comum
if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'usuario') {
    header('Location: index.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet' type='text/css' media='screen' href='../../src/css/styles_default.css'>
        <link rel='stylesheet' type='text/css' media='screen' href='../../src/css/styles_cad_pasc.css'>
        <title>Home - Usuário</title>
    </head>
    <body>
        <header>
            <?php
                include_once("./header.html");
            ?>
        </header>
        <main class="content">
            <section class="form-container">
                <h2 class="title_form">Cadastrar Paciente</h2>
                <form id="cadastroForm" action="cadastrar_paciente.php" method="post">
                    <section class="form-group">
                        <label class="label_form" for="nome">Nome:</label>
                        <input class="input_form" type="text" id="nome" name="nome" required>
                    </section>
                    <section class="form-group">
                        <label class="label_form" for="data_nascimento">Data de Nascimento:</label>
                        <input class="input_form" type="text" id="data_nascimento" name="data_nascimento" maxlength="10" required>
                    </section>
                    <section class="form-group">
                        <label class="label_form" for="telefone">Número de Telefone:</label>
                        <input class="input_form" type="text" id="telefone" name="telefone" maxlength="15" required>
                    </section>
                    <section class="form-group">
                        <label class="label_form" for="cpf">CPF:</label>
                        <input class="input_form" type="text" id="cpf" name="cpf" maxlength="14" required>
                    </section>
                    <section class="form-group">
                        <label class="label_form" for="endereco">Endereço:</label>
                        <input class="input_form" type="text" id="endereco" name="endereco" required>
                    </section>
                    <section class="form-group btn-group">
                        <input class="btn_form confirm" type="submit" value="Cadastrar">
                        <input class="btn_form clear" type="button" value="Limpar" onclick="clearForm()">
                    </section>
                </form>
            </section>
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