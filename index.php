<?php
// Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/

// Função para conectar ao banco de dados
@session_start();

// Verifica se a sessão do usuário está ativa
if (!isset($_SESSION['usuario'])) {
    // Se o usuário não estiver logado, execute o logout
    include_once("logout.php");
    exit();
}

include_once("config/conexao.php");

// Adicione a instrução SET NAMES 'utf8'; para garantir a codificação correta
mysqli_query($conn, "SET NAMES 'utf8';");

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se o campo do seletor foi enviado
    if (isset($_POST['gerente_projeto_id'])) {
        $gerente_projeto_id = $_POST['gerente_projeto_id'];

        // Construa a parte da condição WHERE da sua consulta com base no gerente_projeto_id
        $whereClause = "WHERE prj.Gerente_Projeto_ID = {$gerente_projeto_id}";

        // Construa a consulta SQL completa com o filtro
        $query = "
        SELECT
            prj.Nome AS NomeProjeto,
            DATE_FORMAT(prj.Data_Inicio, '%d/%m/%Y') AS Data_Inicio,
            DATE_FORMAT(prj.Prazo_Estimado, '%d/%m/%Y') AS Prazo_Estimado,
            ent.Titulo AS TituloEntrega,
            ent.Percentual,
            atv.Titulo AS TituloAtividade,
            atv.Porcentagem_Execucao
        FROM
            projetos prj
        LEFT JOIN
            entregas ent ON prj.ID = ent.Projetos_ID
        LEFT JOIN
            atividades atv ON prj.ID = atv.Projeto_ID
        $whereClause
    ";
    } else {
        echo "Erro: O campo do seletor não foi enviado.";
        exit;
    }
} else {
    // Se o formulário não foi enviado, execute uma consulta sem filtro
    $query = "
        SELECT
            prj.Nome AS NomeProjeto,
            DATE_FORMAT(prj.Data_Inicio, '%d/%m/%Y') AS Data_Inicio,
            DATE_FORMAT(prj.Prazo_Estimado, '%d/%m/%Y') AS Prazo_Estimado,
            ent.Titulo AS TituloEntrega,
            ent.Percentual,
            atv.Titulo AS TituloAtividade,
            atv.Porcentagem_Execucao
        FROM
            projetos prj
        LEFT JOIN
            entregas ent ON prj.ID = ent.Projetos_ID
        LEFT JOIN
            atividades atv ON prj.ID = atv.Projeto_ID
    ";
}

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Erro na consulta: " . mysqli_error($conn));
}

// Função para buscar dados do banco de dados Gerente
function buscarDadosGerente($conn, $table, $field, $gerenteSelecionado)
{
    $table = 'gerente_projeto';
    $sql = "SELECT ID, Nome FROM $table";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $selected = ($row["ID"] == $gerenteSelecionado) ? 'selected' : '';
            echo '<option value="' . $row["ID"] . '" ' . $selected . '>' . $row["Nome"] . '</option>';
        }
    } else {
        echo '<option value="" disabled>Nenhum registro encontrado</option>';
    }
}
?>

<!DOCTYPE html>
<!-- Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/ -->
<html>

