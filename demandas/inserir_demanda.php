<?php
// Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/

// Função para conectar ao banco de dados
@session_start();
// Verifica se a sessão do usuário está ativa
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php"); // Redireciona para a página de login
    exit();
}

//include_once("config/conexao.php");
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

// Função para buscar dados do banco de dados Status Demanda
function buscarDadosStatusDemanda($conn, $table, $field)
{
    $table = "status_demanda";
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

// Função para buscar dados do banco de dados Tipo Demanda
function buscarDadosTipoDemanda($conn, $table, $field)
{
    $table = "tipo_demanda";
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

// Função para buscar dados do banco de dados dos Projetos
function buscarDadosProjetos($conn, $table, $field)
{
    $table = "projetos";
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

// Função para buscar dados do banco de dados das Coordenadorias
function buscarDadosCoordenadoria($conn, $table, $field)
{
    $table = "coordenadoria";
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

// Função para inserir uma nova demanda
function inserirContato($conn, $data)
{
    $query = "INSERT INTO cadastro_da_demanda (Numero_da_Demanda, Contrato, Descricao, Solicitante, Projetos_ID, Coordenadoria_ID, Status_demanda_ID, Tipo_demanda_ID, Criticidade_ID, Valor_demanda, Data_Solicitacao, Data_Aprovacao, Previsao_Termino) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    $stmt->bind_param("sssssssssssis", $data['Numero_da_Demanda'], $data['Numero_do_Contrato'], $data['Descricao'], $data['Solicitante'], $data['Projetos'], $data['Coordenadoria'], $data['Status_da_Demanda'], $data['Tipo_da_Demanda'], $data['Criticidade_ID'], $data['Valor_da_Demanda'], $data['Data_Solicitacao'], $data['data_aprovacao'], $data['previsao_termino'], );

    // Execute statement
    $stmt->execute();

    // Check for errors
    if ($stmt->errno) {
        echo "Erro ao inserir demanda: " . $stmt->error;
    } else {
        // Redireciona para a página de consulta após a inserção
        header("Location: inserir_demanda.php?mensagem=Demanda inserida com sucesso!");
        exit(); // Certifica-se de que o script seja encerrado após o redirecionamento
    }

    // Close statement
    $stmt->close();
}

// Processar o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_inserir'])) {
    // Obter os dados do formulário
    $data = [
        'Numero_da_Demanda' => filter_input(INPUT_POST, 'Numero_da_Demanda', FILTER_SANITIZE_NUMBER_INT),
        'Numero_do_Contrato' => filter_input(INPUT_POST, 'Numero_do_Contrato', FILTER_SANITIZE_STRING),
        'Descricao' => filter_input(INPUT_POST, 'Descricao', FILTER_SANITIZE_STRING),
        'Projetos' => filter_input(INPUT_POST, 'Projetos', FILTER_SANITIZE_NUMBER_INT),
        'Solicitante' => filter_input(INPUT_POST, 'Solicitante', FILTER_SANITIZE_STRING),
        'Coordenadoria' => filter_input(INPUT_POST, 'Coordenadoria', FILTER_SANITIZE_NUMBER_INT),
        'Status_da_Demanda' => filter_input(INPUT_POST, 'Status_da_Demanda', FILTER_SANITIZE_NUMBER_INT),
        'Tipo_da_Demanda' => filter_input(INPUT_POST, 'Tipo_da_Demanda', FILTER_SANITIZE_NUMBER_INT),
        'Criticidade' => filter_input(INPUT_POST, 'Criticidade', FILTER_SANITIZE_NUMBER_INT),
        'Valor_da_Demanda' => formatarReaisParaNumero(filter_input(INPUT_POST, 'Valor_da_Demanda')),
        'Data_Solicitacao' => filter_input(INPUT_POST, 'Data_Solicitacao', FILTER_SANITIZE_NUMBER_INT),
        'data_aprovacao' => filter_input(INPUT_POST, 'data_aprovacao', FILTER_SANITIZE_NUMBER_INT),
        'previsao_termino' => filter_input(INPUT_POST, 'previsao_termino', FILTER_SANITIZE_NUMBER_INT),
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

// Processar a atualização do contato quando o formulário é enviado via POST
if ($_SERVER && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Resto do código de processamento aqui

    // Obtém os dados do formulário
    $result_usuario = "SELECT * FROM cadastro_da_demanda WHERE id = '$id'";
    $resultado_usuario = mysqli_query($conn, $result_usuario);
    $row_usuario = mysqli_fetch_assoc($resultado_usuario);

    if ($row_usuario) {
        // Obter os dados do formulário
        $data = [
            'Numero_da_Demanda' => $row_usuario['Numero_da_Demanda'],
            'Numero_do_Contrato' => $row_usuario['Contrato'],
            'Descricao' => $row_usuario['Descricao'],
            'Solicitante' => $row_usuario['Solicitante'],
            'Status_da_Demanda' => $row_usuario['Status_demanda_ID'],
            'Tipo_da_Demanda' => $row_usuario['Tipo_demanda_ID'],
            'Criticidade' => $row_usuario['Criticidade_ID'],
            'Valor_da_Demanda' => formatarReaisParaNumero($row_usuario['Valor_demanda']),
            'Data da Solicitação' => $row_usuario['Data_Solicitacao'],
            'data_aprovacao' => $row_usuario['Data_Aprovacao'],
            'previsao_termino' => $row_usuario['Previsao_Termino'],
            'Coordenadoria' => $row_usuario['Coordenadoria_ID'],
            'Projetos' => $row_usuario['Projetos_ID']
        ];

        // Atualizar o contato
        updateContato($conn, $id, $data);
    } else {
        echo "Contato não encontrado.";
    }

    // Fechar a conexão com o banco de dados
    $conn->close();
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
            <h2>Inserir Demanda</h2>
            <form action="../demandas/inserir_demanda.php" method="POST">
                <div class="form-group">
                    <label for="Numero_da_Demanda">Número da Demanda:</label>
                    <input type="number" class="form-control" placeholder="Digite o Número da Demanda"
                        id="Numero_da_Demanda" name="Numero_da_Demanda" required>
                </div>
                <div class="form-group">
                    <label for="Numero_do_Contrato">Número do Contrato:</label>
                    <input type="text" class="form-control" placeholder="Digite o Número do Contrato"
                        id="Numero_do_Contrato" name="Numero_do_Contrato">
                </div>
                <div class="form-group">
                    <label for="Descricao">Descrição:</label>
                    <input type="text" class="form-control" placeholder="Digite a Descrição da Demanda" id="Descricao"
                        name="Descricao">
                </div>
                <div class="form-group">
                    <label for="Projetos">Projetos:</label>
                    <select class="form-control" id="Projetos" name="Projetos">
                        <option value="">--- Selecione um Projeto ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosProjetos($conn, "projetos", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Solicitante">Solicitante:</label>
                    <input type="text" class="form-control" placeholder="Digite o nome do Solicitante" id="Solicitante"
                        name="Solicitante" onkeypress="return ApenasLetras(event,this);">
                </div>
                <div class="form-group">
                    <label for="Coordenadoria">Coordenadoria:</label>
                    <select class="form-control" id="Coordenadoria" name="Coordenadoria">
                        <option value="">--- Selecione uma Coordenadoria ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosCoordenadoria($conn, "coordenadoria", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Status_da_Demanda">Status da Demanda:</label>
                    <select class="form-control" id="Status_da_Demanda" name="Status_da_Demanda">
                        <option value="">--- Selecione um Status ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosStatusDemanda($conn, "status_demanda", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Tipo_da_Demanda">Tipo de Demanda:</label>
                    <select class="form-control" id="Tipo_da_Demanda" name="Tipo_da_Demanda">
                        <option value="">--- Selecione um tipo ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosTipoDemanda($conn, "tipo_demanda", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Criticidade">Criticidade:</label>
                    <select class="form-control" id="Criticidade" name="Criticidade">
                        <option value="">--- Selecione um tipo ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosCriticidade($conn, "criticidade", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Valor_da_Demanda">Valor da Demanda (R$):</label>
                    <input type="text" class="form-control" id="Valor_da_Demanda" name="Valor_da_Demanda"
                        oninput="formatarReais(this)" required>
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
                    <label for="Data_Solicitacao">Data da Solicitação:</label>
                    <input type="date" class="form-control" id="Data_Solicitacao" name="Data_Solicitacao">
                </div>
                <div class="form-group">
                    <label for="data_aprovacao">Data da Aprovação:</label>
                    <input type="date" class="form-control" id="data_aprovacao" name="data_aprovacao">
                </div>
                <div class="form-group">
                    <label for="previsao_termino">Previsão de Término:</label>
                    <input type="date" class="form-control" id="previsao_termino" name="previsao_termino">
                </div>
                <!-- Botão "Inserir Demanda" com ícone -->
                <button type="submit" name="submit_inserir" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Inserir Demanda
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
                        document.getElementById('Numero_da_Demanda').value = ''; // Limpa o campo "Número da Demanda"
                        document.getElementById('Numero_do_Contrato').value = ''; // Limpa o campo "Número do Contrato"
                        document.getElementById('Descricao').value = ''; // Limpa o campo "Descrição"
                        document.getElementById('Projetos').value = ''; // Limpa o campo "Projetos"
                        document.getElementById('Solicitante').value = ''; // Limpa o campo "Solicitante"
                        document.getElementById('Coordenadoria').value = ''; // Limpa o campo "Coordenadoria"
                        document.getElementById('Status_da_Demanda').value = ''; // Limpa o campo "Status da Demanda"
                        document.getElementById('Tipo_da_Demanda').value = ''; // Limpa o campo "Tipo da Demanda"
                        document.getElementById('Criticidade').value = ''; // Limpa o campo "Criticidade"
                        document.getElementById('Valor_da_Demanda').value = ''; // Limpa o campo "Valor da Demanda"
                        document.getElementById('Data_Solicitacao').value = ''; // Limpa o campo "Data da Solicitação"
                        document.getElementById('data_aprovacao').value = ''; // Limpa o campo "Data de Aprovação"
                        document.getElementById('previsao_termino').value = ''; // Limpa o campo "Previsão de Término"
                    }
                </script>

            </form>
        </div>
    </div>
    <p>
        <!-- Rodapé -->
    <footer>
        <!-- Rodapé com imagem -->
        <div class="container">
            <img src="..\imagem\rodape_preto.png" alt="Rodapé" />
        </div>
    </footer>

    <!-- Scripts JavaScript (incluindo jQuery e Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>