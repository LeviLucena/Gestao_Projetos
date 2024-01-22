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
        'Contatos_Tecnicos' => filter_input(INPUT_POST, 'Contatos_Tecnicos', FILTER_SANITIZE_NUMBER_INT),
    ];

    // Atualizar o contato
    updateContato($conn, $data['ID'], $data);
}

// Consulta o banco de dados para obter os dados do contato pelo ID
$result_usuario = "SELECT * FROM projetos WHERE id = '$id'";
$resultado_usuario = mysqli_query($conn, $result_usuario);
$row_usuario = mysqli_fetch_assoc($resultado_usuario);

// Função para buscar dados do banco de dados Situação
function buscarDadosSituacao($conn, $table, $field, $selectedValue)
{
    $table = 'situacao';
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

// Função para buscar dados do banco de dados Gerente
function buscarDadosGerente($conn, $table, $field, $selectedValue)
{
    $table = 'gerente_projeto';
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

// Função para buscar dados do banco de dados Gerente
function buscarDadosContatos($conn, $table, $field, $selectedValue)
{
    $table = 'contatos_tecnicos';
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

// Função para buscar dados do banco de dados Tipo do Projeto
function buscarDadosTipoProjeto($conn, $table, $field, $selectedValue)
{
    $table = 'tipo_projeto';
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

// Função para buscar dados do banco de dados Subtipo do Projeto
function buscarDadosSubtipoProjeto($conn, $table, $field, $selectedValue)
{
    $table = 'subtipo_projeto';
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

// Função para buscar dados do banco de dados Nível de Prioridade do Projeto
function buscarDadosNivelPrioridadeProjeto($conn, $table, $field, $selectedValue)
{
    $table = 'nivel_prioridade_projeto';
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

// Função para buscar dados do banco de dados Coordenadoria
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

// Função para obter os dados do contato pelo ID
function getContatoById($conn, $id)
{
    $sql = "SELECT * FROM projetos WHERE ID = '$id'";
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
    $query = "UPDATE projetos SET Nome = ?, Objetivo = ?, Data_Inicio = ?, Prazo_Estimado = ?, Situacao_ID = ?, Tipo_Projeto_ID = ?, Subtipo_Projeto_ID = ?, Nivel_Prioridade_Projeto_ID = ?, Criticidade_ID = ?, Coordenadoria_ID = ?, Orcamento_Previsto = ?, Gerente_Projeto_ID = ?, Contatos_Tecnicos_ID = ? WHERE ID = ?";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bind_param("ssssssssssissd", $data['Nome'], $data['Objetivo'], $data['data_inicio'], $data['Prazo_Estimado'], $data['Situacao'], $data['Tipo_do_Projeto'], $data['Subtipo_do_Projeto'], $data['Nivel_de_Prioridade'], $data['Criticidade'], $data['Coordenadoria'], $data['Orcamento_Previsto'], $data['Gerente_do_Projeto'], $data['Contatos_Tecnicos'], $id);

    // Execute statement
    $stmt->execute();

    // Check for errors
    if ($stmt->errno) {
        echo "Erro ao atualizar projeto: " . $stmt->error;
    } else {
        // Redireciona para a página de edição após a atualização
        header("Location: ../projetos/editar_projeto.php?id=" . $id . "&mensagem=Projeto atualizado com sucesso!");
        exit(); // Certifica-se de que o script seja encerrado após o redirecionamento
    }

    // Close statement
    $stmt->close();
}

//ESPAÇO RESERVADO PARA FUNCTION UPDATE

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
    $result_usuario = "SELECT * FROM projetos WHERE id = '$id'";
    $resultado_usuario = mysqli_query($conn, $result_usuario);
    $row_usuario = mysqli_fetch_assoc($resultado_usuario);

    if ($row_usuario) {
        // Obter os dados do formulário
        $data = [
            'Nome' => $row_usuario['Nome'],
            'Objetivo' => $row_usuario['Objetivo'],
            'data_inicio' => $row_usuario['Data_Inicio'],
            'Prazo_Estimado' => $row_usuario['Prazo_Estimado'],
            'Situacao' => $row_usuario['Situacao_ID'],
            'Tipo_do_Projeto' => $row_usuario['Tipo_Projeto_ID'],
            'Subtipo_do_Projeto' => $row_usuario['Subtipo_Projeto_ID'],
            'Nivel_de_Prioridade' => $row_usuario['Nivel_Prioridade_Projeto_ID'],
            'Criticidade' => $row_usuario['Criticidade_ID'],
            'Coordenadoria' => $row_usuario['Coordenadoria_ID'],
            'Tipo_de_Contatos' => $row_usuario['Tipos_Contatos_ID'],
            'Orcamento_Previsto' => formatarReaisParaNumero($row_usuario['Orcamento_Previsto']),
            'Gerente_do_Projeto' => $row_usuario['Gerente_Projeto_ID'],
            'Contatos_Tecnicos' => $row_usuario['Contatos_Tecnicos_ID'],
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
            <h2>Editar Projeto</h2>
            <form action="../projetos/editar_projeto.php" method="POST">
                <div class="form-group">
                    <!-- Adicione um campo oculto para armazenar o ID do contato -->
                    <input type="hidden" name="ID" required value="<?php echo $row_usuario['ID']; ?>">
                    <label for="Nome">Nome do Projeto:</label>
                    <input type="text" class="form-control" placeholder="Digite o Nome do Projeto" id="Nome" name="Nome"
                        required
                        value="<?php echo isset($row_usuario['Nome']) ? htmlspecialchars($row_usuario['Nome']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="Objetivo">Objetivo:</label>
                    <input type="text" class="form-control" placeholder="Digite o Objetivo do Projeto" id="Objetivo"
                        name="Objetivo" required
                        value="<?php echo isset($row_usuario['Objetivo']) ? htmlspecialchars($row_usuario['Objetivo']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="Situacao">Situação:</label>
                    <select class="form-control" id="Situacao" name="Situacao" required>
                        <?php buscarDadosSituacao($conn, "situacao", "Nome", $row_usuario['Situacao_ID']); ?>"
                    </select>
                </div>
                <div class="form-group">
                    <label for="Tipo_do_Projeto">Tipo do Projeto:</label>
                    <select class="form-control" id="Tipo_do_Projeto" name="Tipo_do_Projeto" required>
                        <?php buscarDadosTipoProjeto($conn, "tipo_projeto", "Nome", $row_usuario['Tipo_Projeto_ID']); ?>"
                    </select>
                </div>
                <div class="form-group">
                    <label for="Subtipo_do_Projeto">Subtipo do Projeto:</label>
                    <select class="form-control" id="Subtipo_do_Projeto" name="Subtipo_do_Projeto" required>
                        <?php buscarDadosSubtipoProjeto($conn, "subtipo_projeto", "Nome", $row_usuario['Subtipo_Projeto_ID']); ?>"
                    </select>
                </div>
                <div class="form-group">
                    <label for="Nivel_de_Prioridade">Nível de Prioridade:</label>
                    <select class="form-control" id="Nivel_de_Prioridade" name="Nivel_de_Prioridade" required>
                        <?php buscarDadosNivelPrioridadeProjeto($conn, "nivel_prioridade_projeto", "Nome", $row_usuario['Nivel_Prioridade_Projeto_ID']); ?>"
                    </select>
                </div>
                <div class="form-group">
                    <label for="Criticidade">Criticidade:</label>
                    <select class="form-control" id="Criticidade" name="Criticidade" required>
                        <?php buscarDadosCriticidade($conn, "criticidade", "Nome", $row_usuario['Criticidade_ID']); ?>"
                    </select>
                </div>
                <div class="form-group">
                    <label for="Coordenadoria">Coordenadoria:</label>
                    <select class="form-control" id="Coordenadoria" name="Coordenadoria" required>
                        <?php buscarDadosCoordenadoria($conn, "coordenadoria", "Nome", $row_usuario['Coordenadoria_ID']); ?>"
                    </select>
                </div>
                <div class="form-group">
                    <label for="data_inicio">Data de Início:</label>
                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" required
                        value="<?php echo isset($row_usuario['Data_Inicio']) ? $row_usuario['Data_Inicio'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="Prazo_Estimado">Prazo Estimado:</label>
                    <input type="date" class="form-control" id="Prazo_Estimado" name="Prazo_Estimado"
                        value="<?php echo isset($row_usuario['Prazo_Estimado']) ? $row_usuario['Prazo_Estimado'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="Orcamento_Previsto">Orçamento Previsto:</label>
                    <input type="text" class="form-control" id="Orcamento_Previsto" name="Orcamento_Previsto" required
                        oninput="formatarReais(this)"
                        value="<?php echo isset($row_usuario['Orcamento_Previsto']) ? number_format($row_usuario['Orcamento_Previsto'], 2, ',', '.') : ''; ?>">
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
                    <label for="Gerente_do_Projeto">Gerente do Projeto:</label>
                    <select class="form-control" id="Gerente_do_Projeto" name="Gerente_do_Projeto" required>
                        <?php buscarDadosGerente($conn, "gerente_projeto", "Nome", $row_usuario['Gerente_Projeto_ID']); ?>"
                    </select>
                </div>

                <div class="form-group">
                    <label for="Contatos_Tecnicos">Contato Técnico:</label>
                    <select class="form-control" id="Contato_Tecnico" name="Contatos_Tecnicos" required>
                        <?php buscarDadosContatos($conn, "contatos_tecnicos", "Nome", $row_usuario['Contatos_Tecnicos_ID']); ?>"
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
                        window.location.href = '../projetos/consultar_projeto.php';
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
                        document.getElementById('Nome').value = ''; // Limpa o campo "Nome do Projeto"
                        document.getElementById('Objetivo').value = ''; // Limpa o campo "Objetivo"
                        document.getElementById('Situacao').value = ''; // Limpa o campo "Situação"
                        document.getElementById('Tipo_do_Projeto').value = ''; // Limpa o campo "Tipo do Projeto"
                        document.getElementById('Subtipo_do_Projeto').value = ''; // Limpa o campo "Subtipo do Projeto"
                        document.getElementById('Nivel_de_Prioridade').value = ''; // Limpa o campo "Nível de Prioridade"
                        document.getElementById('Criticidade').value = ''; // Limpa o campo "Criticidade"
                        document.getElementById('Coordenadoria').value = ''; // Limpa o campo "Coordenadoria"
                        document.getElementById('data_inicio').value = ''; // Limpa o campo "Data de Início"
                        document.getElementById('Prazo_Estimado').value = ''; // Limpa o campo "Prazo Estimado"
                        document.getElementById('Orcamento_Previsto').value = ''; // Limpa o campo " Orçamento Previsto"
                        document.getElementById('Gerente_do_Projeto').value = ''; // Limpa o campo "Gerente de Projetos"
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