<head>
    <title>Gerenciamento de Projetos</title>
    <!-- Adicione os arquivos CSS do Bootstrap e DataTables -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Link para o CSS da página -->
    <link rel="stylesheet" type="text/css" href="stylesheet.css" media="screen" />

    <!-- Biblioteca do Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>


    <script>
        // Função para redirecionar após um período de tempo
        function redirecionarParaLogin() {
            setTimeout(function () {
                // Fazer uma chamada AJAX para logout.php
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "logout.php", true);
                xhr.send();

                alert("Sua sessão expirou. Você será redirecionado para a página de login.");
                window.location.href = "login.php"; // Redireciona para a página de login
            }, 1800000); // 1800000 milissegundos = 30 minutos (ajuste conforme necessário)
        }
    </script>

    <!-- Script para o gráfico de colunas do orçamento por situação do projeto -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script>
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChartOrcamento);

        function drawChartOrcamento() {
            var dataOrcamento = google.visualization.arrayToDataTable(<?php echo json_encode($dataOrcamento); ?>);

            var optionsOrcamento = {
                title: 'Total de Orçamento por Situação do Projeto',
                bars: 'vertical',
                vAxis: { format: 'currency' },
                height: 300
            };

            var chartOrcamento = new google.visualization.ColumnChart(document.getElementById('columnchartOrcamento'));

            chartOrcamento.draw(dataOrcamento, optionsOrcamento);
        }
    </script>

    <!-- Script para o gráfico de colunas do orçamento por tipo do projeto -->
    <script>
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChartTipoProjeto);

        function drawChartTipoProjeto() {
            var dataTipoProjeto = google.visualization.arrayToDataTable(<?php echo json_encode($dataTipoProjeto); ?>);

            var optionsTipoProjeto = {
                title: 'Total de Orçamento por Tipo de Projeto',
                bars: 'vertical',
                vAxis: { format: 'currency' },
                height: 300
            };

            var chartTipoProjeto = new google.visualization.ColumnChart(document.getElementById('columnchartTipoProjeto'));

            chartTipoProjeto.draw(dataTipoProjeto, optionsTipoProjeto);
        }
    </script>

    <!-- Script para o gráfico de colunas do orçamento por subtipo do projeto -->
    <script>
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChartSubtipoProjeto);

        function drawChartSubtipoProjeto() {
            var dataSubtipoProjeto = google.visualization.arrayToDataTable(<?php echo json_encode($dataSubtipoProjeto); ?>);

            var optionsSubtipoProjeto = {
                title: 'Total de Orçamento por Subtipo de Projeto',
                bars: 'vertical',
                vAxis: { format: 'currency' },
                height: 300
            };

            var chartSubtipoProjeto = new google.visualization.ColumnChart(document.getElementById('columnchartSubtipoProjeto'));

            chartSubtipoProjeto.draw(dataSubtipoProjeto, optionsSubtipoProjeto);
        }
    </script>

    <!-- Script para o gráfico de colunas do orçamento por nível de prioridade -->
    <script>
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChartNivelPrioridade);

        function drawChartNivelPrioridade() {
            var dataNivelPrioridade = google.visualization.arrayToDataTable(<?php echo json_encode($dataNivelPrioridade); ?>);

            var optionsNivelPrioridade = {
                title: 'Total de Orçamento por Nível de Prioridade',
                bars: 'vertical',
                vAxis: { format: 'currency' },
                height: 300
            };

            var chartNivelPrioridade = new google.visualization.ColumnChart(document.getElementById('columnchartNivelPrioridade'));

            chartNivelPrioridade.draw(dataNivelPrioridade, optionsNivelPrioridade);
        }
    </script>

    <!-- Script para o gráfico de colunas do orçamento por gerente de projeto -->
    <script>
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChartGerenteProjeto);

        function drawChartGerenteProjeto() {
            var dataGerenteProjeto = google.visualization.arrayToDataTable(<?php echo json_encode($dataGerenteProjeto); ?>);

            var optionsGerenteProjeto = {
                title: 'Total de Orçamento por Gerente de Projeto',
                bars: 'vertical',
                vAxis: { format: 'currency' },
                height: 300
            };

            var chartGerenteProjeto = new google.visualization.ColumnChart(document.getElementById('columnchartGerenteProjeto'));

            chartGerenteProjeto.draw(dataGerenteProjeto, optionsGerenteProjeto);
        }
    </script>

    <!-- Script para o gráfico de colunas do orçamento por coordenadoria -->
    <script>
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChartCoordenadoria);

        function drawChartCoordenadoria() {
            var dataCoordenadoria = google.visualization.arrayToDataTable(<?php echo json_encode($dataCoordenadoria); ?>);

            var optionsCoordenadoria = {
                title: 'Total de Orçamento por Coordenadoria',
                bars: 'vertical',
                vAxis: { format: 'currency' },
                height: 300
            };

            var chartCoordenadoria = new google.visualization.ColumnChart(document.getElementById('columnchartCoordenadoria'));

            chartCoordenadoria.draw(dataCoordenadoria, optionsCoordenadoria);
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
                        <img src="imagem/logo-governo-do-estado-sp.png">
                    </div>

                </div>
                <div class="col-md-10">
                    <nav class="navbar">
                        <ul class="nav navbar-nav">
                            <li><a href="index.php"><i class="fas fa-home"></i>Página Inicial</a></li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-tasks"></i>Projetos
                                    <ul class="submenu">
                                        <li><a href="projetos/inserir_projeto.php">Cadastrar um novo Projeto</a></li>
                                        <li><a href="projetos/consultar_projeto.php">Consultar ou Editar Projeto</a>
                                        </li>
                                    </ul>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-clipboard"></i>Demandas
                                    <ul class="submenu">
                                        <li><a href="demandas/inserir_demanda.php">Cadastrar uma Demanda</a></li>
                                        <li><a href="demandas/consultar_demanda.php">Consultar ou Editar Demanda</a>
                                        </li>
                                    </ul>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-box"></i>Entregas
                                    <ul class="submenu">
                                        <li><a href="entregas/inserir_entrega.php">Cadastrar uma Entrega</a>
                                        </li>
                                        <li><a href="entregas/consultar_entrega.php">Consultar ou Editar Entrega</a>
                                        </li>
                                    </ul>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-pencil-alt"></i>Atividades
                                    <ul class="submenu">
                                        <li><a href="atividades/inserir_atividade.php">Cadastrar uma Atividade</a>
                                        </li>
                                        <li><a href="atividades/consultar_atividade.php">Consultar ou Editar
                                                Atividade</a>
                                        </li>
                                    </ul>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-chart-bar"></i>Dashboards
                                    <ul class="submenu">
                                        <li><a href="dashboards/indicadores1.php">Painel de Indicadores 1</a></li>
                                        <li><a href="dashboards/indicadores2.php">Painel de Indicadores 2</a></li>
                                        <li><a href="dashboards/indicadores3.php">Painel de Indicadores 3</a></li>
                                    </ul>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-envelope"></i>Contatos
                                    <ul class="submenu">
                                        <li><a href="contatos/inserir_contato.php">Cadastrar um novo Contato</a></li>
                                        <li><a href="contatos/consultar_contato.php">Consultar ou Editar Contato</a>
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
                        <a href="logout.php" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container main">
        <!-- Conteúdo principal -->
        <div class="container main">
            <!-- Conteúdo principal -->
            <div class="main mx-auto tabela-projetos">
                <!-- Seção da tabela e formulário -->
                <h2>Visão Geral dos Projetos por Gerente</h2>
                <h5>Uma opção mais concisa que mantém a ideia de fornecer informações numéricas e percentuais sobre os
                    projetos</h5>
                <p>

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="gerente_projeto_id">Gerente do Projeto:</label>
                            <select name="gerente_projeto_id" id="gerente_projeto_id" class="form-control" required>
                                <!-- ou class="form-control" -->
                                <option value="" disabled <?php echo (!isset($_POST['gerente_projeto_id'])) ? 'selected' : ''; ?>>--- Selecione um Gerente ---</option>
                                <?php
                                $gerenteSelecionado = isset($_POST['gerente_projeto_id']) ? $_POST['gerente_projeto_id'] : '';
                                buscarDadosGerente($conn, "gerente_projeto", "Nome", $gerenteSelecionado);
                                ?>
                                <!-- Adicione mais opções conforme necessário -->
                            </select>
                        </div>
                        <br>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                            <p>
                        </div>
                    </div>
                </form>

                <table id="Projetos-table" class="table table-striped table-bordered" class="display">
                    <thead>
                        <tr>
                            <th>Nome do Projeto</th>
                            <th>Data de Início</th>
                            <th>Prazo Estimado</th>
                            <th>Título da Entrega</th>
                            <th>Percentual</th>
                            <th>Título da Atividade</th>
                            <th>Percentual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Defina as funções fora do loop
                        function obterCorFundo($percentual)
                        {
                            $vermelho = [255, 0, 0];
                            $amarelo = [255, 255, 0];
                            $verde = [0, 128, 0];

                            $corRGB = interpolarCores($vermelho, $amarelo, $verde, $percentual / 100);

                            return "rgb($corRGB[0], $corRGB[1], $corRGB[2])";
                        }

                        function interpolarCores($inicio, $meio, $fim, $fracao)
                        {
                            $corInterpolada = [];

                            foreach (range(0, 2) as $i) {
                                $corInterpolada[$i] = round((1 - $fracao) * ((1 - $fracao) * $inicio[$i] + $fracao * $meio[$i]) + $fracao * ((1 - $fracao) * $meio[$i] + $fracao * $fim[$i]));
                            }

                            return $corInterpolada;
                        }

                        while ($row = mysqli_fetch_assoc($result)) {
                            $percentual = $row['Percentual'];
                            $percentual2 = $row['Porcentagem_Execucao'];
                            $corFundo = obterCorFundo($percentual);
                            $corFundo2 = obterCorFundo($percentual2);
                            echo "<tr>";
                            echo "<td>{$row['NomeProjeto']}</td>";
                            echo "<td>{$row['Data_Inicio']}</td>";
                            echo "<td>{$row['Prazo_Estimado']}</td>";
                            echo "<td>{$row['TituloEntrega']}</td>";
                            echo "<td style='background: $corFundo; color: white;'>" . ($percentual !== null ? number_format($percentual, 2, ',', '.') . "%" : '') . "</td>";
                            echo "<td>{$row['TituloAtividade']}</td>";
                            echo "<td style='background: $corFundo2; color: white;'>" . ($percentual2 !== null ? number_format($percentual2, 2, ',', '.') . "%" : '') . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div> <!-- Fechando a div com a classe "main mx-auto tabela-projetos" -->

            <p><!-- Isso parece ser um marcador de parágrafo aberto, verifique se você precisa dele. -->

        </div> <!-- Fechando a div com a classe "container main" -->

        <!-- Rodapé -->
        <footer>
            <!-- Rodapé com imagem -->
            <div class="container main">
                <img src="imagem\rodape_preto.png" alt="Rodapé" />
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
                var dataTable = $('#Projetos-table').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json"
                    }
                });
            });
        </script>

</body>

</html>
