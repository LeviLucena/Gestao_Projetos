<?php
// Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/

// Função para conectar ao banco de dados
@session_start();
// Verifica se a sessão do usuário está ativa
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php"); // Redireciona para a página de login
    exit();
}

// Função para conectar ao banco de dados
function conectarBanco()
{
    $servername = "172.17.0.55";
    $username = "usu_gestao";
    $password = "gst_db_user26g1";
    $dbname = "gestao";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Define o conjunto de caracteres
    mysqli_set_charset($conn, "utf8");

    return $conn;
}

// Adicione a instrução SET NAMES 'utf8'; para garantir a codificação correta
//mysqli_query($conn, "SET NAMES 'utf8';");

// Se houver uma mensagem na URL, exiba-a
if (isset($_GET['mensagem'])) {
    //echo '<div class="alert alert-success">' . htmlspecialchars//($_GET['mensagem']) . '</div>';
}

// Função para buscar dados do banco de dados Situação
function buscarDadosSituacao($conn, $table, $field)
{
    $table = 'situacao';
    $sql = "SELECT ID, Nome FROM $table";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row["ID"] . '">' . $row["Nome"] . '</option>';
        }
    } else {
        echo '<option value="">Nenhum registro encontrado</option>';
    }
}

// Função para buscar dados do banco de dados Gerente
function buscarDadosGerente($conn, $table, $field)
{
    $table = 'gerente_projeto';
    $sql = "SELECT ID, Nome FROM $table";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row["ID"] . '">' . $row["Nome"] . '</option>';
        }
    } else {
        echo '<option value="">Nenhum registro encontrado</option>';
    }
}

// Função para buscar dados do banco de dados Tipo do Projeto
function buscarDadosTipoProjeto($conn, $table, $field)
{
    $table = 'tipo_projeto';
    $sql = "SELECT ID, Nome FROM $table";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row["ID"] . '">' . $row["Nome"] . '</option>';
        }
    } else {
        echo '<option value="">Nenhum registro encontrado</option>';
    }
}

// Função para buscar dados do banco de dados Subtipo do Projeto
function buscarDadosSubtipoProjeto($conn, $table, $field)
{
    $table = 'subtipo_projeto';
    $sql = "SELECT ID, Nome FROM $table";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row["ID"] . '">' . $row["Nome"] . '</option>';
        }
    } else {
        echo '<option value="">Nenhum registro encontrado</option>';
    }
}

// Função para buscar dados do banco de dados Nível de Prioridade do Projeto
function buscarDadosNivelPrioridadeProjeto($conn, $table, $field)
{
    $table = 'nivel_prioridade_projeto';
    $sql = "SELECT ID, Nome FROM $table";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row["ID"] . '">' . $row["Nome"] . '</option>';
        }
    } else {
        echo '<option value="">Nenhum registro encontrado</option>';
    }
}

// Função para buscar dados do banco de dados Criticidade
function buscarDadosCriticidade($conn, $table, $field)
{
    $table = 'criticidade';
    $sql = "SELECT ID, Nome FROM $table";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row["ID"] . '">' . $row["Nome"] . '</option>';
        }
    } else {
        echo '<option value="">Nenhum registro encontrado</option>';
    }
}

// Função para buscar dados do banco de dados Coordenadoria
function buscarDadosCoordenadoria($conn, $table, $field)
{
    $table = 'coordenadoria';
    $sql = "SELECT ID, Nome FROM $table";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row["ID"] . '">' . $row["Nome"] . '</option>';
        }
    } else {
        echo '<option value="">Nenhum registro encontrado</option>';
    }
}

// Função para buscar dados do banco de dados Contatos Técnicos
function buscarDadosContatos($conn, $table, $field)
{
    $table = 'contatos_tecnicos';
    $sql = "SELECT ID, Nome FROM $table";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row["ID"] . '">' . $row["Nome"] . '</option>';
        }
    } else {
        echo '<option value="">Nenhum registro encontrado</option>';
    }
}


