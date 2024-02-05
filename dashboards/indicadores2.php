<?php
// Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/

// Função para conectar ao banco de dados
@session_start();

// Verifica se a sessão do usuário está ativa
if (!isset($_SESSION['usuario'])) {
    // Se o usuário não estiver logado, execute o logout
    include_once("../logout.php");
    exit();
}

include_once("../config/conexao.php");

// Adicione a instrução SET NAMES 'utf8'; para garantir a codificação correta
mysqli_query($conn, "SET NAMES 'utf8';");


// Receba e processe os filtros aqui

// Construa a consulta SQL com base na soma do orçamento previsto por situação
$sqlOrcamento = "SELECT s.Nome as situacao, SUM(p.orcamento_previsto) as total_orcamento
                FROM gestão.projetos p
                JOIN gestão.situacao s ON p.situacao_ID = s.ID
                GROUP BY p.situacao_ID";

// Execute a consulta SQL
$resultOrcamento = mysqli_query($conn, $sqlOrcamento);

// Verifique se a consulta foi bem-sucedida
if ($resultOrcamento) {
    // Inicialize um array para armazenar os dados do gráfico de orçamento
    $dataOrcamento = array();

    // Adicione os cabeçalhos do array
    $dataOrcamento[] = ['Situação do Projeto', 'Total de Orçamento'];

    // Adicione os dados ao array
    while ($rowOrcamento = mysqli_fetch_assoc($resultOrcamento)) {
        $dataOrcamento[] = [$rowOrcamento['situacao'], (float) $rowOrcamento['total_orcamento']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL: " . mysqli_error($conn);
}

// Construa a consulta SQL com base na soma do orçamento previsto por tipo de projeto
$sqlTipoProjeto = "SELECT tp.Nome as tipo_projeto, SUM(p.orcamento_previsto) as total_orcamento
                  FROM gestão.projetos p
                  JOIN gestão.tipo_projeto tp ON p.tipo_projeto_ID = tp.ID
                  GROUP BY p.tipo_projeto_ID";

// Execute a consulta SQL
$resultTipoProjeto = mysqli_query($conn, $sqlTipoProjeto);

// Verifique se a consulta foi bem-sucedida
if ($resultTipoProjeto) {
    // Inicialize um array para armazenar os dados do gráfico de tipo de projeto
    $dataTipoProjeto = array();

    // Adicione os cabeçalhos do array
    $dataTipoProjeto[] = ['Tipo de Projeto', 'Total de Orçamento'];

    // Adicione os dados ao array
    while ($rowTipoProjeto = mysqli_fetch_assoc($resultTipoProjeto)) {
        $dataTipoProjeto[] = [$rowTipoProjeto['tipo_projeto'], (float) $rowTipoProjeto['total_orcamento']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL: " . mysqli_error($conn);
}

// Construa a consulta SQL com base na soma do orçamento previsto por subtipo de projeto
$sqlSubtipoProjeto = "SELECT stp.Nome as subtipo_projeto, SUM(p.orcamento_previsto) as total_orcamento
                      FROM gestão.projetos p
                      JOIN gestão.subtipo_projeto stp ON p.subtipo_projeto_ID = stp.ID
                      GROUP BY p.subtipo_projeto_ID";

// Execute a consulta SQL
$resultSubtipoProjeto = mysqli_query($conn, $sqlSubtipoProjeto);

// Verifique se a consulta foi bem-sucedida
if ($resultSubtipoProjeto) {
    // Inicialize um array para armazenar os dados do gráfico de subtipo de projeto
    $dataSubtipoProjeto = array();

    // Adicione os cabeçalhos do array
    $dataSubtipoProjeto[] = ['Subtipo de Projeto', 'Total de Orçamento'];

    // Adicione os dados ao array
    while ($rowSubtipoProjeto = mysqli_fetch_assoc($resultSubtipoProjeto)) {
        $dataSubtipoProjeto[] = [$rowSubtipoProjeto['subtipo_projeto'], (float) $rowSubtipoProjeto['total_orcamento']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL: " . mysqli_error($conn);
}

// Construa a consulta SQL com base na soma do orçamento previsto por nível de prioridade
$sqlNivelPrioridade = "SELECT np.Nome as nivel_prioridade_projeto, SUM(p.orcamento_previsto) as total_orcamento
                      FROM gestão.projetos p
                      JOIN gestão.nivel_prioridade_projeto np ON p.nivel_prioridade_projeto_ID = np.ID
                      GROUP BY p.nivel_prioridade_projeto_ID";

// Execute a consulta SQL
$resultNivelPrioridade = mysqli_query($conn, $sqlNivelPrioridade);

// Verifique se a consulta foi bem-sucedida
if ($resultNivelPrioridade) {
    // Inicialize um array para armazenar os dados do gráfico de nível de prioridade
    $dataNivelPrioridade = array();

    // Adicione os cabeçalhos do array
    $dataNivelPrioridade[] = ['Nível de Prioridade', 'Total de Orçamento'];

    // Adicione os dados ao array
    while ($rowNivelPrioridade = mysqli_fetch_assoc($resultNivelPrioridade)) {
        $dataNivelPrioridade[] = [$rowNivelPrioridade['nivel_prioridade_projeto'], (float) $rowNivelPrioridade['total_orcamento']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL: " . mysqli_error($conn);
}

// Construa a consulta SQL com base na soma do orçamento previsto por gerente de projeto
$sqlGerenteProjeto = "SELECT gp.Nome as gerente_projeto, SUM(p.orcamento_previsto) as total_orcamento
                     FROM gestão.projetos p
                     JOIN gestão.gerente_projeto gp ON p.gerente_projeto_ID = gp.ID
                     GROUP BY p.gerente_projeto_ID";

// Execute a consulta SQL
$resultGerenteProjeto = mysqli_query($conn, $sqlGerenteProjeto);

// Verifique se a consulta foi bem-sucedida
if ($resultGerenteProjeto) {
    // Inicialize um array para armazenar os dados do gráfico de gerente de projeto
    $dataGerenteProjeto = array();

    // Adicione os cabeçalhos do array
    $dataGerenteProjeto[] = ['Gerente de Projeto', 'Total de Orçamento'];

    // Adicione os dados ao array
    while ($rowGerenteProjeto = mysqli_fetch_assoc($resultGerenteProjeto)) {
        $dataGerenteProjeto[] = [$rowGerenteProjeto['gerente_projeto'], (float) $rowGerenteProjeto['total_orcamento']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL: " . mysqli_error($conn);
}

// Construa a consulta SQL com base na soma do orçamento previsto por coordenadoria
$sqlCoordenadoria = "SELECT c.Nome as coordenadoria, SUM(p.orcamento_previsto) as total_orcamento
                    FROM gestão.projetos p
                    JOIN gestão.coordenadoria c ON p.coordenadoria_ID = c.ID
                    GROUP BY p.coordenadoria_ID";

// Execute a consulta SQL
$resultCoordenadoria = mysqli_query($conn, $sqlCoordenadoria);

// Verifique se a consulta foi bem-sucedida
if ($resultCoordenadoria) {
    // Inicialize um array para armazenar os dados do gráfico de coordenadoria
    $dataCoordenadoria = array();

    // Adicione os cabeçalhos do array
    $dataCoordenadoria[] = ['Coordenadoria', 'Total de Orçamento'];

    // Adicione os dados ao array
    while ($rowCoordenadoria = mysqli_fetch_assoc($resultCoordenadoria)) {
        $dataCoordenadoria[] = [$rowCoordenadoria['coordenadoria'], (float) $rowCoordenadoria['total_orcamento']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<!-- Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/ -->
<html>

<head>
    <title>Gerenciamento de Projetos</title>
    <!-- Link para o CSS do Bootstrap 3 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Link para o CSS da página -->
    <link rel="stylesheet" type="text/css" href="../stylesheet.css" media="screen" />

    <!-- Biblioteca do Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

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

    <!-- Script para o gráfico de barras horizontais do orçamento por coordenadoria -->
    <script>
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChartCoordenadoria);

        function drawChartCoordenadoria() {
            var dataCoordenadoria = google.visualization.arrayToDataTable(<?php echo json_encode($dataCoordenadoria); ?>);

            var optionsCoordenadoria = {
                title: 'Total de Orçamento por Coordenadoria',
                bars: 'horizontal', // Alterado para barras horizontais
                hAxis: { format: 'currency' }, // Modificado para hAxis (eixo horizontal)
                height: 300
            };

            var chartCoordenadoria = new google.visualization.BarChart(document.getElementById('columnchartCoordenadoria'));

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

    <div class="container main">
        <!-- Conteúdo principal -->
        <div class="main mx-auto tabela-projetos">

            <h2>Visão Geral dos Projetos por Orçamento das Especificações</h2>
            <h5>Uma opção mais concisa que mantém a ideia de fornecer informações numéricas e percentuais sobre os
                projetos</h5>

            <!-- Div para o gráfico de coluna de Orçamento por Tipo do Projeto -->
            <div id="columnchartTipoProjeto" style="width: 50%; height: 300px; float: left;"></div>

            <!-- Div para o gráfico de coluna de Orçamento por Subtipo do Projeto -->
            <div id="columnchartSubtipoProjeto" style="width: 50%; height: 300px; float: left;"></div>

            <!-- Div para o gráfico de coluna de Orçamento por Situação do Projeto -->
            <div id="columnchartOrcamento" style="width: 50%; height: 300px; float: left;"></div>

            <!-- Div para o gráfico de coluna de Orçamento por Prioridade do Projeto -->
            <div id="columnchartNivelPrioridade" style="width: 50%; height: 300px; float: left;"></div>

            <!-- Div para o gráfico de coluna de Orçamento por Gerente do Projeto -->
            <div id="columnchartGerenteProjeto" style="width: 50%; height: 300px; float: left;"></div>

            <!-- Div para o gráfico de coluna de Orçamento por Coordenadoria do Projeto -->
            <div id="columnchartCoordenadoria" style="width: 50%; height: 300px; float: left;"></div>

        </div>

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