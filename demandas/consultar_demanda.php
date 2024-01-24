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

// Função para buscar dados da tabela "Cadastro_da_Demanda" com nomes correspondentes
function buscarDadosDemanda($conn)
{
    $sql = "SELECT d.ID, d.Numero_da_Demanda, d.Contrato, d.Solicitante,
            sd.Nome as NomeStatusDemanda, td.Nome as NomeTipoDemanda,
            FORMAT(d.Valor_demanda, 2, 'de_DE') as Valor_da_demanda, 
            DATE_FORMAT(d.Data_Solicitacao, '%d/%m/%Y') as Data_Solicitacao,
            DATE_FORMAT(d.Data_Aprovacao, '%d/%m/%Y') as Data_Aprovacao,
            DATE_FORMAT(d.Previsao_Termino, '%d/%m/%Y') as Previsao_Termino,
            p.Nome as NomeProjeto, coord.Nome as NomeCoordenadoria
            FROM cadastro_da_demanda d
            LEFT JOIN status_demanda sd ON d.Status_demanda_ID = sd.ID
            LEFT JOIN tipo_demanda td ON d.Tipo_demanda_ID = td.ID
            LEFT JOIN projetos p ON d.Projetos_ID = p.ID
            LEFT JOIN coordenadoria coord ON d.Coordenadoria_ID = coord.ID";

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
<html>
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

        <h2 class="main mx-auto tabela-projetos">Lista de Demandas</h2>
        <p>
        <div class="main mx-auto tabela-projetos">
            <table id="demandas-table" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Número da Demanda</th>
                        <th>Número do Contrato</th>
                        <th>Projetos</th>
                        <th>Solicitante</th>
                        <!-- <th>Coordenadoria</th> -->
                        <th>Status da Demanda</th>
                        <th>Tipo da Demanda</th>
                        <th>Valor da Demanda</th>
                        <th>Data de Aprovação</th>
                        <th>Previsão de Término</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Busca dados e exibe na tabela
                    //$conn = conectarBanco();
                    $dados = buscarDadosDemanda($conn);

                    foreach ($dados as $row) {
                        echo "<tr>";
                        echo "<td>{$row['ID']}</td>";
                        echo "<td>{$row['Numero_da_Demanda']}</td>";
                        echo "<td>{$row['Contrato']}</td>"; // ou use $row['Projetos_ID'] dependendo do que deseja exibir
                        echo "<td>{$row['NomeProjeto']}</td>";
                        echo "<td>{$row['Solicitante']}</td>";
                        // echo "<td>{$row['NomeCoordenadoria']}</td>";
                        echo "<td>{$row['NomeStatusDemanda']}</td>";
                        echo "<td>{$row['NomeTipoDemanda']}</td>";
                        echo "<td>R$ {$row['Valor_da_demanda']}</td>";
                        echo "<td>{$row['Data_Aprovacao']}</td>";
                        echo "<td>{$row['Previsao_Termino']}</td>";
                        echo "<td><button class='btn btn-info btn-visualizar' data-id='{$row['ID']}'>Visualizar</button></td>";
                        echo "</tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
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
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            // Inicializa o DataTables para a tabela de projetos
            var table = $('#demandas-table').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json"
                    }
                });

            // Adiciona um ouvinte de evento para os botões "Visualizar"
            $('#demandas-table').on('click', '.btn-visualizar', function () {
                // Obtém o ID do contato da linha da tabela
                var contatoID = $(this).data('id');

                // Redireciona para a página de visualização com o ID
                window.location.href = '../demandas/editar_demanda.php?id=' + contatoID;
            });
        });
    </script>
</body>
</body>