// Função para inserir um novo contato
function inserirContato($conn, $data)
{
    $query = "INSERT INTO projetos (Nome, Objetivo, Data_Inicio, Prazo_Estimado, Situacao_ID, Tipo_Projeto_ID, Subtipo_Projeto_ID, Nivel_Prioridade_Projeto_ID, Criticidade_ID, Coordenadoria_ID, Orcamento_Previsto, Gerente_Projeto_ID, Contatos_Tecnicos_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bind_param("ssssiiiiidsss", $data['Nome'], $data['Objetivo'], $data['data_inicio'], $data['Prazo_Estimado'], $data['Situacao'], $data['Tipo_do_Projeto'], $data['Subtipo_do_Projeto'], $data['Nivel_de_Prioridade'], $data['Criticidade'], $data['Coordenadoria'], $data['Orcamento_Previsto'], $data['Gerente_do_Projeto'], $data['Contatos_Tecnicos']);

    // Execute statement
    $stmt->execute();

    // Check for errors
    if ($stmt->errno) {
        echo "Erro ao inserir projeto: " . $stmt->error;
    } else {
        // Redireciona para a página de consulta após a inserção
        header("Location: inserir_projeto.php?mensagem=Projeto inserido com sucesso!");
        exit(); // Certifica-se de que o script seja encerrado após o redirecionamento
    }

    // Close statement
    $stmt->close();
}

// Processar o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_inserir'])) {
    // Obter os dados do formulário
    $data = [
        'Nome' => filter_input(INPUT_POST, 'Nome', FILTER_SANITIZE_STRING),
        'Objetivo' => filter_input(INPUT_POST, 'Objetivo', FILTER_SANITIZE_STRING),
        'data_inicio' => filter_input(INPUT_POST, 'data_inicio', FILTER_SANITIZE_NUMBER_INT),
        'Prazo_Estimado' => filter_input(INPUT_POST, 'Prazo_Estimado', FILTER_SANITIZE_NUMBER_INT),
        'Situacao' => filter_input(INPUT_POST, 'Situacao', FILTER_SANITIZE_NUMBER_INT),
        'Tipo_do_Projeto' => filter_input(INPUT_POST, 'Tipo_do_Projeto', FILTER_SANITIZE_NUMBER_INT),
        'Subtipo_do_Projeto' => filter_input(INPUT_POST, 'Subtipo_do_Projeto', FILTER_SANITIZE_NUMBER_INT),
        'Nivel_de_Prioridade' => filter_input(INPUT_POST, 'Nivel_de_Prioridade', FILTER_SANITIZE_NUMBER_INT),
        'Criticidade' => filter_input(INPUT_POST, 'Criticidade', FILTER_SANITIZE_NUMBER_INT),
        'Coordenadoria' => filter_input(INPUT_POST, 'Coordenadoria', FILTER_SANITIZE_NUMBER_INT),
        'Orcamento_Previsto' => formatarReaisParaNumero(filter_input(INPUT_POST, 'Orcamento_Previsto')),
        'Gerente_do_Projeto' => filter_input(INPUT_POST, 'Gerente_do_Projeto', FILTER_SANITIZE_NUMBER_INT),
        'Contatos_Tecnicos' => filter_input(INPUT_POST, 'Contatos_Tecnicos_ID', FILTER_SANITIZE_NUMBER_INT),
    ];

    // Inserir o novo contato
    inserirContato(conectarBanco(), $data);
}

// Função para formatar o valor monetário para número
function formatarReaisParaNumero($valorReais)
{
    // Remove caracteres não numéricos
    $valor = preg_replace('/[^0-9]/', '', $valorReais);

    // Converte o valor para número
    return floatval($valor) / 100;
}
?>

<!DOCTYPE html>
<html>
<!-- Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/ -->

