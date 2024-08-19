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
        'Nome' => filter_input(INPUT_POST, 'Nome', FILTER_SANITIZE_STRING),
        'Telefone' => filter_input(INPUT_POST, 'Telefone', FILTER_SANITIZE_STRING),
        'Email' => filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_EMAIL),
        'Coordenadoria' => filter_input(INPUT_POST, 'Coordenadoria', FILTER_SANITIZE_NUMBER_INT),
        'Contatos' => filter_input(INPUT_POST, 'Contatos', FILTER_SANITIZE_NUMBER_INT),
    ];

    // Atualizar o contato
    updateContato($conn, $data['ID'], $data);
}

// Consulta o banco de dados para obter os dados do contato pelo ID
$result_usuario = "SELECT * FROM contatos_tecnicos WHERE ID = '$id'";
$resultado_usuario = mysqli_query($conn, $result_usuario);
$row_usuario = mysqli_fetch_assoc($resultado_usuario);

// Função para buscar dados do banco de dados das Coordenadorias
function buscarDadosCoordenadoria($conn, $table, $field, $selectedValue)
{
    $table = 'coordenadoria';
    $sql = "SELECT ID, Nome FROM $table";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $selected = ($selectedValue == $row["ID"]) ? 'selected' : '';
            echo '<option value="' . $row["ID"] . '" ' . $selected . '>' . $row["Nome"] . '</option>';
        }
    } else {
        echo '<option value="">Nenhum registro encontrado</option>';
    }
}

// Função para buscar dados do banco de dados dos Tipos de Contatos
function buscarDadosContatos($conn, $table, $field, $selectedValue)
{
    $table = 'tipos_contatos';
    $sql = "SELECT ID, Nome FROM $table";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $selected = ($selectedValue == $row["ID"]) ? 'selected' : '';
            echo '<option value="' . $row["ID"] . '" ' . $selected . '>' . $row["Nome"] . '</option>';
        }
    } else {
        echo '<option value="">Nenhum registro encontrado</option>';
    }
}

// Função para obter os dados do contato pelo ID
function getContatoById($conn, $id)
{
    $sql = "SELECT * FROM contatos_tecnicos WHERE ID = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function updateContato($conn, $id, $data)
{
    $query = "UPDATE contatos_tecnicos SET Nome = ?, Telefone = ?, Email = ?, Coordenadorias_ID = ?, Tipos_Contatos_ID = ? WHERE ID = ?";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bind_param("sssssi", $data['Nome'], $data['Telefone'], $data['Email'], $data['Coordenadoria'], $data['Contatos'], $id);

    // Execute statement
    $stmt->execute();

    // Check for errors
    if ($stmt->errno) {
        echo "Erro ao atualizar contato: " . $stmt->error;
    } else {
        // Redireciona para a página de edição após a atualização
        header("Location: ../contatos/editar_contato.php?id=" . $id . "&mensagem=Contato atualizado com sucesso!");
        exit(); // Certifica-se de que o script seja encerrado após o redirecionamento
    }

    // Close statement
    $stmt->close();
}

?>

<!DOCTYPE html>
<html>
<!-- Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/ -->
<!-- Cabeçalho, Meta tags e Links para CSS e Scripts -->

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

    <!-- Script para permitir apenas letras, espaços e acentuações no campo "Nome" -->
    <script>
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

    <!-- Script para formatar o número de telefone -->
    <script>
        $(document).ready(function () {
            // Adiciona um ouvinte de evento para o campo de telefone
            $("#Telefone").on("input", function () {
                // Obtém o valor atual do campo de telefone
                let telefone = $(this).val();

                // Remove caracteres não numéricos do valor
                telefone = telefone.replace(/\D/g, "");

                // Formata o número de telefone
                if (telefone.length === 11) {
                    telefone = telefone.replace(/^(\d{2})(\d{5})(\d{4})$/, "$1 $2-$3");
                }

                // Define o valor formatado de volta no campo
                $(this).val(telefone);
            });
        });
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
            <h2>Editar Contato</h2>
            <form action="../contatos/editar_contato.php" method="POST">
                <div class="form-group">
                    <!-- Adicione um campo oculto para armazenar o ID do contato -->
                    <input type="hidden" name="ID" value="<?php echo $row_usuario['ID']; ?>">
                    <label for="Nome">Nome:</label>
                    <input type="text" class="form-control" placeholder="Digite o Nome Completo" id="Nome" name="Nome"
                        onkeypress="return ApenasLetras(event,this);" required
                        value="<?php echo isset($row_usuario['Nome']) ? htmlspecialchars($row_usuario['Nome']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="Telefone">Telefone:</label>
                    <?php
                    // Pré-processamento do número de telefone
                    $telefoneFormatado = isset($row_usuario['Telefone']) ? formatarTelefone($row_usuario['Telefone']) : '';
                    ?>
                    <input type="tel" class="form-control" placeholder="Digite o Número de Telefone" id="Telefone"
                        name="Telefone" pattern="[0-9\s-]+$" maxlength="13" required
                        title="Digite um número de telefone válido (pode conter dígitos, espaços e hífens)"
                        value="<?php echo $telefoneFormatado; ?>">
                </div>
                <?php
                // Função para formatar o número de telefone
                function formatarTelefone($telefone)
                {
                    // Remove caracteres não numéricos e espaços do valor
                    $telefone = preg_replace('/\D/', '', $telefone);

                    // Formata o número de telefone
                    if (strlen($telefone) === 11) {
                        $telefone = preg_replace('/^(\d{2})(\d{5})(\d{4})$/', '$1 $2-$3', $telefone);
                    }

                    return $telefone;
                }
                ?>
                <div class="form-group">
                    <label for="Email">E-mail:</label>
                    <input type="text" class="form-control" placeholder="Digite o nome do E-mail" id="Email"
                        name="Email"
                        pattern="/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/"
                        required value="<?php echo isset($row_usuario['Email']) ? $row_usuario['Email'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="Coordenadoria">Coordenadoria:</label>
                    <select class="form-control" id="Coordenadoria" name="Coordenadoria" required>
                        <?php buscarDadosCoordenadoria($conn, "coordenadoria", "Nome", $row_usuario['Coordenadorias_ID']); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Contatos">Tipo de Contato:</label>
                    <select class="form-control" id="Contatos" name="Contatos" required>
                        <?php buscarDadosContatos($conn, "tipos_contatos", "Nome", $row_usuario['Tipos_Contatos_ID']); ?>
                    </select>
                </div>
                <!-- Botão "Salvar" com ícone -->
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
                        window.location.href = '../contatos/consultar_contato.php';
                    });
                </script>
                <p>

                    <!-- Adicione um evento de clique ao botão "Limpar" -->
                    <script>
                        document.getElementById('btn-limpar').addEventListener('click', function () {
                            // Chame a função para limpar os campos
                            limparCampos();
                        });

                        // Função para limpar os campos do formulário
                        function limparCampos() {
                            document.getElementById('Nome').value = ''; // Limpa o campo "Nome"
                            document.getElementById('Telefone').value = ''; // Limpa o campo "Telefone"
                            document.getElementById('Email').value = ''; // Limpa o campo "E-mail"
                            document.getElementById('Coordenadoria').value = ''; // Limpa o campo "Coordenadoria"
                            document.getElementById('Contatos').value = ''; // Limpa o campo "Tipo de Contato"
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
</body>

</html>