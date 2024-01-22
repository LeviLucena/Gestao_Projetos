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

// Função para buscar dados da tabela Contatos
function buscarDadosAtividades($conn, $table, $field)
{
    $table = "atividades";
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
// Função para buscar dados da tabela Contatos
function buscarDadosEntregas($conn, $table, $field)
{
    $table = "entregas";
    $sql = "SELECT ID, Titulo FROM $table";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row["ID"] . '">' . $row["Titulo"] . '</option>';
        }
    } else {
        echo '<option value="">Nenhum registro encontrado</option>';
    }
}

// Função para buscar dados da tabela Projetos
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

// Função para buscar dados da tabela Status
function buscarDadosSituacao($conn, $table, $field)
{
    $table = "situacao";
    $sql = "SELECT ID, Nome FROM $table";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            echo '<option value="' . $row["ID"] . '">' . $row["Nome"] . '</option>';
        }
    } else {
        echo '<option value="">Nenhum registro encontrado</option>';
    }
}

// Função para buscar dados da tabela Atividades
function buscarDadosAtividade($conn, $table, $field)
{
    $table = "atividades";
    $sql = "SELECT ID, Nome FROM $table";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            echo '<option value="' . $row["ID"] . '">' . $row["Nome"] . '</option>';
        }
    } else {
        echo '<option value="">Nenhum registro encontrado</option>';
    }
}

// Função para buscar dados da tabela Tipo de Atividade
function buscarDadosTipoAtividade($conn, $table, $field)
{
    $table = "tipo_atividade";
    $sql = "SELECT ID, Nome FROM $table";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            echo '<option value="' . $row["ID"] . '">' . $row["Nome"] . '</option>';
        }
    } else {
        echo '<option value="">Nenhum registro encontrado</option>';
    }
}

// Função para inserir um novo contato
function inserirContato($conn, $data)
{
    $query = "INSERT INTO atividades (Projeto_ID, Entrega_ID, Numero_Atividade, Titulo, Descricao, Observacao, Data_Prevista, Situacao_ID, Tipo_Atividade_ID, Data_Lembrete, Porcentagem_Execucao) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bind_param("iisssssiisd", $data['NomeProjeto'], $data['NomeEntrega'], $data['Numero_Atividade'], $data['Titulo'], $data['Descricao'], $data['Observacao'], $data['Data_Prevista'], $data['NomeSituacao'], $data['NomeTipoAtividade'], $data['Data_Lembrete'], $data['Percentual']);

    // Execute statement
    $stmt->execute();

    // Check for errors
    if ($stmt->errno) {
        echo "Erro ao inserir atividade: " . $stmt->error;
    } else {
        // Redireciona para a página de consulta após a inserção
        header("Location: inserir_atividade.php?mensagem=Atividade inserida com sucesso!");
        exit(); // Certifica-se de que o script seja encerrado após o redirecionamento
    }

    // Close statement
    $stmt->close();
}

// Processar o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_inserir'])) {
    // Obter os dados do formulário
    $data = [
        'NomeProjeto' => filter_input(INPUT_POST, 'NomeProjeto', FILTER_SANITIZE_STRING),
        'NomeEntrega' => filter_input(INPUT_POST, 'NomeEntrega', FILTER_SANITIZE_STRING),
        'Numero_Atividade' => filter_input(INPUT_POST, 'Numero_Atividade', FILTER_SANITIZE_NUMBER_INT),
        'Titulo' => filter_input(INPUT_POST, 'Titulo', FILTER_SANITIZE_STRING),
        'Descricao' => filter_input(INPUT_POST, 'Descricao', FILTER_SANITIZE_STRING),
        'Observacao' => filter_input(INPUT_POST, 'Observacao', FILTER_SANITIZE_STRING),
        'Data_Prevista' => filter_input(INPUT_POST, 'Data_Prevista', FILTER_SANITIZE_STRING),
        'NomeSituacao' => filter_input(INPUT_POST, 'NomeSituacao', FILTER_SANITIZE_NUMBER_INT),
        'NomeTipoAtividade' => filter_input(INPUT_POST, 'NomeTipoAtividade', FILTER_SANITIZE_NUMBER_INT),
        'Data_Lembrete' => filter_input(INPUT_POST, 'Data_Lembrete', FILTER_SANITIZE_STRING),
        'Percentual' => filter_input(INPUT_POST, 'Percentual', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),

    ];

    // Inserir o novo contato
    inserirContato(conectarBanco(), $data);
}

