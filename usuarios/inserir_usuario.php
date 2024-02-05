<?php
// Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/

// Função para conectar ao banco de dados
@session_start();
// Verifica se a sessão do usuário está ativa
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php"); // Redireciona para a página de login
    exit();
}

include_once("../config/conexao.php");

// Adicione a instrução SET NAMES 'utf8'; para garantir a codificação correta
//mysqli_query($conn, "SET NAMES 'utf8';");

// Atualiza o último acesso
$_SESSION['ultimo_acesso'] = time();

// Função para conectar ao banco de dados
function conectarBanco()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gestão";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Define o conjunto de caracteres
    mysqli_set_charset($conn, "utf8");

    return $conn;
}

// Adicione a instrução SET NAMES 'utf8'; para garantir a codificação correta
mysqli_query($conn, "SET NAMES 'utf8';");

// Se houver uma mensagem na URL, exiba-a
if (isset($_GET['mensagem'])) {
    //echo '<div class="alert alert-success">' . htmlspecialchars//($_GET['mensagem']) . '</div>';
}

// Função para buscar dados do banco de dados Usuários
function buscarDadosUsuarios($conn)
{
    $sql = "SELECT id, nome, senha, permissao_admin, permissao_editar, permissao_excluir FROM usuarios";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['nome'] . '</td>';
            echo '<td>' . $row['senha'] . '</td>';
            echo '<td>' . $row['permissao_admin'] . '</td>';
            echo '<td>' . $row['permissao_editar'] . '</td>';
            echo '<td>' . $row['permissao_excluir'] . '</td>';
            // Adicione outras colunas conforme necessário
            echo '<td><button class="btn btn-primary btn-visualizar" data-id="' . $row['id'] . '">Visualizar</button></td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="7">Nenhum registro encontrado</td></tr>';
    }
}

// Função para inserir um novo gerente
function inserirUsuario($conn, $data)
{
    $query = "INSERT INTO usuarios (Nome, Senha, Permissao_Admin, Permissao_Editar, Permissao_Excluir) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bind_param("ssiii", $data['Nome'], $data['Senha'], $data['PermissaoAdmin'], $data['PermissaoEditar'], $data['PermissaoExcluir']);

    // Execute statement
    $stmt->execute();

    // Check for errors
    if ($stmt->errno) {
        echo "Erro ao inserir usuário: " . $stmt->error;
    } else {
        // Redireciona para a página de consulta após a inserção
        header("Location: inserir_usuario.php?mensagem=Usuario inserido com sucesso!");
        exit(); // Certifica-se de que o script seja encerrado após o redirecionamento
    }

    // Close statement
    $stmt->close();
}

// Processar o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_inserir'])) {
    // Obter os dados do formulário
    $data = [
        'Nome' => filter_input(INPUT_POST, 'Nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        'Senha' => filter_input(INPUT_POST, 'Senha', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        'PermissaoAdmin' => filter_input(INPUT_POST, 'PermissaoAdmin', FILTER_SANITIZE_NUMBER_INT),
        'PermissaoEditar' => filter_input(INPUT_POST, 'PermissaoEditar', FILTER_SANITIZE_NUMBER_INT),
        'PermissaoExcluir' => filter_input(INPUT_POST, 'PermissaoExcluir', FILTER_SANITIZE_NUMBER_INT),
        // Adicione outras colunas conforme necessário
    ];

    // Inserir o novo usuário
    inserirUsuario(conectarBanco(), $data);
}
?>

<!DOCTYPE html>
<!-- Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/ -->
<html>

<head>
    <title>Gerenciamento de Projetos</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Adicione esta biblioteca para facilitar a formatação do telefone -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Link para o CSS do Bootstrap 3 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Link para o CSS da página -->
    <link rel="stylesheet" type="text/css" href="../stylesheet.css" media="screen" />

    <script>
        // Função para redirecionar após um período de tempo
        function redirecionarParaLogin() {
            setTimeout(function () {
                // Fazer uma chamada AJAX para logout.php
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "../logout.php", true);
                xhr.send();

                alert("Sua sessão expirou. Você será redirecionado para a página de login.");
                window.location.href = "../login.php"; // Redireciona para a página de login
            }, 1800000); // 1800000 milissegundos = 30 minutos (ajuste conforme necessário)
        }
    </script>

    <script>
        // Função para permitir apenas letras, espaços e acentuações no campo "Nome"
        function ApenasLetras(e) {
            try {
                var charCode = (typeof e.which === "undefined") ? e.keyCode : e.which;

                // Permite letras, espaços e acentuações
                if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || charCode === 32 || (charCode >= 192 && charCode <= 255)) {
                    return true;
                } else {
                    return false;
                }
            } catch (err) {
                alert(err.Description);
            }
        }
    </script>

</head>

