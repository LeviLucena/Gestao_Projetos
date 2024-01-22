<?php
// Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/
// Função para conectar ao banco de dados
@session_start();
// Verifica se a sessão do usuário está ativa
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login.php"); // Redireciona para a página de login
    exit();
}

include_once("../../config/conexao.php");

// Adicione a instrução SET NAMES 'utf8'; para garantir a codificação correta
mysqli_query($conn, "SET NAMES 'utf8';");

// Se houver uma mensagem na URL, exiba-a
if (isset($_GET['mensagem'])) {
    //echo '<div class="alert alert-success">' . htmlspecialchars//($_GET['mensagem']) . '</div>';
}

// Obtém o ID do contato da URL
$id = filter_input(INPUT_GET, 'atividade_id', FILTER_SANITIZE_NUMBER_INT);

// Processar a atualização do contato quando o formulário é enviado via POST
if ($_SERVER && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter os dados do formulário
    $data = [
        'atividade_id' => filter_input(INPUT_POST, 'atividade_id', FILTER_SANITIZE_NUMBER_INT),
        'NomeProjeto' => filter_input(INPUT_POST, 'NomeProjeto', FILTER_SANITIZE_STRING),
        'NomeEntrega' => filter_input(INPUT_POST, 'NomeEntrega', FILTER_SANITIZE_STRING),
        'Numero_Atividade' => filter_input(INPUT_POST, 'Numero_Atividade', FILTER_SANITIZE_NUMBER_INT),
        'Titulo' => filter_input(INPUT_POST, 'Titulo', FILTER_SANITIZE_STRING),
        'Descricao' => filter_input(INPUT_POST, 'Descricao', FILTER_SANITIZE_STRING),
        'Observacao' => filter_input(INPUT_POST, 'Observacao', FILTER_SANITIZE_STRING),
        'Data_Prevista' => filter_input(INPUT_POST, 'Data_Prevista', FILTER_SANITIZE_STRING),
        'NomeSituacao' => filter_input(INPUT_POST, 'NomeSituacao', FILTER_SANITIZE_STRING),
        'NomeTipoAtividade' => filter_input(INPUT_POST, 'NomeTipoAtividade', FILTER_SANITIZE_NUMBER_INT),
        'Data_Lembrete' => filter_input(INPUT_POST, 'Data_Lembrete', FILTER_SANITIZE_STRING),
        'Porcentagem_Execucao' => filter_input(INPUT_POST, 'Porcentagem_Execucao', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
    ];

    // Atualizar o contato
    updateContato($conn, $data['atividade_id'], $data);
}

// Consulta o banco de dados para obter os dados do contato pelo ID
$result_usuario = "SELECT * FROM atividades WHERE ID = '$id'";
$resultado_usuario = mysqli_query($conn, $result_usuario);
$row_usuario = mysqli_fetch_assoc($resultado_usuario);

// Função para buscar dados da tabela projetos
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

// Função para buscar dados na tabela entregas
function buscarDadosEntregas($conn, $table, $field, $selectedValue)
{
    $table = 'entregas';
    $sql = "SELECT ID, Titulo FROM $table";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $selected = ($selectedValue == $row["ID"]) ? 'selected' : '';
            echo '<option value="' . $row["ID"] . '" ' . $selected . '>' . $row["Titulo"] . '</option>';
        }
    } else {
        echo '<option value="">Nenhum registro encontrado</option>';
    }
}

// Função para buscar dados da tabela situação
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

