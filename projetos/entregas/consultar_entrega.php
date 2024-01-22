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
                                        <li><a href="../../projetos/inserir_projeto.php">Cadastrar um novo
                                                Projeto</a></li>
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


    <div class="container main">
        <!-- Conteúdo principal -->
        <h2 class="main mx-auto tabela-projetos">Lista de Entregas</h2>
        <p>
        <div class="main mx-auto tabela-projetos">
            <table id="entregas-table" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Projeto</th>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Situação</th>
                        <th>Tipo de Atividade</th>
                        <th>Percentual</th>
                        <th>Ações</th>
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

                    // Certifique-se de que $conn está definido antes dessa linha
                    $projetoID = $_GET['projeto_id']; // Supondo que o ID do projeto está sendo passado pela URL
                    
                    // Certifique-se de que $projetoID tem um valor válido antes de chamar a função
                    $dados = buscarDadosEntregas($conn, $projetoID);

                    // Inicializa a variável para a soma do percentual
                    $somaPercentual = 0;

                    foreach ($dados as $row) {
                        $somaPercentual += $row['Percentual'];
                    }

                    // Calcula a média dividindo a soma pelo número total de entregas
                    $numEntregas = count($dados);
                    $mediaPercentual = $numEntregas > 0 ? $somaPercentual / $numEntregas : 0;

                    echo "<p>Média percentual do projeto: " . number_format($mediaPercentual, 2, ',', '.') . "%";

                    // echo "Soma total do percentual para o projeto: " . number_format($somaPercentual, 2, ',', '.') . "%";
                    
                    foreach ($dados as $row) {
                        $percentual = $row['Percentual'];
                        $corFundo = obterCorFundo($percentual);
                        echo "<tr>";
                        echo "<td>{$row['ID']}</td>";
                        echo "<td>{$row['NomeProjeto']}</td>"; // Alterado para refletir a mudança na consulta SQL
                        echo "<td>{$row['Titulo']}</td>";
                        echo "<td>{$row['Descricao']}</td>";
                        echo "<td>{$row['NomeSituacao']}</td>"; // Alterado para refletir a mudança na consulta SQL
                        echo "<td>{$row['NomeTipoAtividade']}</td>"; // Alterado para refletir a mudança na consulta SQL
                        echo "<td style='background: $corFundo; color: white;'>" . number_format($percentual, 2, ',', '.') . "%</td>";
                        echo "<td><button class='btn btn-warning btn-atividades' data-entregaid='{$row['ID']}'>Atividades</button>
                        </td>";
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
            <img src="..\..\imagem\rodape_preto.png" alt="Rodapé" />
        </div>
    </footer>

    <!-- Scripts JavaScript (incluindo jQuery e Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            // Inicializa o DataTables para a tabela de entregas
            $('#entregas-table').DataTable();
        });
    </script>

    <!-- DUPLICIDADE DE CÓDIGO 
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

    -->


    <script>
        $(document).ready(function () {
            // Adiciona um ouvinte de evento para os botões "Entregas"
            $('.btn-entregas').on('click', function () {
                // Obtém o ID do projeto da linha da tabela
                var projetoID = $(this).data('id');

                // Redireciona para a página de consultas de entregas com o ID do projeto
                window.location.href = 'entregas/consultar_entrega.php?projeto_id=' + projetoID;
            });

            $('.btn-atividades').on('click', function () {
                // Obtém o ID da entrega da linha da tabela
                var entregaID = $(this).data('entregaid');

                // Redireciona para a página de consultas de atividades com o ID da entrega
                window.location.href = 'consultar_atividade.php?entrega_id=' + entregaID;
            });
        });
    </script>

</body>

</html>