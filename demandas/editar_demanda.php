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
        'Numero_da_Demanda' => filter_input(INPUT_POST, 'Numero_da_Demanda', FILTER_SANITIZE_NUMBER_INT),
        'Numero_do_Contrato' => filter_input(INPUT_POST, 'Numero_do_Contrato', FILTER_SANITIZE_STRING),
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

    // Atualizar o contato
    updateContato($conn, $data['ID'], $data);
}

// Consulta o banco de dados para obter os dados do contato pelo ID
$result_usuario = "SELECT * FROM cadastro_da_demanda WHERE ID = '$id'";
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

// Função para buscar dados do banco de dados dos Projetos
function buscarDadosProjetos($conn, $table, $field, $selectedValue)
{
    $table = 'projetos';
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

// Função para buscar dados do banco de dados Status da Demanda
function buscarDadosStatusDemanda($conn, $table, $field, $selectedValue)
{
    $table = 'status_demanda';
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

// Função para buscar dados do banco de dados Tipos de Demanda
function buscarDadosTipoDemanda($conn, $table, $field, $selectedValue)
{
    $table = 'tipo_demanda';
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

// Função para buscar dados do banco de dados Criticidade
function buscarDadosCriticidade($conn, $table, $field, $selectedValue)
{
    $table = 'criticidade';
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


// Função para atualizar um contato no banco de dados
function updateContato($conn, $id, $data)
{
    $query = "UPDATE cadastro_da_demanda SET Numero_da_Demanda = ?, Contrato = ?, Solicitante = ?, Status_demanda_ID = ?, Tipo_demanda_ID = ?, Criticidade_ID = ?, Valor_demanda = ?, Data_Solicitacao = ?, Data_Aprovacao = ?, Previsao_Termino = ?, Projetos_ID = ?, Coordenadoria_ID = ? WHERE ID = ?";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bind_param("sssssssssssis", $data['Numero_da_Demanda'], $data['Numero_do_Contrato'], $data['Solicitante'], $data['Status_da_Demanda'], $data['Tipo_da_Demanda'], $data['Criticidade'], $data['Valor_da_Demanda'], $data['Data_Solicitacao'], $data['data_aprovacao'], $data['previsao_termino'], $data['Projetos'], $data['Coordenadoria'], $id);

    // Execute statement
    $stmt->execute();

    // Check for errors
    if ($stmt->errno) {
        echo "Erro ao atualizar demanda: " . $stmt->error;
    } else {
        // Redireciona para a página de edição após a atualização
        header("Location: editar_demanda.php?id=" . $id . "&mensagem=Demanda atualizada com sucesso!");
        exit(); // Certifica-se de que o script seja encerrado após o redirecionamento
    }

    // Close statement
    $stmt->close();
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
        // Função para permitir apenas letras e espaços no campo "Solicitante"
        function ApenasLetras(e, t) {
            try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                } else if (e) {
                    var charCode = e.which;
                } else {
                    return true;
                }

                // Permitir letras maiúsculas e minúsculas (A-Z, a-z) e espaço (código ASCII 32)
                if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || charCode === 32) {
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
            <h2>Editar Demanda</h2>
            <form action="../demandas/editar_demanda.php" method="POST">
                <div class="form-group">
                    <!-- Adicione um campo oculto para armazenar o ID do contato -->
                    <input type="hidden" name="ID" required value="<?php echo $row_usuario['ID']; ?>">
                    <label for="Numero_da_Demanda">Número da Demanda:</label>
                    <input type="number" class="form-control" placeholder="Digite o Numero_da_Demanda"
                        id="Numero_da_Demanda" name="Numero_da_Demanda" required
                        value="<?php echo isset($row_usuario['Numero_da_Demanda']) ? htmlspecialchars($row_usuario['Numero_da_Demanda']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="Numero_do_Contrato">Número do Contrato:</label>
                    <input type="text" class="form-control" placeholder="Digite o Numero_do_Contrato"
                        id="Numero_do_Contrato" name="Numero_do_Contrato" required
                        value="<?php echo isset($row_usuario['Contrato']) ? htmlspecialchars($row_usuario['Contrato']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="Projetos">Projetos:</label>
                    <select class="form-control" id="Projetos" name="Projetos" required>
                        <?php buscarDadosProjetos(
                            $conn,
                            "projetos",
                            "Nome",
                            $row_usuario['Projetos_ID']
                        ); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Solicitante">Solicitante:</label>
                    <input type="text" class="form-control" placeholder="Digite o nome do Solicitante" id="Solicitante"
                        name="Solicitante" onkeypress="return ApenasLetras(event,this);" required
                        value="<?php echo isset($row_usuario['Solicitante']) ? htmlspecialchars($row_usuario['Solicitante']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="Coordenadoria">Coordenadoria:</label>
                    <select class="form-control" id="Coordenadoria" name="Coordenadoria" required>
                        <?php buscarDadosCoordenadoria($conn, "coordenadoria", "Nome", $row_usuario['Coordenadoria_ID']); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Status_da_Demanda">Status da Demanda:</label>
                    <select class="form-control" id="Status_da_Demanda" name="Status_da_Demanda" required>
                        <?php buscarDadosStatusDemanda($conn, "status_demanda", "Nome", $row_usuario['Status_demanda_ID']); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Tipo_da_Demanda">Tipo de Demanda:</label>
                    <select class="form-control" id="Tipo_da_Demanda" name="Tipo_da_Demanda" required>
                        <?php buscarDadosTipoDemanda($conn, "tipo_demanda", "Nome", $row_usuario['Tipo_demanda_ID']); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Criticidade">Criticidade:</label>
                    <select class="form-control" id="Criticidade" name="Criticidade" required>
                        <option value="">--- Selecione um tipo ---</option>
                        <?php buscarDadosCriticidade($conn, "criticidade", "Nome", $row_usuario['Criticidade_ID']); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Valor_da_Demanda">Valor da Demanda (R$):</label>
                    <input type="text" class="form-control" id="Valor_da_Demanda" name="Valor_da_Demanda"
                        oninput="formatarReais(this)" required
                        value="<?php echo isset($row_usuario['Valor_demanda']) ? number_format($row_usuario['Valor_demanda'], 2, ',', '.') : ''; ?>">
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
                    <label for="data_aprovacao">Data de Aprovação:</label>
                    <input type="date" class="form-control" id="data_aprovacao" name="data_aprovacao" required
                        value="<?php echo isset($row_usuario['Data_Aprovacao']) ? $row_usuario['Data_Aprovacao'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="previsao_termino">Previsão de Término:</label>
                    <input type="date" class="form-control" id="previsao_termino" name="previsao_termino" required
                        value="<?php echo isset($row_usuario['Previsao_Termino']) ? $row_usuario['Previsao_Termino'] : ''; ?>">
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
                        window.location.href = '../demandas/consultar_demanda.php';
                    });
                </script>

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
                        document.getElementById('Projetos').value = ''; // Limpa o campo "Projetos"
                        document.getElementById('Solicitante').value = ''; // Limpa o campo "Solicitante"
                        document.getElementById('Coordenadoria').value = ''; // Limpa o campo "Coordenadoria"
                        document.getElementById('Status_da_Demanda').value = ''; // Limpa o campo "Status da Demanda"
                        document.getElementById('Tipo_da_Demanda').value = ''; // Limpa o campo "Tipo da Demanda"
                        document.getElementById('Criticidade').value = ''; // Limpa o campo "Criticidade"
                        document.getElementById('Valor_da_Demanda').value = ''; // Limpa o campo "Valor da Demanda"
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