// Função para buscar dados da tabela Tipo de Atividade
function buscarDadosTipoAtividade($conn, $table, $field, $selectedValue)
{
    $table = 'tipo_atividade';
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
    $sql = "SELECT * FROM atividades WHERE ID = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function updateContato($conn, $id, $data)
{
    $query = "UPDATE atividades SET Projeto_ID = ?, Entrega_ID = ?, Numero_Atividade = ?, Titulo = ?, Descricao = ?, Observacao = ?, Data_Prevista = ?, Situacao_ID = ?, Tipo_Atividade_ID = ?, Data_Lembrete = ?, Porcentagem_Execucao = ? WHERE ID = ?";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bind_param("iiissssiissd", $data['NomeProjeto'], $data['NomeEntrega'], $data['Numero_Atividade'], $data['Titulo'], $data['Descricao'], $data['Observacao'], $data['Data_Prevista'], $data['NomeSituacao'], $data['NomeTipoAtividade'], $data['Data_Lembrete'], $data['Porcentagem_Execucao'], $id);
    // $stmt->bind_param("iisissiisdsd", $data['NomeProjeto'], $data['NomeEntrega'], $data['Numero_Atividade'], $data['Titulo'], $data['Descricao'], $data['Observacao'], $data['Data_Prevista'], $data['NomeSituacao'], $data['NomeTipoAtividade'], $data['Data_Lembrete'], $data['Porcentagem_Execucao'], $id);

    // Execute statement
    $stmt->execute();

    // Check for errors
    if ($stmt->errno) {
        echo "Erro ao atualizar atividade: " . $stmt->error;
    } else {
        // Redireciona para a página de edição após a atualização
        header("Location: ../entregas/editar_atividade.php?atividade_id=" . $id . "&mensagem=Atividade atualizada com sucesso!");
        exit(); // Certifica-se de que o script seja encerrado após o redirecionamento
    }

    // Close statement
    $stmt->close();
}

// Processar a atualização do contato quando o formulário é enviado via POST
if ($_SERVER && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    //Resto do código de processamento aqui

    // Consulta o banco de dados para obter os dados do contato pelo ID
    $result_usuario = "SELECT * FROM atividades WHERE ID = '$id'";
    $resultado_usuario = mysqli_query($conn, $result_usuario);
    $row_usuario = mysqli_fetch_assoc($resultado_usuario);

    // Verifica se o contato foi encontrado
    if ($row_usuario) {
        // Obter os dados do formulário
        $data = [
            //'atividade_id' => $row_usuario['ID'],
            'NomeProjeto' => $row_usuario['Projeto_ID'],
            'NomeEntrega' => $row_usuario['Entrega_ID'],
            'Numero_Atividade' => $row_usuario['Numero_Atividade'],
            'Titulo' => $row_usuario['Titulo'],
            'Descricao' => $row_usuario['Descricao'],
            'Observacao' => $row_usuario['Observacao'],
            'Data_Prevista' => $row_usuario['Data_Prevista'],
            'Situacao' => $row_usuario['Situacao_ID'],
            'NomeTipoAtividade' => $row_usuario['Tipo_Atividade_ID'],
            'Data_Lembrete' => $row_usuario['Data_Lembrete'],
            'Porcentagem_Execucao' => $row_usuario['Porcentagem_Execucao'],
        ];

        // Atualizar o contato
        updateContato($conn, $id, $data);
        // Exemplo: Se o campo Data_Prevista for uma data, você pode formatá-lo assim:
        $data['Data_Prevista'] = date('Y-m-d', strtotime($row_usuario['Data_Prevista']));
    } else {
        echo "Atividade não encontrada.";
    }

    // Fechar a conexão com o banco de dados
    $conn->close();
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
    <link rel="stylesheet" type="text/css" href="../../stylesheet.css" media="screen" />

    <script>
        // Função para redirecionar após um período de tempo
        function redirecionarParaLogin() {
            setTimeout(function () {
                // Fazer uma chamada AJAX para logout.php
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "../../logout.php", true);
                xhr.send();

                alert("Sua sessão expirou. Você será redirecionado para a página de login.");
                window.location.href = "../../login.php"; // Redireciona para a página de login
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

</head>

<body onload="redirecionarParaLogin()">
    <!-- Cabeçalho (Logotipo e Menu) -->
    <header>
        <div class="main mx-auto tabela-projetos">
            <div class="row">
                <div class="col-md-2">
                    <div class="logo-container">
                        <img src="../../imagem/logo-governo-do-estado-sp.png">
                    </div>
                </div>
                <div class="col-md-10">
                    <nav class="navbar">
                        <ul class="nav navbar-nav">
                            <li><a href="../../index.php"><i class="fas fa-home"></i>Página Inicial</a></li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-tasks"></i>Projetos
                                    <ul class="submenu">
                                        <li><a href="../../projetos/inserir_projeto.php">Cadastrar um novo Projeto</a>
                                        </li>
                                        <li><a href="../../projetos/consultar_projeto.php">Consultar ou Editar
                                                Projeto</a>
                                        </li>
                                    </ul>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-clipboard"></i>Demandas
                                    <ul class="submenu">
                                        <li><a href="../../demandas/inserir_demanda.php">Cadastrar uma Demanda</a></li>
                                        <li><a href="../../demandas/consultar_demanda.php">Consultar ou Editar
                                                Demanda</a>
                                        </li>
                                    </ul>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-box"></i>Entregas
                                    <ul class="submenu">
                                        <li><a href="../../entregas/inserir_entrega.php">Cadastrar uma Entrega</a>
                                        </li>
                                        <li><a href="../../entregas/consultar_entrega.php">Consultar ou Editar
                                                Entrega</a>
                                        </li>
                                    </ul>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-pencil-alt"></i>Atividades
                                    <ul class="submenu">
                                        <li><a href="../../atividades/inserir_atividade.php">Cadastrar uma Atividade</a>
                                        </li>
                                        <li><a href="../../atividades/consultar_atividade.php">Consultar ou Editar
                                                Atividade</a>
                                        </li>
                                    </ul>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-chart-bar"></i>Dashboards
                                    <ul class="submenu">
                                        <li><a href="../../dashboards/indicadores1.php">Painel de Indicadores 1</a></li>
                                        <li><a href="../../dashboards/indicadores2.php">Painel de Indicadores 2</a></li>
                                        <li><a href="../../dashboards/indicadores3.php">Painel de Indicadores 3</a></li>
                                    </ul>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-envelope"></i>Contatos
                                    <ul class="submenu">
                                        <li><a href="../../contatos/inserir_contato.php">Cadastrar um novo Contato</a>
                                        </li>
                                        <li><a href="../../contatos/consultar_contato.php">Consultar ou Editar
                                                Contato</a>
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
                        <a href="../../logout.php" class="btn btn-danger">
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
            <h2>Editar Atividade</h2>
            <form action="../entregas/editar_atividade.php" method="POST">
                <div class="form-group">
                    <!-- Adicione um campo oculto para armazenar o ID do contato -->
                    <input type="hidden" name="atividade_id" value="<?php echo $row_usuario['ID']; ?>">
                    <label for="NomeProjeto">Projeto:</label>
                    <select class="form-control" id="NomeProjeto" name="NomeProjeto" required>
                        <?php buscarDadosProjetos($conn, "projetos", "Nome", $row_usuario['Projeto_ID']); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="NomeEntrega">Entrega:</label>
                    <select class="form-control" id="NomeEntrega" name="NomeEntrega" required>
                        <?php buscarDadosEntregas($conn, "entregas", "Titulo", $row_usuario['Entrega_ID']); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Numero_Atividade">Número Atividade:</label>
                    <input type="text" class="form-control" placeholder="Digite o Número da Atividade"
                        id="Numero_Atividade" name="Numero_Atividade" required
                        value="<?php echo isset($row_usuario['Numero_Atividade']) ? $row_usuario['Numero_Atividade'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="Titulo">Título:</label>
                    <input type="text" class="form-control" placeholder="Digite o Título da Atividade" id="Titulo"
                        name="Titulo" required
                        value="<?php echo isset($row_usuario['Titulo']) ? htmlspecialchars($row_usuario['Titulo']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="Descricao">Descrição:</label>
                    <input type="text" class="form-control" placeholder="Digite a Descrição da Atividade" id="Descricao"
                        name="Descricao" required
                        value="<?php echo isset($row_usuario['Descricao']) ? htmlspecialchars($row_usuario['Descricao']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="Observacao">Observação:</label>
                    <input type="text" class="form-control" placeholder="Digite a Observação da Atividade"
                        id="Observacao" name="Observacao" required
                        value="<?php echo isset($row_usuario['Observacao']) ? htmlspecialchars($row_usuario['Observacao']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="NomSituacao">Status:</label>
                    <select class="form-control" id="NomeSituacao" name="NomeSituacao" required>
                        <?php buscarDadosSituacao($conn, "situacao", "Titulo", $row_usuario['Situacao_ID']); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="NomeTipoAtividade">Tipo de Atividade:</label>
                    <select class="form-control" id="NomeTipoAtividade" name="NomeTipoAtividade" required>
                        <?php buscarDadosTipoAtividade($conn, "tipo_atividade", "Nome", $row_usuario['Tipo_Atividade_ID']); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Data_Prevista">Data Prevista:</label>
                    <input type="date" class="form-control" id="Data_Prevista" name="Data_Prevista" required
                        value="<?php echo isset($row_usuario['Data_Prevista']) ? $row_usuario['Data_Prevista'] : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="Data_Lembrete">Data Lembrete:</label>
                    <input type="date" class="form-control" id="Data_Lembrete" name="Data_Lembrete" required
                        value="<?php echo isset($row_usuario['Data_Lembrete']) ? $row_usuario['Data_Lembrete'] : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="Porcentagem_Execucao">Percentual:</label>
                    <input type="text" class="form-control" id="Porcentagem_Execucao" name="Porcentagem_Execucao"
                        oninput="formatarPorcentagem(this)" required
                        value="<?php echo isset($row_usuario['Porcentagem_Execucao']) ? $row_usuario['Porcentagem_Execucao'] : ''; ?>">
                </div>

                <script>
                    // Função para formatar o campo como porcentagem durante a digitação
                    function formatarPorcentagem(input) {
                        // Remove todos os caracteres não numéricos, exceto o ponto decimal
                        var valor = input.value.replace(/[^0-9.]/g, '');

                        // Divide o valor em parte inteira e parte decimal
                        var partes = valor.split('.');

                        // Limita a parte inteira a 100 e a parte decimal a dois dígitos
                        partes[0] = Math.min(parseInt(partes[0], 10), 100).toString();
                        if (partes.length > 1) {
                            partes[1] = partes[1].slice(0, 2);
                        }

                        // Formata o valor como porcentagem
                        valor = partes.join('.');

                        // Adiciona um ponto decimal automaticamente se não houver
                        if (!valor.includes('.')) {
                            valor += '.';
                        }

                        // Se o valor for maior que 100, ajusta para 100
                        if (parseFloat(valor) > 100) {
                            valor = '100';
                        }

                        // Atualiza o valor no campo
                        input.value = valor + '%';
                    }
                </script>

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
                        window.location.href = '../../atividades/consultar_atividade.php';
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
                            document.getElementById('NomeProjeto').value = ''; // Limpa o campo "Projetos"
                            document.getElementById('NomeEntrega').value = ''; // Limpa o campo "Entrega"
                            document.getElementById('Numero_Atividade').value = ''; // Limpa o campo "Número da Atividade"
                            document.getElementById('Titulo').value = ''; // Limpa o campo "Titulo"
                            document.getElementById('Descricao').value = ''; // Limpa o campo "Descricao"
                            document.getElementById('Observacao').value = ''; // Limpa o campo "Observacao"
                            document.getElementById('Data_Prevista').value = ''; // Limpa o campo "Data Prevista"
                            document.getElementById('NomeSituacao').value = ''; // Limpa o campo "Status"
                            document.getElementById('NomeTipoAtividade').value = ''; // Limpa o campo "Tipo de Atividade"
                            document.getElementById('Data_Lembrete').value = ''; // Limpa o campo "Data_Lembrete"
                            document.getElementById('Porcentagem_Execucao').value = ''; // Limpa o campo "Percentual"
                        }
                    </script>

            </form>
        </div>
    </div>
    <!-- Rodapé -->
    <footer>
        <!-- Rodapé com imagem -->
        <div class="container main">
            <img src="..\..\imagem\rodape_preto.png" alt="Rodapé" />
        </div>
    </footer>

    <!-- Scripts JavaScript (incluindo jQuery e Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>