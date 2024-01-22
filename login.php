<?php
// Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/
session_start(); // Inicie a sessão no início do seu script PHP

// Inicialize a variável $erro como falsa (nenhum erro ocorreu)
$erro = false;

// Conecte-se ao banco de dados (substitua as informações conforme necessário)
$host = "172.17.0.55";
$usuario_banco = "usu_gestao";
$senha_banco = "gst_db_user26g1";
$nome_banco = "gestao";

$conexao = new mysqli($host, $usuario_banco, $senha_banco, $nome_banco);

// Verifique a conexão
if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

if (isset($_POST['submit'])) {
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];

    // Consulta SQL para verificar as credenciais do usuário
    $consulta = "SELECT * FROM usuarios WHERE nome = ?";

    // Use instruções preparadas para evitar injeção de SQL
    $stmt = $conexao->prepare($consulta);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();

        // Verifique a senha usando password_verify
        if (password_verify($password, $row['senha'])) {
            // Usuário e senha corretos, redirecione
            $_SESSION['usuario'] = $username;
            $_SESSION['senha'] = $password;

            // Armazene o nome do usuário na sessão
            $_SESSION['usuario'] = $row['nome'];

            header("Location: index.php");
            exit();
        } else {
            // Senha incorreta
            $erro = true;
        }
    } else {
        // Usuário não encontrado
        $erro = true;
    }

    $stmt->close();
}

// Feche a conexão com o banco de dados
$conexao->close();
?>

<!DOCTYPE html>
<!-- Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/ -->
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="imagens/bi.ico">

    <title>Painel de Gestão de Projetos</title>

    <!-- Inclua o JavaScript do jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Link para o CSS do Bootstrap 3 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Estilos CSS inline para o logo */
        .navbar-brand img {
            max-width: 150px;
            /* Largura máxima do logo */
            height: auto;
            /* Altura automática para manter a proporção */
        }
    </style>

    <script>
        function validarFormulario() {
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;

            if (username === "" || password === "") {
                alert("Por favor, preencha todos os campos.");
                return false; // Impede o envio do formulário
            }
        }
    </script>

</head>

<body>
    <!-- Tela de Login -->
    <div class="container">
        <div class="jumbotron">
            <h1><i class="fas fa-chart-bar"></i> Painel de Gestão de Projetos</h1>
            <p>Por favor, faça login para acessar o painel.</p>
            <form method="post" onsubmit="return validarFormulario();">
                <div class="form-group">
                    <label for="username">Usuário:</label>
                    <input type="text" id="username" name="username" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password">Senha:</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Entrar</button>
                <?php if ($erro) { ?>
                    <!-- Exibe a mensagem de erro apenas quando $erro for verdadeiro -->
                    <p style="color: red;">Usuário e/ou senha inválidos. Tente novamente!</p>
                <?php } ?>

            </form>



        </div>
    </div>
    <!-- Inclua o JavaScript do Bootstrap (opcional) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Rodapé com imagem -->
    <div class="container">
        <img src="imagem\rodape_preto.png" alt="Rodapé" />
    </div>

</body>

</html>