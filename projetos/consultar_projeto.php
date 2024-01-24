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

// Função para conectar ao banco de dados
//function conectarBanco()
//{
//    $conn = new mysqli($servername, $username, $password, $dbname);
//    if ($conn->connect_error) {
//        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
//    }
//    return $conn;
//}

// Função para buscar dados da tabela "projetos" com nomes correspondentes
function buscarDadosProjetos($conn)
{
    $sql = "SELECT p.ID, p.Nome, p.Objetivo, 
            DATE_FORMAT(p.Data_Inicio, '%d/%m/%Y') as Data_Inicio,
            DATE_FORMAT(p.Prazo_Estimado, '%d/%m/%Y') as Prazo_Estimado,
            s.Nome as NomeSituacao, tp.Nome as NomeTipoProjeto,
            np.Nome as NomeNivelPrioridade, coord.Nome as NomeCoordenadoria,
            gp.Nome as NomeGerenteProjeto, FORMAT(p.Orcamento_Previsto, 2, 'de_DE') as Orcamento_Previsto
            FROM projetos p
            LEFT JOIN situacao s ON p.Situacao_ID = s.ID
            LEFT JOIN tipo_projeto tp ON p.Tipo_Projeto_ID = tp.ID
            LEFT JOIN nivel_prioridade_projeto np ON p.Nivel_Prioridade_Projeto_ID = np.ID
            LEFT JOIN coordenadoria coord ON p.Coordenadoria_ID = coord.ID
            LEFT JOIN gerente_projeto gp ON p.Gerente_Projeto_ID = gp.ID";

    $resultado = $conn->query($sql);
    $dados = [];

    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $dados[] = $row;
        }
    }

    return $dados;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<!-- Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/ -->

<head>
    <title>Gerenciamento de Projetos</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Adicione os arquivos CSS do Bootstrap e DataTables -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
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


    <div class="container main">
        <!-- Conteúdo principal -->
        <h2 class="main mx-auto tabela-projetos">Lista de Projetos</h2>
        <p>
        <div class="main mx-auto tabela-projetos">
            <table id="projetos-table" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Objetivo</th>
                        <th>Data de Início</th>
                        <th>Prazo Estimado</th>
                        <th>Situação</th>
                        <th>Tipo de Projeto</th>
                        <th>Nível de Prioridade</th>
                        <th>Coordenadoria</th>
                        <th>Gerente de Projeto</th>
                        <th>Orçamento Previsto</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Busca dados e exibe na tabela
                    // $conn = conectarBanco();
                    $dados = buscarDadosProjetos($conn);

                    foreach ($dados as $row) {
                        echo "<tr>";
                        echo "<td>{$row['ID']}</td>";
                        echo "<td>{$row['Nome']}</td>";
                        echo "<td>{$row['Objetivo']}</td>";
                        echo "<td>{$row['Data_Inicio']}</td>";
                        echo "<td>{$row['Prazo_Estimado']}</td>";
                        echo "<td>{$row['NomeSituacao']}</td>";
                        echo "<td>{$row['NomeTipoProjeto']}</td>";
                        echo "<td>{$row['NomeNivelPrioridade']}</td>";
                        echo "<td>{$row['NomeCoordenadoria']}</td>";
                        echo "<td>{$row['NomeGerenteProjeto']}</td>";
                        echo "<td>R$ {$row['Orcamento_Previsto']}</td>";
                        echo "<td><button class='btn btn-info btn-visualizar' data-id='{$row['ID']}'>Visualizar</button>";
                        echo "<button class='btn btn-success btn-entregas' data-id='{$row['ID']}'>Entregas&nbsp</button></td>";
                        echo "</tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <p>
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
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            // Inicializa o DataTables para a tabela de projetos
            $('#projetos-table').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json"
                    }
                });

            // Adiciona um ouvinte de evento para os botões "Visualizar"
            $('.btn-visualizar').on('click', function () {
                // Obtém o ID do contato da linha da tabela
                var contatoID = $(this).data('id');

                // Redireciona para a página de visualização com o ID
                window.location.href = 'editar_projeto.php?id=' + contatoID;
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Adiciona um ouvinte de evento para os botões "Entregas"
            $('.btn-entregas').on('click', function () {
                // Obtém o ID do projeto da linha da tabela
                var projetoID = $(this).data('id');

                // Redireciona para a página de consultas de entregas com o ID do projeto
                window.location.href = 'entregas/consultar_entrega.php?projeto_id=' + projetoID;
            });
        });
    </script>
</body>

</html>
