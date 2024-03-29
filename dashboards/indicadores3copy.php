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

// Construa a consulta SQL com base na contagem total de projetos por tipo de projeto
$sql = "SELECT tp.Nome as Tipo_Projeto, COUNT(*) as total_por_tipo_projeto
        FROM gestão.projetos p
        JOIN gestão.Tipo_Projeto tp ON p.Tipo_Projeto_ID = tp.ID
        GROUP BY p.Tipo_Projeto_ID";

// Execute a consulta SQL
$result = mysqli_query($conn, $sql);

// Verifique se a consulta foi bem-sucedida
if ($result) {
    // Inicialize um array para armazenar os dados do gráfico
    $data = array();

    // Adicione os cabeçalhos do array
    $data[] = ['Tipo de Projeto', 'Quantidade'];

    // Adicione os dados ao array
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [$row['Tipo_Projeto'], (int) $row['total_por_tipo_projeto']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL: " . mysqli_error($conn);
}

// Construa a consulta SQL com base na contagem total de projetos por tipo de projeto
$sql = "SELECT tp.Nome as Tipo_Projeto, COUNT(*) as total_por_tipo_projeto
        FROM gestão.projetos p
        JOIN gestão.Tipo_Projeto tp ON p.Tipo_Projeto_ID = tp.ID
        GROUP BY p.Tipo_Projeto_ID";

// Execute a consulta SQL
$result = mysqli_query($conn, $sql);

// Verifique se a consulta foi bem-sucedida
if ($result) {
    // Inicialize um array para armazenar os dados do gráfico
    $data = array();

    // Adicione os cabeçalhos do array
    $data[] = ['Tipo de Projeto', 'Quantidade'];

    // Adicione os dados ao array
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [$row['Tipo_Projeto'], (int) $row['total_por_tipo_projeto']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL: " . mysqli_error($conn);
}

// Construa a consulta SQL com base na contagem total de projetos por subtipo de projeto
$sqlSubtipo = "SELECT st.Nome as Subtipo_Projeto, COUNT(*) as total_por_subtipo_projeto
              FROM gestão.projetos p
              JOIN gestão.Subtipo_Projeto st ON p.Subtipo_Projeto_ID = st.ID
              GROUP BY p.Subtipo_Projeto_ID";

// Execute a consulta SQL
$resultSubtipo = mysqli_query($conn, $sqlSubtipo);

// Verifique se a consulta foi bem-sucedida
if ($resultSubtipo) {
    // Inicialize um array para armazenar os dados do gráfico de subtipos
    $dataSubtipo = array();

    // Adicione os cabeçalhos do array
    $dataSubtipo[] = ['Subtipo de Projeto', 'Quantidade'];

    // Adicione os dados ao array
    while ($rowSubtipo = mysqli_fetch_assoc($resultSubtipo)) {
        $dataSubtipo[] = [$rowSubtipo['Subtipo_Projeto'], (int) $rowSubtipo['total_por_subtipo_projeto']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL: " . mysqli_error($conn);
}

// Construa a consulta SQL com base na contagem total de projetos por situação
$sqlSituacao = "SELECT s.Nome as Situacao, COUNT(*) as total_por_situacao
                FROM gestão.projetos p
                JOIN gestão.Situacao s ON p.Situacao_ID = s.ID
                GROUP BY p.Situacao_ID";

// Execute a consulta SQL
$resultSituacao = mysqli_query($conn, $sqlSituacao);

// Verifique se a consulta foi bem-sucedida
if ($resultSituacao) {
    // Inicialize um array para armazenar os dados do gráfico de situação
    $dataSituacao = array();

    // Adicione os cabeçalhos do array
    $dataSituacao[] = ['Situação do Projeto', 'Quantidade'];

    // Adicione os dados ao array
    while ($rowSituacao = mysqli_fetch_assoc($resultSituacao)) {
        $dataSituacao[] = [$rowSituacao['Situacao'], (int) $rowSituacao['total_por_situacao']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL: " . mysqli_error($conn);
}

// Construa a consulta SQL com base na contagem total de projetos por nível de prioridade
$sqlNivelPrioridade = "SELECT np.Nome as Nivel_Prioridade, COUNT(*) as total_por_nivel_prioridade
                      FROM gestão.projetos p
                      JOIN gestão.Nivel_Prioridade_Projeto np ON p.Nivel_Prioridade_Projeto_ID = np.ID
                      GROUP BY p.Nivel_Prioridade_Projeto_ID";

// Execute a consulta SQL
$resultNivelPrioridade = mysqli_query($conn, $sqlNivelPrioridade);

// Verifique se a consulta foi bem-sucedida
if ($resultNivelPrioridade) {
    // Inicialize um array para armazenar os dados do gráfico de nível de prioridade
    $dataNivelPrioridade = array();

    // Adicione os cabeçalhos do array
    $dataNivelPrioridade[] = ['Nível de Prioridade do Projeto', 'Quantidade'];

    // Adicione os dados ao array
    while ($rowNivelPrioridade = mysqli_fetch_assoc($resultNivelPrioridade)) {
        $dataNivelPrioridade[] = [$rowNivelPrioridade['Nivel_Prioridade'], (int) $rowNivelPrioridade['total_por_nivel_prioridade']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL: " . mysqli_error($conn);
}

// Construa a consulta SQL com base na contagem total de projetos por gerente
$sqlGerente = "SELECT g.Nome as Gerente, COUNT(*) as total_por_gerente
              FROM gestão.projetos p
              JOIN gestão.Gerente_Projeto g ON p.Gerente_Projeto_ID = g.ID
              GROUP BY p.Gerente_Projeto_ID";

// Execute a consulta SQL
$resultGerente = mysqli_query($conn, $sqlGerente);

// Verifique se a consulta foi bem-sucedida
if ($resultGerente) {
    // Inicialize um array para armazenar os dados do gráfico de gerentes
    $dataGerente = array();

    // Adicione os cabeçalhos do array
    $dataGerente[] = ['Gerente do Projeto', 'Quantidade'];

    // Adicione os dados ao array
    while ($rowGerente = mysqli_fetch_assoc($resultGerente)) {
        $dataGerente[] = [$rowGerente['Gerente'], (int) $rowGerente['total_por_gerente']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL: " . mysqli_error($conn);
}

// Construa a consulta SQL com base na contagem total de projetos por coordenadoria
$sqlCoordenadoria = "SELECT c.Nome as Coordenadoria, COUNT(*) as total_por_coordenadoria
                    FROM gestão.projetos p
                    JOIN gestão.Coordenadoria c ON p.Coordenadoria_ID = c.ID
                    GROUP BY p.Coordenadoria_ID";

// Execute a consulta SQL
$resultCoordenadoria = mysqli_query($conn, $sqlCoordenadoria);

// Verifique se a consulta foi bem-sucedida
if ($resultCoordenadoria) {
    // Inicialize um array para armazenar os dados do gráfico de coordenadorias
    $dataCoordenadoria = array();

    // Adicione os cabeçalhos do array
    $dataCoordenadoria[] = ['Coordenadoria do Projeto', 'Quantidade'];

    // Adicione os dados ao array
    while ($rowCoordenadoria = mysqli_fetch_assoc($resultCoordenadoria)) {
        $dataCoordenadoria[] = [$rowCoordenadoria['Coordenadoria'], (int) $rowCoordenadoria['total_por_coordenadoria']];
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


    <script>
        // ... Seu código existente para carregar a biblioteca do Google Charts e outros scripts ...

        // Função para desenhar todos os gráficos
        function drawCharts(gerenteID) {
            drawChart(); // Adicione chamadas para todos os seus gráficos aqui, passando o gerenteID conforme necessário
            drawChartSubtipo();
            drawChartSituacao();
            drawChartNivelPrioridade();
            drawChartGerente();
            drawChartCoordenadoria();
        }

        // Função para carregar a biblioteca do Google Charts
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(function () {
            // Chame a função drawCharts com o valor padrão ou valor inicial do seletor
            drawCharts($('#gerente_projeto_id').val());
        });

        // Adicione um ouvinte de evento para o seletor de gerente_projeto_id
        $('#gerente_projeto_id').change(function () {
            // Quando o valor do seletor mudar, chame a função drawCharts com o novo valor
            drawCharts($(this).val());
        });

        // ... Seu código existente para os scripts de gráficos ...
    </script>

    <script>
        // Função para carregar a biblioteca do Google Charts
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChart);

        // Função para desenhar o gráfico de pizza
        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($data); ?>);

            var options = {
                title: 'Total de Projetos por Tipo de Projeto',
                is3D: true // Adiciona a opção para tornar o gráfico tridimensional
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }
    </script>

    <!-- Script para o gráfico de pizza do subtipo -->
    <script>
        google.charts.setOnLoadCallback(drawChartSubtipo);

        function drawChartSubtipo() {
            var dataSubtipo = google.visualization.arrayToDataTable(<?php echo json_encode($dataSubtipo); ?>);

            var optionsSubtipo = {
                title: 'Total de Projetos por Subtipo de Projeto',
                is3D: true // Adiciona a opção para tornar o gráfico tridimensional
            };

            var chartSubtipo = new google.visualization.PieChart(document.getElementById('piechartSubtipo'));

            chartSubtipo.draw(dataSubtipo, optionsSubtipo);
        }
    </script>

    <!-- Script para o gráfico de pizza da situação -->
    <script>
        google.charts.setOnLoadCallback(drawChartSituacao);

        function drawChartSituacao() {
            var dataSituacao = google.visualization.arrayToDataTable(<?php echo json_encode($dataSituacao); ?>);

            var optionsSituacao = {
                title: 'Total de Projetos por Situação',
                is3D: true // Adiciona a opção para tornar o gráfico tridimensional
            };

            var chartSituacao = new google.visualization.PieChart(document.getElementById('piechartSituacao'));

            chartSituacao.draw(dataSituacao, optionsSituacao);
        }
    </script>

    <!-- Script para o gráfico de pizza do nível de prioridade -->
    <script>
        google.charts.setOnLoadCallback(drawChartNivelPrioridade);

        function drawChartNivelPrioridade() {
            var dataNivelPrioridade = google.visualization.arrayToDataTable(<?php echo json_encode($dataNivelPrioridade); ?>);

            var optionsNivelPrioridade = {
                title: 'Total de Projetos por Nível de Prioridade',
                is3D: true // Adiciona a opção para tornar o gráfico tridimensional
            };

            var chartNivelPrioridade = new google.visualization.PieChart(document.getElementById('piechartNivelPrioridade'));

            chartNivelPrioridade.draw(dataNivelPrioridade, optionsNivelPrioridade);
        }
    </script>

    <!-- Script para o gráfico de pizza do gerente -->
    <script>
        google.charts.setOnLoadCallback(drawChartGerente);

        function drawChartGerente() {
            var dataGerente = google.visualization.arrayToDataTable(<?php echo json_encode($dataGerente); ?>);

            var optionsGerente = {
                title: 'Total de Projetos por Gerente',
                is3D: true // Adiciona a opção para tornar o gráfico tridimensional
            };

            var chartGerente = new google.visualization.PieChart(document.getElementById('piechartGerente'));

            chartGerente.draw(dataGerente, optionsGerente);
        }
    </script>

    <!-- Script para o gráfico de pizza da coordenadoria -->
    <script>
        google.charts.setOnLoadCallback(drawChartCoordenadoria);

        function drawChartCoordenadoria() {
            var dataCoordenadoria = google.visualization.arrayToDataTable(<?php echo json_encode($dataCoordenadoria); ?>);

            var optionsCoordenadoria = {
                title: 'Total de Projetos por Coordenadoria',
                is3D: true // Adiciona a opção para tornar o gráfico tridimensional
            };

            var chartCoordenadoria = new google.visualization.PieChart(document.getElementById('piechartCoordenadoria'));

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
                                        <li><a href="#">Painel de Indicadores 3</a></li>
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

            <h2>Visão Geral dos Projetos</h2>
            <h5>Uma opção mais concisa que mantém a ideia de fornecer informações numéricas e percentuais sobre os
                projetos</h5>

            <!-- Adicione este bloco antes dos gráficos -->
            <div class="form-group">
                <label for="gerente_projeto_id">Selecione um Gerente de Projeto:</label>
                <select class="form-control" id="gerente_projeto_id">
                    <!-- Opções do seletor -->
                    <option value="0">Todos os Gerentes</option>
                    <?php
                    // Substitua o código abaixo com a consulta SQL para obter os gerentes disponíveis
                    $sqlGerentes = "SELECT ID, Nome FROM gestão.Gerente_Projeto";
                    $resultGerentes = mysqli_query($conn, $sqlGerentes);
                    while ($rowGerente = mysqli_fetch_assoc($resultGerentes)) {
                        echo "<option value='" . $rowGerente['ID'] . "'>" . $rowGerente['Nome'] . "</option>";
                    }
                    ?>
                </select>
            </div>


            <!-- Div para o gráfico de pizza Contagem de projetos por Tipo -->
            <div id="piechart" style="width: 50%; height: 300px; float: left;"></div>

            <!-- Div para o gráfico de pizza Contagem de Projetos por Subtipo -->
            <div id="piechartSubtipo" style="width: 50%; height: 300px; float: left;"></div>

            <!-- Div para o gráfico de pizza Contagem de Projetos por Situação-->
            <div id="piechartSituacao" style="width: 50%; height: 300px; float: left;"></div>

            <!-- Div para o gráfico de pizza Contagem de Projetos por Prioridade -->
            <div id="piechartNivelPrioridade" style="width: 50%; height: 300px; float: left;"></div>

            <!-- Div para o gráfico de pizza Contagem de Projetos por Gerente -->
            <div id="piechartGerente" style="width: 50%; height: 300px; float: left;"></div>

            <!-- Div para o gráfico de pizza Contagem de Projetos por Coordenadoria -->
            <div id="piechartCoordenadoria" style="width: 50%; height: 300px; float: left;"></div>

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