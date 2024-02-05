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
mysqli_query($conn, "SET NAMES 'utf8';");

// Atualiza o último acesso
$_SESSION['ultimo_acesso'] = time();

// Se houver uma mensagem na URL, exiba-a
if (isset($_GET['mensagem'])) {
    //echo '<div class="alert alert-success">' . htmlspecialchars//($_GET['mensagem']) . '</div>';
}

// Obtém o ID do contato da URL
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Processar a atualização do contato quando o formulário é enviado via POST
if ($_SERVER && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter os dados do formulário
    $data = [
        'ID' => filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT),
        'Nome' => filter_input(INPUT_POST, 'Nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        'Senha' => filter_input(INPUT_POST, 'Senha', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        'PermissaoAdmin' => filter_input(INPUT_POST, 'PermissaoAdmin', FILTER_SANITIZE_NUMBER_INT),
        'PermissaoEditar' => filter_input(INPUT_POST, 'PermissaoEditar', FILTER_SANITIZE_NUMBER_INT),
        'PermissaoExcluir' => filter_input(INPUT_POST, 'PermissaoExcluir', FILTER_SANITIZE_NUMBER_INT),
    ];

    // Atualizar o contato
    updateContato($conn, $data['ID'], $data);
}

// Consulta o banco de dados para obter os dados do contato pelo ID
$result_usuario = "SELECT * FROM usuarios WHERE ID = '$id'";
$resultado_usuario = mysqli_query($conn, $result_usuario);
$row_usuario = mysqli_fetch_assoc($resultado_usuario);

// Função para buscar dados do banco de dados dos usuários
function buscarDadosUsuarios($conn, $table, $field, $selectedValue)
{
    $table = 'usuarios';
    $dados = [];
    $sql = "SELECT ID, Nome, Senha, PermissaoAdmin, PermissaoEditar, PermissaoExcluir FROM $table";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $selected = ($selectedValue == $row["ID"] || $selectedValue == $row["Nome"] || $selectedValue == $row["Senha"] || $selectedValue == $row["PermissaoAdmin"] || $selectedValue == $row["PermissaoEditar"] || $selectedValue == $row["PermissaoExcluir"]) ? 'selected' : '';
            echo '<option value="' . $row["ID"] . '" ' . $selected . '>';
            echo 'ID: ' . $row["ID"] . ', Nome: ' . $row["Nome"] . ', Senha: ' . $row["Senha"] . ', PermissaoAdmin: ' . $row["PermissaoAdmin"] . ', PermissaoEditar: ' . $row["PermissaoEditar"] . ', PermissaoExcluir: ' . $row["PermissaoExcluir"];
            echo '</option>';
        }
    } else {
        echo '<option value="">Nenhum registro encontrado</option>';
    }
}
// Buscar dados do gerente pelo ID
function getContatoById($conn, $id)
{
    $sql = "SELECT * FROM usuarios WHERE ID = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function updateContato($conn, $id, $data)
{
    $query = "UPDATE usuarios SET Nome = ?, Senha = ?, PermissaoAdmin = ?, PermissaoEditar = ?, PermissaoExcluir = ? WHERE ID = ?";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bind_param("ssiii", $data['Nome'], $data['Senha'], $data['PermissaoAdmin'], $data['PermissaoEditar'], $data['PermissaoExcluir'], $id);

    // Execute statement
    $stmt->execute();

    // Check for errors
    if ($stmt->errno) {
        echo "Erro ao atualizar usuário: " . $stmt->error;
    } else {
        // Redireciona para a página de edição após a atualização
        header("Location: ../usuarios/editar_usuario.php?id=" . $id . "&mensagem=Usuário atualizado com sucesso!");
        exit(); // Certifica-se de que o script seja encerrado após o redirecionamento
    }

    // Close statement
    $stmt->close();
}
?>

<head>
    <!-- Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/ -->
    <title>Gerenciamento de Projetos</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Adicione os arquivos CSS do Bootstrap e DataTables -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
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
            <h2>Editar Usuário</h2>
            <form action="../usuarios/editar_usuario.php" method="POST">
                <div class="form-group">
                    <!-- Adicione um campo oculto para armazenar o ID do contato -->
                    <input type="hidden" name="ID" value="<?php echo $row_usuario['id']; ?>">
                    <label for="Nome">Nome:</label>
                    <input type="text" class="form-control" placeholder="Digite o Nome do Usuário" id="Nome" name="Nome"
                        onkeypress="return ApenasLetras(event,this);" required
                        value="<?php echo isset($row_usuario['Nome']) ? htmlspecialchars($row_usuario['Nome']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="Senha">Senha:</label>
                    <input type="password" class="form-control" placeholder="Digite a Senha Criptografada" id="Senha"
                        name="Senha" required value="<?php echo isset($row_usuario['Senha']) ?>">
                </div>

                <div class="form-group">
                    <label for="PermissaoAdmin">Admin:</label>
                    <input type="text" class="form-control" placeholder="Digite o tipo de Permissão" id="PermissaoAdmin"
                        name="PermissaoAdmin" required>
                </div>

                <div class="form-group">
                    <label for="PermissaoEditar">Editar:</label>
                    <input type="text" class="form-control" placeholder="Digite o tipo de Permissão"
                        id="PermissaoEditar" name="PermissaoEditar" required>
                </div>

                <div class="form-group">
                    <label for="PermissaoExcluir">Excluir:</label>
                    <input type="text" class="form-control" placeholder="Digite o tipo de Permissão"
                        id="PermissaoExcluir" name="PermissaoExcluir" required>
                </div>

                <p>
                <p>
                    <!-- Botão " Salvar" com ícone -->
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Atualizar
                    </button>

                    <!-- Botão "Editar" com ícone -->
                    <button type="button" class="btn btn-warning" id="btn-limpar">
                        <i class="fas fa-eraser"></i> Limpar
                    </button>

                    <!-- Botão "Cancelar" com ícone -->
                    <button type="button" class="btn btn-danger" id="btn-cancelar">
                        <i class="fas fa-times"></i> Cancelar
                    </button>

                    <script>
                        // Adiciona um evento de clique ao botão "Cancelar"
                        document.getElementById('btn-cancelar').addEventListener('click', function () {
                            // Redireciona para a página "consultar_contato.php"
                            window.location.href = '../usuarios/inserir_usuario.php';
                        });
                    </script>

                    <!-- Adicione um evento de clique ao botão "Limpar" -->
                    <script>
                        document.getElementById('btn-limpar').addEventListener('click', function () {
                            // Chame a função para limpar os campos
                            limparCampos();
                        });

                        // Função para limpar os campos do formulário
                        function limparCampos() {
                            document.getElementById('Nome').value = ''; // Limpa o campo "Nome"
                            document.getElementById('Senha').value = ''; // Limpa o campo "Telefone"
                            document.getElementById('PermissaoAdmin').value = ''; // Limpa o campo "E-mail"
                            document.getElementById('PermissaoEditar').value = ''; // Limpa o campo "Coordenadoria"
                            document.getElementById('PermissaoExcluir').value = ''; // Limpa o campo "Tipo de Contato"
                        }
                    </script>
            </form>
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
            // Inicializa o DataTables para a tabela de projetos
            $('#usuarios-table').DataTable();

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
</body>