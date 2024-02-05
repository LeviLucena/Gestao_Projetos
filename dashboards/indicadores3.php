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

// Construa a consulta SQL com base na contagem total de projetos por entregas
$sqlEntregas = "SELECT p.Nome as projetos, COUNT(e.titulo) as total_por_entrega
                FROM gestão.projetos p
                LEFT JOIN gestão.entregas e ON p.ID = e.projetos_ID
                GROUP BY p.ID";

// Execute a consulta SQL
$resultEntregas = mysqli_query($conn, $sqlEntregas);

// Verifique se a consulta foi bem-sucedida
if ($resultEntregas) {
    // Inicialize um array para armazenar os dados do gráfico de entregas
    $dataEntregas = array();

    // Adicione os cabeçalhos do array
    $dataEntregas[] = ['Projeto', 'Quantidade de Entregas'];

    // Adicione os dados ao array
    while ($rowEntregas = mysqli_fetch_assoc($resultEntregas)) {
        $dataEntregas[] = [$rowEntregas['projetos'], (int) $rowEntregas['total_por_entrega']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL para contagem de entregas: " . mysqli_error($conn);
}

// Construa a consulta SQL com base na contagem total de projetos por situação
$sqlSituacao = "SELECT s.Nome as situacao, COUNT(e.titulo) as total_por_situacao
                FROM gestão.entregas e
                LEFT JOIN gestão.situacao s ON e.situacao_ID = s.ID
                GROUP BY e.situacao_ID";

// Execute a consulta SQL
$resultSituacao = mysqli_query($conn, $sqlSituacao);

// Verifique se a consulta foi bem-sucedida
if ($resultSituacao) {

    // Inicialize um array para armazenar os dados do gráfico de entregas por situação
    $dataSituacao = array();

    // Adicione os cabeçalhos do array
    $dataSituacao[] = ['Situacao', 'Quantidade de Entregas'];

    // Adicione os dados ao array
    while ($rowSituacao = mysqli_fetch_assoc($resultSituacao)) {

        $dataSituacao[] = [$rowSituacao['situacao'], (int) $rowSituacao['total_por_situacao']];
    }
} else {

    // Trate erros na consulta
    echo "Erro na consulta SQL para contagem de entregas por situação: " . mysqli_error($conn);
}

// Construa a consulta SQL com base na contagem total de projetos por tipo de atividade
$sqlTipoAtividade = "SELECT ta.Nome as tipo_atividade, COUNT(e.titulo) as total_por_tipo_atividade
                    FROM gestão.entregas e
                    LEFT JOIN gestão.tipo_atividade ta ON e.tipo_atividade_ID = ta.ID
                    GROUP BY e.tipo_atividade_ID";

// Execute a consulta SQL
$resultTipoAtividade = mysqli_query($conn, $sqlTipoAtividade);

// Verifique se a consulta foi bem-sucedida
if ($resultTipoAtividade) {
    // Inicialize um array para armazenar os dados do gráfico de entregas por tipo de atividade
    $dataTipoAtividade = array();

    // Adicione os cabeçalhos do array
    $dataTipoAtividade[] = ['Tipo de Atividade', 'Quantidade de Entregas'];

    // Adicione os dados ao array
    while ($rowTipoAtividade = mysqli_fetch_assoc($resultTipoAtividade)) {
        $dataTipoAtividade[] = [$rowTipoAtividade['tipo_atividade'], (int) $rowTipoAtividade['total_por_tipo_atividade']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL para contagem de entregas por tipo de atividade: " . mysqli_error($conn);
}

// Construa a consulta SQL para obter a média percentual de entrega por projeto
$sqlMediaPercentual = "SELECT p.Nome as projetos, AVG(e.percentual) as mediapercentual
                       FROM gestão.projetos p
                       LEFT JOIN gestão.entregas e ON p.ID = e.projetos_ID
                       GROUP BY p.ID";

// Execute a consulta SQL para obter a média percentual
$resultMediaPercentual = mysqli_query($conn, $sqlMediaPercentual);

// Verifique se a consulta foi bem-sucedida
if ($resultMediaPercentual) {
    // Inicialize um array para armazenar os dados da média percentual por projeto
    $dataMediaPercentual = array();

    // Adicione os cabeçalhos do array
    $dataMediaPercentual[] = ['Projeto', 'Média Percentual'];

    // Adicione os dados ao array
    while ($rowMediaPercentual = mysqli_fetch_assoc($resultMediaPercentual)) {
        $dataMediaPercentual[] = [$rowMediaPercentual['projetos'], (float) $rowMediaPercentual['mediapercentual']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL para média percentual por projeto: " . mysqli_error($conn);
}

// Construa a consulta SQL para obter a média percentual de entrega por situação
$sqlMediaPercentualSituacao = "SELECT s.Nome as situacao, AVG(e.percentual) as mediapercentual
                               FROM gestão.entregas e
                               LEFT JOIN gestão.situacao s ON e.situacao_ID = s.ID
                               GROUP BY e.situacao_ID";

// Execute a consulta SQL para obter a média percentual por situação
$resultMediaPercentualSituacao = mysqli_query($conn, $sqlMediaPercentualSituacao);

// Verifique se a consulta foi bem-sucedida
if ($resultMediaPercentualSituacao) {
    // Inicialize um array para armazenar os dados da média percentual por situação
    $dataMediaPercentualSituacao = array();

    // Adicione os cabeçalhos do array
    $dataMediaPercentualSituacao[] = ['Situação', 'Média Percentual'];

    // Adicione os dados ao array
    while ($rowMediaPercentualSituacao = mysqli_fetch_assoc($resultMediaPercentualSituacao)) {
        $dataMediaPercentualSituacao[] = [$rowMediaPercentualSituacao['situacao'], (float) $rowMediaPercentualSituacao['mediapercentual']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL para média percentual por situação: " . mysqli_error($conn);
}

// Construa a consulta SQL para obter a média percentual de entrega por tipo de atividade
$sqlMediaPercentualTipoAtividade = "SELECT ta.Nome as tipo_atividade, AVG(e.percentual) as mediapercentual
                                     FROM gestão.entregas e
                                     LEFT JOIN gestão.tipo_atividade ta ON e.tipo_atividade_ID = ta.ID
                                     GROUP BY e.tipo_atividade_ID";

// Execute a consulta SQL para obter a média percentual por tipo de atividade
$resultMediaPercentualTipoAtividade = mysqli_query($conn, $sqlMediaPercentualTipoAtividade);

// Verifique se a consulta foi bem-sucedida
if ($resultMediaPercentualTipoAtividade) {
    // Inicialize um array para armazenar os dados da média percentual por tipo de atividade
    $dataMediaPercentualTipoAtividade = array();

    // Adicione os cabeçalhos do array
    $dataMediaPercentualTipoAtividade[] = ['Tipo de Atividade', 'Média Percentual'];

    // Adicione os dados ao array
    while ($rowMediaPercentualTipoAtividade = mysqli_fetch_assoc($resultMediaPercentualTipoAtividade)) {
        $dataMediaPercentualTipoAtividade[] = [$rowMediaPercentualTipoAtividade['tipo_atividade'], (float) $rowMediaPercentualTipoAtividade['mediapercentual']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL para média percentual por tipo de atividade: " . mysqli_error($conn);
}

// Construa a consulta SQL para obter a média percentual de entrega por coordenadoria
$sqlMediaPercentualCoordenadoria = "SELECT c.ID AS coordenadoria_ID, c.Nome AS coordenadoria_Nome, AVG(e.percentual) AS mediapercentual
                                     FROM gestão.coordenadoria c
                                     JOIN gestão.projetos p ON c.ID = p.coordenadoria_ID
                                     JOIN gestão.entregas e ON p.ID = e.projetos_ID
                                     GROUP BY c.ID, c.Nome";

// Execute a consulta SQL para obter a média percentual por coordenadoria
$resultMediaPercentualCoordenadoria = mysqli_query($conn, $sqlMediaPercentualCoordenadoria);

// Verifique se a consulta foi bem-sucedida
if ($resultMediaPercentualCoordenadoria) {
    // Inicialize um array para armazenar os dados da média percentual por coordenadoria
    $dataMediaPercentualCoordenadoria = array();

    // Adicione os cabeçalhos do array
    $dataMediaPercentualCoordenadoria[] = ['Coordenadoria', 'Média Percentual'];

    // Adicione os dados ao array
    while ($rowMediaPercentualCoordenadoria = mysqli_fetch_assoc($resultMediaPercentualCoordenadoria)) {
        $dataMediaPercentualCoordenadoria[] = [$rowMediaPercentualCoordenadoria['coordenadoria_Nome'], (float) $rowMediaPercentualCoordenadoria['mediapercentual']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL para média percentual por coordenadoria: " . mysqli_error($conn);
}

// Construa a consulta SQL para obter a média percentual de entrega por gerente de projeto
$sqlMediaPercentualGerente = "SELECT gp.ID AS gerente_projeto_ID, gp.Nome AS gerente_projeto_Nome, AVG(e.percentual) AS mediapercentual
                               FROM gestão.gerente_projeto gp
                               JOIN gestão.projetos p ON gp.ID = p.gerente_projeto_ID
                               JOIN gestão.entregas e ON p.ID = e.projetos_ID
                               GROUP BY gp.ID, gp.Nome";

// Execute a consulta SQL para obter a média percentual por gerente de projeto
$resultMediaPercentualGerente = mysqli_query($conn, $sqlMediaPercentualGerente);

// Verifique se a consulta foi bem-sucedida
if ($resultMediaPercentualGerente) {
    // Inicialize um array para armazenar os dados da média percentual por gerente de projeto
    $dataMediaPercentualGerente = array();

    // Adicione os cabeçalhos do array
    $dataMediaPercentualGerente[] = ['Gerente de Projeto', 'Média Percentual'];

    // Adicione os dados ao array
    while ($rowMediaPercentualGerente = mysqli_fetch_assoc($resultMediaPercentualGerente)) {
        $dataMediaPercentualGerente[] = [$rowMediaPercentualGerente['gerente_projeto_Nome'], (float) $rowMediaPercentualGerente['mediapercentual']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL para média percentual por gerente de projeto: " . mysqli_error($conn);
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

    <script>
        // Função para carregar a biblioteca do Google Charts
        google.charts.load('current', { 'packages': ['corechart'] });
        // Script para o gráfico de barras da contagem de entregas
        google.charts.setOnLoadCallback(drawChartEntregas);

        function drawChartEntregas() {
            var dataEntregas = google.visualization.arrayToDataTable(<?php echo json_encode($dataEntregas); ?>);

            var optionsEntregas = {
                title: 'Contagem de Entregas por Projeto',
                is3D: true // Adiciona a opção para tornar o gráfico tridimensional
            };

            var chartEntregas = new google.visualization.PieChart(document.getElementById('barchartEntregas'));

            chartEntregas.draw(dataEntregas, optionsEntregas);
        }
    </script>


    <script>
        // Script para o gráfico de barras da contagem de entregas por situação
        google.charts.setOnLoadCallback(drawChartSituacao);

        function drawChartSituacao() {
            var dataSituacao = google.visualization.arrayToDataTable(<?php echo json_encode($dataSituacao); ?>);

            var optionsSituacao = {
                title: 'Contagem de Entregas por Situação',
                is3D: true // Adiciona a opção para tornar o gráfico tridimensional
            };

            var chartSituacao = new google.visualization.PieChart(document.getElementById('barchartSituacao'));

            chartSituacao.draw(dataSituacao, optionsSituacao);
        }
    </script>

    <script>
        // Script para o gráfico de barras da contagem de entregas por tipo de atividade
        google.charts.setOnLoadCallback(drawChartTipoAtividade);

        function drawChartTipoAtividade() {
            var dataTipoAtividade = google.visualization.arrayToDataTable(<?php echo json_encode($dataTipoAtividade); ?>);

            var optionsTipoAtividade = {
                title: 'Contagem de Entregas por Tipo de Atividade',
                is3D: true // Adiciona a opção para tornar o gráfico tridimensional
            };

            var chartTipoAtividade = new google.visualization.PieChart(document.getElementById('barchartTipoAtividade'));

            chartTipoAtividade.draw(dataTipoAtividade, optionsTipoAtividade);
        }
    </script>

    <script>
        // Script para o gráfico de barras da média percentual por projeto
        google.charts.setOnLoadCallback(drawChartMediaPercentual);

        function drawChartMediaPercentual() {
            var dataMediaPercentual = google.visualization.arrayToDataTable(<?php echo json_encode($dataMediaPercentual); ?>);

            var optionsMediaPercentual = {
                title: 'Média Percentual de Entregas por Projeto',
                is3D: true // Adiciona a opção para tornar o gráfico tridimensional
            };

            var chartMediaPercentual = new google.visualization.PieChart(document.getElementById('barchartMediaPercentual'));

            chartMediaPercentual.draw(dataMediaPercentual, optionsMediaPercentual);
        }
    </script>

    <script>
        // Script para o gráfico de barras da média percentual por situação
        google.charts.setOnLoadCallback(drawChartMediaPercentualSituacao);

        function drawChartMediaPercentualSituacao() {
            var dataMediaPercentualSituacao = google.visualization.arrayToDataTable(<?php echo json_encode($dataMediaPercentualSituacao); ?>);

            var optionsMediaPercentualSituacao = {
                title: 'Média Percentual de Entregas por Situação',
                is3D: true // Adiciona a opção para tornar o gráfico tridimensional
            };

            var chartMediaPercentualSituacao = new google.visualization.PieChart(document.getElementById('barchartMediaPercentualSituacao'));

            chartMediaPercentualSituacao.draw(dataMediaPercentualSituacao, optionsMediaPercentualSituacao);
        }
    </script>

    <script>
        // Script para o gráfico de barras da média percentual por tipo de atividade
        google.charts.setOnLoadCallback(drawChartMediaPercentualTipoAtividade);

        function drawChartMediaPercentualTipoAtividade() {
            var dataMediaPercentualTipoAtividade = google.visualization.arrayToDataTable(<?php echo json_encode($dataMediaPercentualTipoAtividade); ?>);

            var optionsMediaPercentualTipoAtividade = {
                title: 'Média Percentual de Entregas por Tipo de Atividade',
                is3D: true // Adiciona a opção para tornar o gráfico tridimensional
            };

            var chartMediaPercentualTipoAtividade = new google.visualization.PieChart(document.getElementById('barchartMediaPercentualTipoAtividade'));

            chartMediaPercentualTipoAtividade.draw(dataMediaPercentualTipoAtividade, optionsMediaPercentualTipoAtividade);
        }
    </script>

    <script>
        // Script para o gráfico de barras da média percentual por coordenadoria
        google.charts.setOnLoadCallback(drawChartMediaPercentualCoordenadoria);

        function drawChartMediaPercentualCoordenadoria() {
            var dataMediaPercentualCoordenadoria = google.visualization.arrayToDataTable(<?php echo json_encode($dataMediaPercentualCoordenadoria); ?>);

            var optionsMediaPercentualCoordenadoria = {
                title: 'Média Percentual de Entregas por Coordenadoria',
                is3D: true // Adiciona a opção para tornar o gráfico tridimensional
            };

            var chartMediaPercentualCoordenadoria = new google.visualization.PieChart(document.getElementById('barchartMediaPercentualCoordenadoria'));

            chartMediaPercentualCoordenadoria.draw(dataMediaPercentualCoordenadoria, optionsMediaPercentualCoordenadoria);
        }
    </script>

    <script>
        // Script para o gráfico de barras da média percentual por gerente de projeto
        google.charts.setOnLoadCallback(drawChartMediaPercentualGerente);

        function drawChartMediaPercentualGerente() {
            var dataMediaPercentualGerente = google.visualization.arrayToDataTable(<?php echo json_encode($dataMediaPercentualGerente); ?>);

            var optionsMediaPercentualGerente = {
                title: 'Média Percentual de Entregas por Gerente de Projeto',
                is3D: true // Adiciona a opção para tornar o gráfico tridimensional
            };

            var chartMediaPercentualGerente = new google.visualization.PieChart(document.getElementById('barchartMediaPercentualGerente'));

            chartMediaPercentualGerente.draw(dataMediaPercentualGerente, optionsMediaPercentualGerente);
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

            <h2>Visão Geral dos Projetos por Média e Percentual de Entregas</h2>
            <h5>Uma opção mais concisa que mantém a ideia de fornecer informações numéricas e percentuais sobre os
                projetos</h5>

            <!-- Div para o gráfico de barras Contagem de Entregas por Projeto -->
            <div id="barchartEntregas" style="width: 50%; height: 300px; float: left;"></div>

            <!-- Div para o gráfico de barras Contagem de Entregas por Situação -->
            <div id="barchartSituacao" style="width: 50%; height: 300px; float: left;"></div>

            <!-- Div para o gráfico de barras Contagem de Entregas por Tipo de Atividade -->
            <div id="barchartTipoAtividade" style="width: 50%; height: 300px; float: left;"></div>

            <!-- Div para o gráfico de barras Média Percentual de Entregas por Projeto -->
            <div id="barchartMediaPercentual" style="width: 50%; height: 300px; float: left;"></div>

            <!-- Div para o gráfico de barras Média Percentual de Entregas por Situação -->
            <div id="barchartMediaPercentualSituacao" style="width: 50%; height: 300px; float: left;"></div>

            <!-- Div para o gráfico de barras Média Percentual de Entregas por Tipo de Atividade -->
            <div id="barchartMediaPercentualTipoAtividade" style="width: 50%; height: 300px; float: left;"></div>

            <!-- Div para o gráfico de barras Média Percentual de Entregas por Gerente de Projeto -->
            <div id="barchartMediaPercentualGerente" style="width: 50%; height: 300px; float: left;"></div>


            <!-- Div para o gráfico de barras Média Percentual de Entregas por Coordenadoria -->
            <div id="barchartMediaPercentualCoordenadoria" style="width: 50%; height: 300px; float: left;"></div>
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