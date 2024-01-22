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

// Função para buscar dados da tabela "atividades"
function buscarDadosAtividades($conn, $entregaID)
{
    // Use prepared statements para prevenir injeções de SQL
    $sql = "SELECT a.ID, p.Nome as NomeProjeto, e.Titulo as NomeEntrega, a.Numero_Atividade, a.Titulo, a.Descricao, a.Observacao, 
            DATE_FORMAT(a.Data_Prevista, '%d/%m/%Y') as Data_Prevista, a.Porcentagem_Execucao, s.Nome as NomeSituacao, ta.Nome as NomeTipoAtividade, a.Data_Lembrete
            FROM atividades a
            LEFT JOIN projetos p ON a.Projeto_ID = p.ID
            LEFT JOIN entregas e ON a.Entrega_ID = e.ID
            LEFT JOIN situacao s ON a.Situacao_ID = s.ID
            LEFT JOIN tipo_atividade ta ON a.Tipo_Atividade_ID = ta.ID
            WHERE a.Entrega_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $entregaID);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $dados = [];

    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $dados[] = $row;
        }
    }

    return $dados;
}

// Função para obter a cor de fundo com base no percentual
function obterCorFundo($percentual)
{
    $vermelho = [255, 0, 0];
    $amarelo = [255, 255, 0];
    $verde = [0, 128, 0];

    $corRGB = interpolarCores($vermelho, $amarelo, $verde, $percentual / 100);

    return "rgb($corRGB[0], $corRGB[1], $corRGB[2])";
}

// Função para interpolar as cores
function interpolarCores($inicio, $meio, $fim, $fracao)
{
    $corInterpolada = [];

    foreach (range(0, 2) as $i) {
        $corInterpolada[$i] = round((1 - $fracao) * ((1 - $fracao) * $inicio[$i] + $fracao * $meio[$i]) + $fracao * ((1 - $fracao) * $meio[$i] + $fracao * $fim[$i]));
    }

    return $corInterpolada;
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
        <h2 class="main mx-auto tabela-projetos">Lista de Atividades</h2>
        <p>
        <div class="main mx-auto tabela-projetos">
            <table id="atividades-table" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Projeto</th>
                        <th>Entrega</th>
                        <th>Nº Atividade</th>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Observação</th>
                        <th>Data Prevista</th>
                        <th>Status</th>
                        <th>Tipo Atividade</th>
                        <th>Percentual</th>
                        <!--<th>Ações</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php

                    // Certifique-se de que $conn está definido antes dessa linha
                    $entregaID = $_GET['entrega_id']; // Supondo que o ID da entrega está sendo passado pela URL     
                    
                    // Certifique-se de que $projetoID tem um valor válido antes de chamar a função
                    $dados = buscarDadosAtividades($conn, $entregaID);

                    // Inicializa a variável para a soma do percentual
                    $somaPercentual = 0;

                    foreach ($dados as $row) {
                        $somaPercentual += $row['Porcentagem_Execucao'];
                    }
                    // Calcula a média dividindo a soma pelo número total de atividades
                    $numAtividades = count($dados);
                    $mediaPercentual = $numAtividades > 0 ? $somaPercentual / $numAtividades : 0;

                    // Corrigido o nome da variável $mediaAtividades para $mediaPercentual
                    echo "<p>Média percentual das entregas: " . number_format($mediaPercentual, 2, ',', '.') . "%";

                    foreach ($dados as $row) {
                        $percentual = $row['Porcentagem_Execucao'];
                        $corFundo = obterCorFundo($percentual);
                        echo "<tr>";
                        echo "<td>{$row['ID']}</td>";
                        echo "<td>{$row['NomeProjeto']}</td>";
                        echo "<td>{$row['NomeEntrega']}</td>";
                        echo "<td>{$row['Numero_Atividade']}</td>";
                        echo "<td>{$row['Titulo']}</td>";
                        echo "<td>{$row['Descricao']}</td>";
                        echo "<td>{$row['Observacao']}</td>";
                        echo "<td>{$row['Data_Prevista']}</td>";
                        echo "<td>{$row['NomeSituacao']}</td>";
                        echo "<td>{$row['NomeTipoAtividade']}</td>";
                        echo "<td style='background: $corFundo; color: white;'>" . number_format($percentual, 2, ',', '.') . "%</td>";
                        //echo "<td><button class='btn btn-info btn-visualizar' data-id='{$row['ID']}'>Visualizar</button></td>";
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
            // Inicializa o DataTables para a tabela de atividades
            $('#atividades-table').DataTable();
        });
    </script>

    <script>
        $(document).ready(function () {
            // Adiciona um ouvinte de evento para os botões "Atividades"
            $('.btn-atividades').on('click', function () {
                // Obtém o ID da atividade da linha da tabela
                var atividadeID = $(this).data('id');

                // Redireciona para a página de consultas de atividades com o ID da atividade
                //window.location.href = 'consultar_atividade.php?atividade_id=' + atividadeID;
            });
        });

    </script>

    <!-- ESTA PARTE DO CÓDIGO ESTÁ EM ANÁLISE 

    <script>
        $(document).ready(function () {
            // Adiciona um ouvinte de evento para os botões "Visualizar"
            $('.btn-visualizar').on('click', function () {
                // Obtém o ID da entrega da linha da tabela
                var entregaID = $(this).closest('tr').find('td:eq(0)').text();

                // Redireciona para a página de editar entrega com o ID da entrega
                window.location.href = '../../entregas/editar_entrega.php?id=' + entregaID;
            });
        });
    </script> -->

    <script>
        $(document).ready(function () {
            // Adiciona um ouvinte de evento para os botões "Visualizar"
            $('.btn-visualizar').on('click', function () {
                // Obtém o ID da entrega da linha da tabela
                var entregaID = $(this).closest('tr').find('td:eq(0)').text();

                // Redireciona para a página de editar entrega com o ID da entrega
                window.location.href = 'editar_atividade.php?id=' + entregaID;
            });
        });
    </script>

</body>

</html>