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

// Receba e processe os filtros aqui
$projetoSelecionado = isset($_GET['projeto']) ? $_GET['projeto'] : null;

// Construa a consulta SQL com base na contagem total de projetos por entregas
$sqlEntregas = "SELECT p.Nome as Projeto_Nome, COUNT(e.Titulo) as total_por_entrega
                FROM gestão.projetos p
                LEFT JOIN gestão.entregas e ON p.ID = e.Projetos_ID
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
        $dataEntregas[] = [$rowEntregas['Projeto_Nome'], (int) $rowEntregas['total_por_entrega']];
    }
} else {
    // Trate erros na consulta
    echo "Erro na consulta SQL para contagem de entregas: " . mysqli_error($conn);
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
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

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
        google.charts.load('current', { 'packages': ['corechart', 'controls'] });
        google.charts.setOnLoadCallback(drawDashboard);

        function drawDashboard() {
            var dataEntregas = google.visualization.arrayToDataTable(<?php echo json_encode($dataEntregas); ?>);

            var dashboard = new google.visualization.Dashboard(document.getElementById('dashboard'));

            var projetoSelector = new google.visualization.ControlWrapper({
                'controlType': 'CategoryFilter',
                'containerId': 'projetoSelector',
                'options': {
                    'filterColumnLabel': 'Projeto',
                    'ui': {
                        'labelStacking': 'vertical',
                        'allowTyping': false,
                        'allowMultiple': false
                    }
                }
            });

            var chartEntregas = new google.visualization.ChartWrapper({
                'chartType': 'PieChart',
                'containerId': 'barchartEntregas',
                'options': {
                    'title': 'Contagem de Entregas por Projeto',
                    'is3D': true
                },
                'view': {
                    'columns': [0, 1]
                }
            });

            dashboard.bind(projetoSelector, chartEntregas);
            dashboard.draw(dataEntregas);
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

            <!-- Seletor de projetos -->
            <div id="projetoSelector"></div>
            <!-- Div para o gráfico de pizza Contagem de Entregas por Projeto -->
            <div id="dashboard">
                <div id="barchartEntregas" style="width: 50%; height: 300px; float: left;"></div>
            </div>


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