<head>
    <title>Gerenciamento de Projetos</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
            <h2>Inserir Projeto</h2>
            <form action="../projetos/inserir_projeto.php" method="POST">
                <div class="form-group">
                    <label for="Nome">Nome do Projeto:</label>
                    <input type="text" class="form-control" placeholder="Digite o Nome do Projeto" id="Nome" name="Nome"
                        required>
                </div>
                <div class="form-group">
                    <label for="Objetivo">Objetivo:</label>
                    <input type="text" class="form-control" placeholder="Digite o Objetivo do Projeto" id="Objetivo"
                        name="Objetivo" required>
                </div>
                <div class="form-group">
                    <label for="Situacao">Situação:</label>
                    <select class="form-control" id="Situacao" name="Situacao" required>
                        <option value="">--- Selecione uma Situação ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosSituacao($conn, "situacao", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Tipo_do_Projeto">Tipo do Projeto:</label>
                    <select class="form-control" id="Tipo do Projeto" name="Tipo do Projeto" required>
                        <option value="">--- Selecione um Tipo ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosTipoProjeto($conn, "tipo_projeto", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Subtipo_do_Projeto">Subtipo do Projeto:</label>
                    <select class="form-control" id="Subtipo_do_Projeto" name="Subtipo_do_Projeto" required>
                        <option value="">--- Selecione um Subtipo ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosSubtipoProjeto($conn, "subtipo_projeto", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Nivel_de_Prioridade">Nível de Prioridade:</label>
                    <select class="form-control" id="Nivel_de_Prioridade" name="Nivel_de_Prioridade" required>
                        <option value="">--- Selecione um Nível ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosNivelPrioridadeProjeto($conn, "nivel_prioridade_projeto", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Criticidade">Criticidade:</label>
                    <select class="form-control" id="Criticidade" name="Criticidade" required>
                        <option value="">--- Selecione uma Criticidade ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosCriticidade($conn, "criticidade", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Coordenadoria">Coordenadoria:</label>
                    <select class="form-control" id="Coordenadoria" name="Coordenadoria" required>
                        <option value="">--- Selecione uma Coordenadoria ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosCoordenadoria($conn, "coordenadoria", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="data_inicio">Data de Início:</label>
                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" required>
                </div>
                <div class="form-group">
                    <label for="Prazo_Estimado">Prazo Estimado:</label>
                    <input type="date" class="form-control" id="Prazo_Estimado" name="Prazo_Estimado">
                </div>
                <div class="form-group">
                    <label for="Orcamento_Previsto">Orçamento Previsto:</label>
                    <input type="text" class="form-control" id="Orcamento_Previsto" name="Orcamento_Previsto"
                        oninput="formatarReais(this)">

                    <script>
                        function formatarReais(input) {
                            // Remove caracteres não numéricos
                            let valor = input.value.replace(/\D/g, '');

                            // Converte o valor para número
                            valor = parseFloat(valor) / 100;

                            // Formata o valor como reais (R$) com ponto como separador de milhares
                            const formatter = new Intl.NumberFormat('pt-BR', {
                                style: 'currency',
                                currency: 'BRL',
                                minimumFractionDigits: 2,
                            });

                            // Atualiza o valor no campo de entrada
                            input.value = formatter.format(valor);
                        }
                    </script>
                </div>
                <div class="form-group">
                    <label for="Gerente_do_Projeto">Gerente do Projeto:
                        <a href="../gerente/inserir_gerente.php" class="btn btn-secondary">
                            Não encontrou o gerente? Insira um novo gerente
                        </a>
                    </label>

                    <select class="form-control" id="Gerente_do_Projeto" name="Gerente_do_Projeto" required>
                        <option value="">--- Selecione um Gerente ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosGerente($conn, "gerente_projeto", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Gerente_do_Projeto">Contato Técnico:</label>

                    <select class="form-control" id="Contatos_Tecnicos" name="Contatos_Tecnicos" required>
                        <option value="">--- Selecione um Contato ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosContatos($conn, "contatos_tecnicos", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>

                <!-- Botão "Inserir Contato" com ícone -->
                <button type="submit" name="submit_inserir" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Inserir Projeto
                </button>

                <!-- Botão "Editar" com ícone -->
                <button type="button" class="btn btn-warning" id="btn-limpar">
                    <i class="fas fa-eraser"></i> Limpar
                </button>

                <!-- Adicione um evento de clique ao botão "Editar" -->
                <script>
                    document.getElementById('btn-limpar').addEventListener('click', function () {
                        // Chame a função para limpar os campos
                        limparCampos();
                    });

                    // Função para limpar os campos do formulário
                    function limparCampos() {
                        document.getElementById('Nome').value = ''; // Limpa o campo "Nome do Projeto"
                        document.getElementById('Objetivo').value = ''; // Limpa o campo "Objetivo"
                        document.getElementById('Situacao').selectedIndex = 0; // Reseta o campo "Situação"
                        document.getElementById('Tipo do Projeto').selectedIndex = 0; // Reseta o campo "Tipo do Projeto"
                        document.getElementById('Subtipo_do_Projeto').selectedIndex = 0; // Reseta o campo "Subtipo do Projeto"
                        document.getElementById('Nivel_de_Prioridade').selectedIndex = 0; // Reseta o campo "Nível de Prioridade"
                        document.getElementById('Criticidade').selectedIndex = 0; // Reseta o campo "Criticidade"
                        document.getElementById('Coordenadoria').selectedIndex = 0; // Reseta o campo "Coordenadoria"
                        document.getElementById('data_inicio').value = ''; // Limpa o campo "Data de Início"
                        document.getElementById('Prazo_Estimado').value = ''; // Limpa o campo "Prazo Estimado"
                        document.getElementById('Orcamento_Previsto').value = ''; // Limpa o campo "Orçamento Previsto"
                        document.getElementById('Gerente_do_Projeto').selectedIndex = 0; // Reseta o campo "Gerente do Projeto"
                        document.getElementById('Contatos_Tecnicos').value = ''; // Limpa o campo "Contatos Tecnicos"
                    }
                </script>

                <p>
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