<body onload="redirecionarParaLogin()">
    <!-- Cabeçalho (Logotipo e Menu) -->
    <header>
        <div class="main mx-auto tabela-projetos">
            <div class="row">
                <div class="col-md-2">
                    <div class="logo-container">
                        <img src="../imagem/logo-governo-do-estado-sp.png">
                    </div>
                </div>
                <div class="col-md-10">
                    <nav class="navbar">
                        <ul class="nav navbar-nav">
                            <li><a href="../index.php"><i class="fas fa-home"></i>Página Inicial</a></li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-tasks"></i>Projetos
                                    <ul class="submenu">
                                        <li><a href="../projetos/inserir_projeto.php">Cadastrar um novo Projeto</a></li>
                                        <li><a href="../projetos/consultar_projeto.php">Consultar ou Editar Projeto</a>
                                        </li>
                                    </ul>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-clipboard"></i>Demandas
                                    <ul class="submenu">
                                        <li><a href="../demandas/inserir_demanda.php">Cadastrar uma Demanda</a></li>
                                        <li><a href="../demandas/consultar_demanda.php">Consultar ou Editar Demanda</a>
                                        </li>
                                    </ul>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-box"></i>Entregas
                                    <ul class="submenu">
                                        <li><a href="../entregas/inserir_entrega.php">Cadastrar uma Entrega</a>
                                        </li>
                                        <li><a href="../entregas/consultar_entrega.php">Consultar ou Editar Entrega</a>
                                        </li>
                                    </ul>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-pencil-alt"></i>Atividades
                                    <ul class="submenu">
                                        <li><a href="../atividades/inserir_atividade.php">Cadastrar uma Atividade</a>
                                        </li>
                                        <li><a href="../atividades/consultar_atividade.php">Consultar ou Editar
                                                Atividade</a>
                                        </li>
                                    </ul>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-chart-bar"></i>Dashboards
                                    <ul class="submenu">
                                        <li><a href="../dashboards/indicadores1.php">Painel de Indicadores 1</a></li>
                                        <li><a href="../dashboards/indicadores2.php">Painel de Indicadores 2</a></li>
                                        <li><a href="../dashboards/indicadores3.php">Painel de Indicadores 3</a></li>
                                    </ul>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-envelope"></i>Contatos
                                    <ul class="submenu">
                                        <li><a href="../contatos/inserir_contato.php">Cadastrar um novo Contato</a></li>
                                        <li><a href="../contatos/consultar_contato.php">Consultar ou Editar Contato</a>
                                        </li>
                                        <li><a href="#">Lembretes via E-mail</a></li>
                                    </ul>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div align="right">
                        Usuário Logado:
                        <?php echo $_SESSION['usuario']; ?>
                        <a href="../logout.php" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </header>


    <div class="container">
        <!-- Conteúdo principal -->
        <div class="main tabela-projetos">
            <?php
            // Exibe a mensagem acima do título
            if (isset($_GET['mensagem'])) {
                echo '<div class="alert alert-success">' . htmlspecialchars($_GET['mensagem']) . '</div>';
            }
            ?>
            <h2>Inserir Usuário</h2>
            <form action="../usuarios/inserir_usuario.php" method="POST">
                <div class="form-group">
                    <label for="Nome">Nome:</label>
                    <input type="text" class="form-control" placeholder="Digite o Nome do Usuário" id="Nome" name="Nome"
                        onkeypress="return ApenasLetras(event,this);" required>
                </div>

                <div class="form-group">
                    <label for="Senha">Senha:</label>
                    <input type="password" class="form-control" placeholder="Digite a Senha Criptografada" id="Senha"
                        name="Senha" required>
                </div>

                <div class="form-group">
                    <label for="PermissaoAdmin">Admin:</label>
                    <input type="text" class="form-control" placeholder="Digite sim ou não para a permissão"
                        id="PermissaoAdmin" name="PermissaoAdmin" required>
                </div>

                <div class="form-group">
                    <label for="PermissaoEditar">Editar:</label>
                    <input type="text" class="form-control" placeholder="Digite sim ou não para a permissão"
                        id="PermissaoEditar" name="PermissaoEditar" required>
                </div>

                <div class="form-group">
                    <label for="PermissaoExcluir">Excluir:</label>
                    <input type="text" class="form-control" placeholder="Digite sim ou não para a permissão"
                        id="PermissaoExcluir" name="PermissaoExcluir" required>
                </div>


                <!-- Botão "Inserir Gerente" -->
                <button type="submit" name="submit_inserir" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Inserir Usuário
                </button>

                <!-- Botão "Editar" com ícone -->
                <button type="button" class="btn btn-warning" id="btn-limpar">
                    <i class="fas fa-eraser"></i> Limpar
                </button>
                <p>

                    <!-- Adicione um evento de clique ao botão "Editar" -->
                    <script>
                        document.getElementById('btn-limpar').addEventListener('click', function () {
                            // Chame a função para limpar os campos
                            limparCampos();
                        });

                        // Função para limpar os campos do formulário
                        function limparCampos() {
                            document.getElementById('Nome').value = ''; // Limpa o campo "Nome"
                            document.getElementById('Senha').value = ''; // Limpa o campo "Senha"
                            document.getElementById('PermissaoAdmin').value = ''; // Limpa o campo "Admin"
                            document.getElementById('PermissaoEditar').value = ''; // Limpa o campo "Editar"
                            document.getElementById('PermissaoExcluir').value = ''; // Limpa o campo "Excluir"
                        }
                    </script>
            </form>

            <!-- Tabela de Gerentes -->
            <h2>Tabela de Usuários</h2>
            <table id="tabela-usuario" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Senha</th>
                        <th>Admin</th>
                        <th>Editar</th>
                        <th>Excluir</th>
                        <th>Ações</th> <!-- Nova coluna para o botão "Visualizar" -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Buscar dados de gerentes usando a função
                    buscarDadosUsuarios(conectarBanco());
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Rodapé -->
    <footer>
        <!-- Rodapé com imagem -->
        <div class="container main">
            <img src="..\imagem\rodape_preto.png" alt="Rodapé" />
        </div>
    </footer>

    <!-- Scripts JavaScript (incluindo jQuery e Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            // Inicializa o DataTables para a tabela de gerentes
            $('#tabela-usuario').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json"
                }
            });

            // Adiciona um ouvinte de evento para os botões "Visualizar"
            $('.btn-visualizar').on('click', function () {
                // Obtém o ID do contato da linha da tabela
                var usuarioID = $(this).data('id');

                // Redireciona para a página de visualização com o ID
                window.location.href = '../usuarios/editar_usuario.php?id=' + usuarioID;
            });
        });
    </script>
</body>

</html>