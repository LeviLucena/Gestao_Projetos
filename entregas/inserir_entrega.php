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

// Função para inserir uma nova entrega
function inserirContato($conn, $data)
{
    $query = "INSERT INTO entregas (Projetos_ID, Titulo, Descricao, Situacao_ID, Tipo_Atividade_ID, Percentual) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    $stmt->bind_param("isssss", $data['Projetos'], $data['Titulo'], $data['Descricao'], $data['Situacao'], $data['Tipo_Atividade'], $data['Percentual']);

    // Execute statement
    $stmt->execute();

    // Check for errors
    if ($stmt->errno) {
        echo "Erro ao inserir Entrega: " . $stmt->error;
    } else {
        // Redireciona para a página de consulta após a inserção
        header("Location: inserir_entrega.php?mensagem=Entrega inserida com sucesso!");
        exit(); // Certifica-se de que o script seja encerrado após o redirecionamento
    }

    // Close statement
    $stmt->close();
}

// Processar o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_inserir'])) {
    // Obter os dados do formulário
    $data = [
        'Projetos' => filter_input(INPUT_POST, 'Projetos', FILTER_SANITIZE_NUMBER_INT),
        'Titulo' => filter_input(INPUT_POST, 'Titulo', FILTER_SANITIZE_STRING),
        'Descricao' => filter_input(INPUT_POST, 'Descricao', FILTER_SANITIZE_STRING),
        'Situacao' => filter_input(INPUT_POST, 'Situacao', FILTER_SANITIZE_NUMBER_INT),
        'Tipo_Atividade' => filter_input(INPUT_POST, 'Tipo_Atividade', FILTER_SANITIZE_NUMBER_INT),
        'Percentual' => filter_input(INPUT_POST, 'Percentual', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
    ];

    // Inserir o novo contato
    inserirContato(conectarBanco(), $data);
}

// Processar a atualização do contato quando o formulário é enviado via POST
if ($_SERVER && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Resto do código de processamento aqui

    // Obtém os dados do formulário
    $result_usuario = "SELECT * FROM entregas WHERE id = '$id'";
    $resultado_usuario = mysqli_query($conn, $result_usuario);
    $row_usuario = mysqli_fetch_assoc($resultado_usuario);


    if ($row_usuario) {
        // Obter os dados do formulário
        $data = [
            'Projetos' => $row_usuario['Projetos_ID'],
            'Titulo' => $row_usuario['Titulo'],
            'Descricao' => $row_usuario['Descricao'],
            'Situacao' => $row_usuario['Situacao_ID'],
            'Tipo_Atividade' => $row_usuario['Tipo_Atividade_ID'],
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

// Função para buscar dados da tabela "entregas"
function buscarDadosEntregas($conn, $projetoID)
{
    $sql = "SELECT e.ID, p.Nome as NomeProjeto, e.Titulo, e.Descricao, s.Nome as NomeSituacao, st.Nome as NomeTipoAtividade, e.Percentual
            FROM entregas e
            LEFT JOIN projetos p ON e.Projetos_ID = p.ID
            LEFT JOIN situacao s ON e.Situacao_ID = s.ID
            LEFT JOIN tipo_atividade st ON e.Tipo_Atividade_ID = st.ID
            WHERE e.Projetos_ID = $projetoID";

    $resultado = $conn->query($sql);
    $dados = [];

    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $dados[] = $row;
        }
    }

    return $dados;
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

// Função para buscar dados do banco de dados da situacao
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
                                        <li><a href="../projetos/inserir_projeto.php">Cadastrar um novo Projeto</a>
                                        </li>
                                        <li><a href="../projetos/consultar_projeto.php">Consultar ou Editar
                                                Projeto</a>
                                        </li>
                                    </ul>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-clipboard"></i>Demandas
                                    <ul class="submenu">
                                        <li><a href="../demandas/inserir_demanda.php">Cadastrar uma Demanda</a></li>
                                        <li><a href="../demandas/consultar_demanda.php">Consultar ou Editar
                                                Demanda</a>
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
                                        <li><a href="../entregas/consultar_entrega.php">Consultar ou Editar
                                                Entrega</a>
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
                                        <li><a href="../contatos/inserir_contato.php">Cadastrar um novo Contato</a>
                                        </li>
                                        <li><a href="../contatos/consultar_contato.php">Consultar ou Editar
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
            <h2>Inserir Entrega</h2>
            <form action="../entregas/inserir_entrega.php" method="POST">
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
                    <label for="Titulo">Título:</label>
                    <input type="text" class="form-control" placeholder="Digite o nome da Entrega" id="Titulo"
                        name="Titulo" required>
                </div>
                <div class="form-group">
                    <label for="Descricao">Descrição:</label>
                    <input type="text" class="form-control" placeholder="Digite a Descrição da Entrega" id="Descricao"
                        name="Descricao" required></textarea>
                </div>

                <div class="form-group">
                    <label for="Situacao">Situação:</label>
                    <select class="form-control" id="Situacao" name="Situacao" required>
                        <option value="">--- Selecione uma Situação ---</option>
                        <?php
                        $conn = conectarBanco();
                        // Função para buscar dados de Situação no banco de dados
                        buscarDadosSituacao($conn, "situacao", "Nome");
                        $conn->close();
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Tipo_Atividade">Tipo de Atividade:</label>
                    <select class="form-control" id="Tipo_Atividade" name="Tipo_Atividade" required>
                        <option value="">--- Selecione uma Atividade ---</option>
                        <?php
                        $conn = conectarBanco();
                        buscarDadosTipoAtividade($conn, "tipo_atividade", "Nome");
                        $conn->close();
                        ?>
                    </select>
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

                <!-- Botão "Inserir Demanda" com ícone -->
                <button type="submit" name="submit_inserir" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Inserir Entrega
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
                        document.getElementById('Projetos').value = ''; // Limpa o campo "Projetos"
                        document.getElementById('Titulo').value = ''; // Limpa o campo "Título da Entrega"
                        document.getElementById('Descricao').value = ''; // Limpa o campo "Descrição da Entrega"
                        document.getElementById('Situacao').value = ''; // Limpa o campo "Situação da Entrega"
                        document.getElementById('Tipo_Atividade').value = ''; // Limpa o campo "Tipo de Atividade da Entrega"
                        document.getElementById('Percentual').value = ''; // Limpa o campo "Percentual"
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