// Processar a atualização do contato quando o formulário é enviado via POST
if ($_SERVER && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Resto do código de processamento aqui

    // Obtém os dados do formulário
    $result_usuario = "SELECT * FROM atividades WHERE id = '$id'";
    $resultado_usuario = mysqli_query($conn, $result_usuario);
    $row_usuario = mysqli_fetch_assoc($resultado_usuario);


    if ($row_usuario) {
        // Obter os dados do formulário
        $data = [
            'NomeProjeto' => $row_usuario['Projetos_ID'],
            'NomeEntrega' => $row_usuario['Titulo'],
            'Numero_Atividade' => $row_usuario['Numero_Atividade_ID'],
            'Titulo' => $row_usuario['Titulo'],
            'Descricao' => $row_usuario['Descricao'],
            'Observacao' => $row_usuario['Observacao'],
            'Data_Prevista' => $row_usuario['Data_Prevista'],
            'NomeSituacao' => $row_usuario['Situacao_ID'],
            'NomeTipoAtividade' => $row_usuario['Tipo_Atividade_ID'],
            'Data_Lembrete' => $row_usuario['Data_Lembrete'],
            'Percentual' => $row_usuario['Percentual'],

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
            <h2>Inserir Atividade</h2>
            <form action="../atividades/inserir_atividade.php" method="POST">
                <div class="form-group">
                    <label for="NomeProjeto">Projeto:</label>
                    <select class="form-control" id="NomeProjeto" name="NomeProjeto" required>
                        <option value="">--- Selecione um Projeto ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosProjetos($conn, "projetos", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="NomeEntrega">Entrega:</label>
                    <select class="form-control" id="NomeEntrega" name="NomeEntrega" required>
                        <option value="">--- Selecione uma Entrega ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosEntregas($conn, "entregas", "Titulo");
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Numero_Atividade">Número Atividade:</label>
                    <input type="text" class="form-control" placeholder="Digite o Número da Atividade"
                        id="Numero_Atividade" name="Numero_Atividade">
                </div>
                <div class="form-group">
                    <label for="Titulo">Título:</label>
                    <input type="text" class="form-control" placeholder="Digite o Título da Atividade" id="Titulo"
                        name="Titulo">
                </div>
                <div class="form-group">
                    <label for="Descricao">Descrição:</label>
                    <input type="text" class="form-control" placeholder="Digite a Descrição da Atividade" id="Descricao"
                        name="Descricao">
                </div>
                <div class="form-group">
                    <label for="Observacao">Observação:</label>
                    <input type="text" class="form-control" placeholder="Digite a Observação da Atividade"
                        id="Observacao" name="Observacao">
                </div>

                <div class="form-group">
                    <label for="NomeSituacao">Status:</label>
                    <select class="form-control" id="NomeSituacao" name="NomeSituacao" required>
                        <option value="">--- Selecione um Status ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosSituacao($conn, "situacao", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="NomeTipoAtividade">Tipo de Atividade:</label>
                    <select class="form-control" id="NomeTipoAtividade" name="NomeTipoAtividade" required>
                        <option value="">--- Selecione um Tipo de Atividade ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosTipoAtividade($conn, "tipo_atividade", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Data_Prevista">Data Prevista:</label>
                    <input type="date" class="form-control" id="Data_Prevista" name="Data_Prevista">
                </div>

                <div class="form-group">
                    <label for="Data_Lembrete">Data Lembrete:</label>
                    <input type="date" class="form-control" id="Data_Lembrete" name="Data_Lembrete">
                </div>

                <div class="form-group">
                    <label for="Percentual">Percentual:</label>
                    <input type="text" class="form-control" id="Percentual" name="Percentual"
                        oninput="formatarPorcentagem(this)" required>
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

                <!-- Botão "Inserir Contato" com ícone -->
                <button type="submit" name="submit_inserir" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Inserir Atividade
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
                            document.getElementById('Percentual').value = ''; // Limpa o campo "Percentual"
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