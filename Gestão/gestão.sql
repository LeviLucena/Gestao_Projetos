-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04-Jan-2024 às 18:03
-- Versão do servidor: 10.4.28-MariaDB
-- versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `gestão`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `atividades`
--

CREATE TABLE `atividades` (
  `ID` int(11) NOT NULL,
  `Projeto_ID` int(11) DEFAULT NULL,
  `Entrega_ID` int(11) DEFAULT NULL,
  `Numero_Atividade` int(11) DEFAULT NULL,
  `Titulo` varchar(90) DEFAULT NULL,
  `Descricao` varchar(500) DEFAULT NULL,
  `Observacao` varchar(200) DEFAULT NULL,
  `Data_Prevista` date DEFAULT NULL,
  `Porcentagem_Execucao` decimal(5,2) DEFAULT NULL,
  `Situacao_ID` int(11) DEFAULT NULL,
  `Tipo_Atividade_ID` int(11) DEFAULT NULL,
  `Data_Lembrete` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `atividades`
--

INSERT INTO `atividades` (`ID`, `Projeto_ID`, `Entrega_ID`, `Numero_Atividade`, `Titulo`, `Descricao`, `Observacao`, `Data_Prevista`, `Porcentagem_Execucao`, `Situacao_ID`, `Tipo_Atividade_ID`, `Data_Lembrete`) VALUES
(1, 1, 1, 312687621, 'Interatividade Humana x Robô', 'Quo temporibus ipsam eum voluptatem omnis et corporis quas? Et voluptatem mollitia est deleniti inventore aut harum fugiat.', 'Quo temporibus ipsam eum voluptatem omnis et corporis quas? Et voluptatem mollitia est del', '2023-12-01', 15.00, 2, 4, '2023-12-06'),
(2, 2, 4, 35424652, 'Criando bat para execução automática II', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore. Ut enim ad minim veniam', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore. Ut enim ad minim veniam', '2023-12-02', 34.00, 3, 4, '2023-12-29'),
(3, 2, 15, 435656, 'Cloud Infinite Future III', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore. Ut enim ad minim veniam', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. ', '2023-12-26', 85.55, 1, 1, '2023-12-30'),
(4, 1, 2, 3687498, 'Codificação dos Requisitos', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit labori', '2023-12-06', 5.55, 3, 3, '2023-12-20'),
(5, 1, 15, 354632, 'Criando bat para execução automática', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua.', '2023-12-06', 55.77, 2, 2, '2023-12-29'),
(6, 1, 3, 232455, 'Script da Codificação Quântica', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua.', '2023-12-06', 64.55, 1, 2, '2023-12-27');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cadastro_da_demanda`
--

CREATE TABLE `cadastro_da_demanda` (
  `ID` int(11) NOT NULL,
  `Numero_da_Demanda` int(11) NOT NULL,
  `Contrato` varchar(60) DEFAULT NULL,
  `Solicitante` text DEFAULT NULL,
  `Status_demanda_ID` int(11) DEFAULT NULL,
  `Tipo_demanda_ID` int(11) DEFAULT NULL,
  `Valor_demanda` decimal(10,2) DEFAULT NULL,
  `Data_Solicitacao` date DEFAULT NULL,
  `Data_Aprovacao` date DEFAULT NULL,
  `Previsao_Termino` date DEFAULT NULL,
  `Projetos_ID` int(11) DEFAULT NULL,
  `Coordenadoria_ID` int(11) DEFAULT NULL,
  `Descricao` varchar(500) DEFAULT NULL,
  `Criticidade_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `cadastro_da_demanda`
--

INSERT INTO `cadastro_da_demanda` (`ID`, `Numero_da_Demanda`, `Contrato`, `Solicitante`, `Status_demanda_ID`, `Tipo_demanda_ID`, `Valor_demanda`, `Data_Solicitacao`, `Data_Aprovacao`, `Previsao_Termino`, `Projetos_ID`, `Coordenadoria_ID`, `Descricao`, `Criticidade_ID`) VALUES
(1, 850300621, 'AZF653295PRODESP/Testes', 'Ciclano Fulana', 1, 1, 15968.00, NULL, '2023-12-06', '2023-12-21', 1, 2, 'Esta demanda tem a finalidade de custear recursos para novos projetos', 2),
(2, 766865233, 'ABC458548PRODESP/Implementação', 'Fulano Ciclana', 3, 2, 1500.00, NULL, '2023-11-07', '2023-12-25', 2, 10, 'Esta demanda tem a finalidade de financiar recursos para a inteligência artificial piloto', 2),
(3, 349784996, 'ABC698965PRODESP/Desenvolvimento', 'Beltrano Ciclano', 7, 7, 50000.00, NULL, '2023-12-15', '2023-12-31', 4, 9, 'Este projeto tem a finalidade de financiar os recursos para a conexão com as nuvens', 1),
(4, 199043520, 'ABC255874PRODESP/Desenvolvimento', 'Beltrano Ciclano', 10, 10, 2500.75, '2023-09-03', '0000-00-00', '2023-11-25', 1, 4, 'Esta demanda tem a finalidade de custear recursos para novos projetos', NULL),
(5, 199043567, 'ABC243874PRODESP/Implementação', 'Beltrano Ciclano', 5, 8, 3580.99, NULL, '2024-02-14', '2024-02-20', 7, 7, 'Esta demanda tem a finalidade de custear recursos para novos projetos', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `contatos_tecnicos`
--

CREATE TABLE `contatos_tecnicos` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(30) DEFAULT NULL,
  `Telefone` varchar(13) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Coordenadorias_ID` int(11) DEFAULT NULL,
  `Tipos_Contatos_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `contatos_tecnicos`
--

INSERT INTO `contatos_tecnicos` (`ID`, `Nome`, `Telefone`, `Email`, `Coordenadorias_ID`, `Tipos_Contatos_ID`) VALUES
(1, 'Ciclano Beltrano', '11 98345-7566', 'beltrano@gmail.com', 1, 1),
(2, 'Samuel Alves Pereira', '11 99709-4283', 'SamuelAlvesPereira@jourrapide.com', 3, 2),
(3, 'Lavinia Correia Ribeiro', '21 97301-9484', 'LaviniaCorreiaRibeiro@rhyta.com', 5, 3),
(4, 'Lucas Castro Gomes', '11 95218-7292', 'LucasCastroGomes@armyspy.com', 3, 4),
(5, 'Leonor Cavalcanti Carvalho', '44 97054-1000', 'LeonorCavalcantiCarvalho@armyspy.com', 9, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `coordenadoria`
--

CREATE TABLE `coordenadoria` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `coordenadoria`
--

INSERT INTO `coordenadoria` (`ID`, `Nome`) VALUES
(1, 'Coordenadoria Geral de Administração - CGA'),
(2, 'Coordenadoria de Recursos Humanos - CRH'),
(3, 'Coordenadoria de Planejamento de Saúde - CPS'),
(4, 'Coordenadoria de Ciência, Tecnologia e Insumos Estratégicos de Saúde - CCTIES'),
(5, 'Coordenadoria de Controle de Doenças - CCD'),
(6, 'Coordenadoria de Gestão de Contratos de Serviços de Saúde - CGCSS'),
(7, 'Coordenadoria de Serviços de Saúde - CSS'),
(8, 'Coordenadoria de Regiões de Saúde - CRS'),
(9, 'Coordenadoria de Gestão Orçamentária e Financeira - CGOF'),
(10, 'Coordenadoria de Assistência Farmacêutica - CAF');

-- --------------------------------------------------------

--
-- Estrutura da tabela `criticidade`
--

CREATE TABLE `criticidade` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `criticidade`
--

INSERT INTO `criticidade` (`ID`, `Nome`) VALUES
(1, 'Baixa'),
(2, 'Média'),
(3, 'Alta'),
(4, 'Muito Alta');

-- --------------------------------------------------------

--
-- Estrutura da tabela `entregas`
--

CREATE TABLE `entregas` (
  `ID` int(11) NOT NULL,
  `Projetos_ID` int(11) DEFAULT NULL,
  `Titulo` varchar(90) DEFAULT NULL,
  `Descricao` varchar(150) DEFAULT NULL,
  `Situacao_ID` int(11) DEFAULT NULL,
  `Tipo_Atividade_ID` int(11) DEFAULT NULL,
  `Percentual` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `entregas`
--

INSERT INTO `entregas` (`ID`, `Projetos_ID`, `Titulo`, `Descricao`, `Situacao_ID`, `Tipo_Atividade_ID`, `Percentual`) VALUES
(1, 1, 'Alinhamento Operacional', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore. Ut enim ad minim veniam', 3, 5, 10.50),
(2, 2, 'SLA das Requisições ', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore. Ut enim ad minim veniam', 1, 6, 4.50),
(3, 4, 'Fator X ', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore. Ut enim ad minim veniam', 5, 2, 50.00),
(4, 1, 'Codificação Infinita IA', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore. Ut enim ad minim veniam', 3, 1, 35.00),
(15, 2, 'Script Automáticos', 'Este projeto tem a finalidade de financiar os recursos para a conexão com as nuvens', 3, 1, 23.10),
(16, 2, 'Programação em Nuvem', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nos', 4, 2, 100.00),
(17, 2, 'Github interface II', 'Este projeto tem a finalidade de financiar os recursos para a conexão com as nuvens', 2, 1, 16.00),
(18, 4, 'Cyber Security Cloud', 'Este projeto tem a finalidade de financiar os recursos para a conexão com as nuvens', 2, 1, 5.00),
(19, 4, 'Cloud Conection', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nos', 5, 2, 76.00),
(20, 4, 'Robo Config', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nos', 4, 4, 100.00),
(21, 2, 'Carro IA Elon Musk Feature II', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nos', 2, 2, 88.00),
(22, 2, 'GuardianX', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nos', 1, 4, 11.00),
(23, 2, 'Azure DataLake III', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nos', 3, 3, 47.78),
(24, 4, 'Interatividade entre Robôs', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nos', 3, 2, 4.55);

-- --------------------------------------------------------

--
-- Estrutura da tabela `gerente_projeto`
--

CREATE TABLE `gerente_projeto` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `gerente_projeto`
--

INSERT INTO `gerente_projeto` (`ID`, `Nome`) VALUES
(1, 'Marcelo Borota'),
(2, 'Ricardo Trugillo'),
(3, 'João Moreira');

-- --------------------------------------------------------

--
-- Estrutura da tabela `nivel_prioridade_projeto`
--

CREATE TABLE `nivel_prioridade_projeto` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `nivel_prioridade_projeto`
--

INSERT INTO `nivel_prioridade_projeto` (`ID`, `Nome`) VALUES
(1, 'Baixa'),
(2, 'Média'),
(3, 'Alta'),
(4, 'Muito Alta');

-- --------------------------------------------------------

--
-- Estrutura da tabela `projetos`
--

CREATE TABLE `projetos` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(100) DEFAULT NULL,
  `Objetivo` varchar(200) DEFAULT NULL,
  `Data_Inicio` date DEFAULT NULL,
  `Prazo_Estimado` date DEFAULT NULL,
  `Situacao_ID` int(11) DEFAULT NULL,
  `Tipo_Projeto_ID` int(11) DEFAULT NULL,
  `Subtipo_Projeto_ID` int(11) DEFAULT NULL,
  `Nivel_Prioridade_Projeto_ID` int(11) DEFAULT NULL,
  `Criticidade_ID` int(11) DEFAULT NULL,
  `Tipos_Contatos_ID` int(11) DEFAULT NULL,
  `Contatos_Tecnicos_ID` int(11) DEFAULT NULL,
  `Orcamento_Previsto` decimal(10,2) DEFAULT NULL,
  `Gerente_Projeto_ID` int(11) DEFAULT NULL,
  `Coordenadoria_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `projetos`
--

INSERT INTO `projetos` (`ID`, `Nome`, `Objetivo`, `Data_Inicio`, `Prazo_Estimado`, `Situacao_ID`, `Tipo_Projeto_ID`, `Subtipo_Projeto_ID`, `Nivel_Prioridade_Projeto_ID`, `Criticidade_ID`, `Tipos_Contatos_ID`, `Contatos_Tecnicos_ID`, `Orcamento_Previsto`, `Gerente_Projeto_ID`, `Coordenadoria_ID`) VALUES
(1, 'Código Infinito	', 'Desenvolvimento de um Sistema de Gerenciamento de Projetos utilizando metodologia ágil.	', '2023-12-01', '2023-12-31', 1, 2, 3, 4, 2, 2, 3, 45055.00, 1, 3),
(2, 'Projeto: CiberGuardião', 'Reforçar a segurança cibernética da organização, protegendo ativos e dados contra ameaças externas', '2023-11-10', '2023-12-15', 2, 1, 1, 1, 2, 2, 2, 14950.00, 2, 4),
(4, 'Projeto: CloudConnectX', 'Facilitar a transição para um ambiente em nuvem, otimizando recursos, melhorando a flexibilidade', '2023-11-09', '2023-12-31', 4, 2, 3, 4, 3, 2, 3, 10500.00, 3, 8),
(5, 'Projeto: SmartNetworkOptimizer', 'Otimizar a infraestrutura de rede, melhorando o desempenho, a eficiência e a qualidade do serviço', '2023-10-10', '2024-01-15', 2, 3, 6, 1, 4, 3, 1, 13500.00, 3, 5),
(6, 'Projeto: AI-DrivenInsights', 'Utilizar inteligência artificial para analisar dados e fornecer insights valiosos, impulsionando a tomada de decisões informadas.', '2023-11-10', '2024-01-15', 2, 4, 5, 3, 1, 4, 5, 19800.00, 2, 3),
(7, 'Projeto: BlockchainSynergy', 'Implementar tecnologia blockchain para aumentar a transparência, segurança e eficiência em transações e contratos.', '2023-10-10', '2024-02-20', 5, 4, 5, 2, 3, 3, 2, 35950.00, 1, 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `situacao`
--

CREATE TABLE `situacao` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `situacao`
--

INSERT INTO `situacao` (`ID`, `Nome`) VALUES
(1, 'Planejamento'),
(2, 'Em Andamento'),
(3, 'Em Aprovação'),
(4, 'Finalizado'),
(5, 'Suspenso/Pausado');

-- --------------------------------------------------------

--
-- Estrutura da tabela `status_demanda`
--

CREATE TABLE `status_demanda` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `status_demanda`
--

INSERT INTO `status_demanda` (`ID`, `Nome`) VALUES
(1, 'Nova solicitação'),
(2, 'Aguardando aprovação de solicitação'),
(3, 'Aguardando aprovação da demanda'),
(4, 'Demanda Reprovada'),
(5, 'Estimando'),
(6, 'Desenvolvimento'),
(7, 'Aguarda aprovação N1'),
(8, 'Aguarda aprovação N2'),
(9, 'Aguardando Aceite'),
(10, 'Homologação'),
(11, 'Concluído'),
(12, 'Cancelado / Suspenso');

-- --------------------------------------------------------

--
-- Estrutura da tabela `subtipo_projeto`
--

CREATE TABLE `subtipo_projeto` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `subtipo_projeto`
--

INSERT INTO `subtipo_projeto` (`ID`, `Nome`) VALUES
(1, 'Sistemas'),
(2, 'Dashboards'),
(3, 'Infraestrutura'),
(4, 'Outros'),
(5, 'Segurança'),
(6, 'Big Data');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipos_contatos`
--

CREATE TABLE `tipos_contatos` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tipos_contatos`
--

INSERT INTO `tipos_contatos` (`ID`, `Nome`) VALUES
(1, 'Cliente'),
(2, 'Equipe Tecnica'),
(3, 'Suporte'),
(4, 'Coordenador');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_atividade`
--

CREATE TABLE `tipo_atividade` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tipo_atividade`
--

INSERT INTO `tipo_atividade` (`ID`, `Nome`) VALUES
(1, 'Melhoria'),
(2, 'Evolução'),
(3, 'Mudança de Escopo'),
(4, 'Alinhamento'),
(5, 'Reuniao'),
(6, 'Homologação'),
(7, 'Testes');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_demanda`
--

CREATE TABLE `tipo_demanda` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tipo_demanda`
--

INSERT INTO `tipo_demanda` (`ID`, `Nome`) VALUES
(1, 'Adequação Legal'),
(2, 'Ajuste'),
(3, 'Análise de Fonte de Dados'),
(4, 'Carga de Dados'),
(5, 'Erro'),
(6, 'Extração de Dados'),
(7, 'Implantação'),
(8, 'Melhorias'),
(9, 'Nova Funcionalidade'),
(10, 'Projeto'),
(11, 'Solicitação Eventual'),
(12, 'Suporte');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_projeto`
--

CREATE TABLE `tipo_projeto` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tipo_projeto`
--

INSERT INTO `tipo_projeto` (`ID`, `Nome`) VALUES
(1, 'Inovação'),
(2, 'Sustentação'),
(3, 'Pontual'),
(4, 'Evolução');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `permissao_admin` tinyint(1) DEFAULT 0,
  `permissao_editar` tinyint(1) DEFAULT 0,
  `permissao_excluir` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `senha`, `permissao_admin`, `permissao_editar`, `permissao_excluir`) VALUES
(1, 'Levi', '$2a$12$kQqmQsU2r1bz9QVq992Qn.doRYnoql3fPTQXKMsvuwmjQMWBx8shS', 1, 1, 1),
(2, 'Visitante', '$2a$12$X7kOogYCn773nyFCCJgsWOAp0CuhLlxaIhLneMfJX18rzNau5j5Om', 0, 0, 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `atividades`
--
ALTER TABLE `atividades`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_Projetos` (`Projeto_ID`),
  ADD KEY `fk_Entregas` (`Entrega_ID`),
  ADD KEY `fk_Tipo_Atividade` (`Tipo_Atividade_ID`),
  ADD KEY `fk_Situacao` (`Situacao_ID`);

--
-- Índices para tabela `cadastro_da_demanda`
--
ALTER TABLE `cadastro_da_demanda`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Status_demanda_ID` (`Status_demanda_ID`),
  ADD KEY `Tipo_demanda_ID` (`Tipo_demanda_ID`),
  ADD KEY `Projetos_ID` (`Projetos_ID`) USING BTREE,
  ADD KEY `Criticidade_ID` (`Criticidade_ID`) USING BTREE,
  ADD KEY `Coordenadoria_ID` (`Coordenadoria_ID`) USING BTREE;

--
-- Índices para tabela `contatos_tecnicos`
--
ALTER TABLE `contatos_tecnicos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Tipos_Contatos_ID` (`Tipos_Contatos_ID`),
  ADD KEY `fk_Coordenadorias` (`Coordenadorias_ID`);

--
-- Índices para tabela `coordenadoria`
--
ALTER TABLE `coordenadoria`
  ADD PRIMARY KEY (`ID`);

--
-- Índices para tabela `criticidade`
--
ALTER TABLE `criticidade`
  ADD PRIMARY KEY (`ID`);

--
-- Índices para tabela `entregas`
--
ALTER TABLE `entregas`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Situacao_ID` (`Situacao_ID`),
  ADD KEY `Tipo_Atividade_ID` (`Tipo_Atividade_ID`),
  ADD KEY `Projetos_ID` (`Projetos_ID`) USING BTREE;

--
-- Índices para tabela `gerente_projeto`
--
ALTER TABLE `gerente_projeto`
  ADD PRIMARY KEY (`ID`);

--
-- Índices para tabela `nivel_prioridade_projeto`
--
ALTER TABLE `nivel_prioridade_projeto`
  ADD PRIMARY KEY (`ID`);

--
-- Índices para tabela `projetos`
--
ALTER TABLE `projetos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Situacao_ID` (`Situacao_ID`),
  ADD KEY `Tipo_Projeto_ID` (`Tipo_Projeto_ID`),
  ADD KEY `Subtipo_Projeto_ID` (`Subtipo_Projeto_ID`),
  ADD KEY `Nivel_Prioridade_Projeto_ID` (`Nivel_Prioridade_Projeto_ID`),
  ADD KEY `Tipos_Contatos_ID` (`Tipos_Contatos_ID`),
  ADD KEY `Gerente_Projeto_ID` (`Gerente_Projeto_ID`),
  ADD KEY `Contatos_Tecnicos_ID` (`Contatos_Tecnicos_ID`) USING BTREE,
  ADD KEY `Coordenadorias_ID` (`Coordenadoria_ID`) USING BTREE,
  ADD KEY `Criticidade_ID` (`Criticidade_ID`) USING BTREE;

--
-- Índices para tabela `situacao`
--
ALTER TABLE `situacao`
  ADD PRIMARY KEY (`ID`);

--
-- Índices para tabela `status_demanda`
--
ALTER TABLE `status_demanda`
  ADD PRIMARY KEY (`ID`);

--
-- Índices para tabela `subtipo_projeto`
--
ALTER TABLE `subtipo_projeto`
  ADD PRIMARY KEY (`ID`);

--
-- Índices para tabela `tipos_contatos`
--
ALTER TABLE `tipos_contatos`
  ADD PRIMARY KEY (`ID`);

--
-- Índices para tabela `tipo_atividade`
--
ALTER TABLE `tipo_atividade`
  ADD PRIMARY KEY (`ID`);

--
-- Índices para tabela `tipo_demanda`
--
ALTER TABLE `tipo_demanda`
  ADD PRIMARY KEY (`ID`);

--
-- Índices para tabela `tipo_projeto`
--
ALTER TABLE `tipo_projeto`
  ADD PRIMARY KEY (`ID`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `atividades`
--
ALTER TABLE `atividades`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `cadastro_da_demanda`
--
ALTER TABLE `cadastro_da_demanda`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `contatos_tecnicos`
--
ALTER TABLE `contatos_tecnicos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `entregas`
--
ALTER TABLE `entregas`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `projetos`
--
ALTER TABLE `projetos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `atividades`
--
ALTER TABLE `atividades`
  ADD CONSTRAINT `fk_Entregas` FOREIGN KEY (`Entrega_ID`) REFERENCES `entregas` (`ID`),
  ADD CONSTRAINT `fk_Projetos` FOREIGN KEY (`Projeto_ID`) REFERENCES `projetos` (`ID`),
  ADD CONSTRAINT `fk_Situacao` FOREIGN KEY (`Situacao_ID`) REFERENCES `situacao` (`ID`),
  ADD CONSTRAINT `fk_Tipo_Atividade` FOREIGN KEY (`Tipo_Atividade_ID`) REFERENCES `tipo_atividade` (`ID`);

--
-- Limitadores para a tabela `cadastro_da_demanda`
--
ALTER TABLE `cadastro_da_demanda`
  ADD CONSTRAINT `cadastro_da_demanda_ibfk_1` FOREIGN KEY (`Status_demanda_ID`) REFERENCES `status_demanda` (`ID`),
  ADD CONSTRAINT `cadastro_da_demanda_ibfk_2` FOREIGN KEY (`Tipo_demanda_ID`) REFERENCES `tipo_demanda` (`ID`),
  ADD CONSTRAINT `fk_cadastro_demanda_criticidade` FOREIGN KEY (`Criticidade_ID`) REFERENCES `criticidade` (`ID`),
  ADD CONSTRAINT `fk_cadastro_demanda_projetos` FOREIGN KEY (`Projetos_ID`) REFERENCES `projetos` (`ID`),
  ADD CONSTRAINT `fk_cordenadoria` FOREIGN KEY (`Coordenadoria_ID`) REFERENCES `coordenadoria` (`ID`);

--
-- Limitadores para a tabela `contatos_tecnicos`
--
ALTER TABLE `contatos_tecnicos`
  ADD CONSTRAINT `contatos_tecnicos_ibfk_1` FOREIGN KEY (`Tipos_Contatos_ID`) REFERENCES `tipos_contatos` (`ID`),
  ADD CONSTRAINT `fk_Coordenadorias` FOREIGN KEY (`Coordenadorias_ID`) REFERENCES `coordenadoria` (`ID`);

--
-- Limitadores para a tabela `entregas`
--
ALTER TABLE `entregas`
  ADD CONSTRAINT `entregas_ibfk_1` FOREIGN KEY (`Situacao_ID`) REFERENCES `situacao` (`ID`),
  ADD CONSTRAINT `entregas_ibfk_2` FOREIGN KEY (`Tipo_Atividade_ID`) REFERENCES `tipo_atividade` (`ID`),
  ADD CONSTRAINT `fk_Entregas_Projetos` FOREIGN KEY (`Projetos_ID`) REFERENCES `projetos` (`ID`);

--
-- Limitadores para a tabela `projetos`
--
ALTER TABLE `projetos`
  ADD CONSTRAINT `Gerente_Projeto_ID` FOREIGN KEY (`Gerente_Projeto_ID`) REFERENCES `gerente_projeto` (`ID`),
  ADD CONSTRAINT `fk_Coordenadoria` FOREIGN KEY (`Coordenadoria_ID`) REFERENCES `coordenadoria` (`ID`),
  ADD CONSTRAINT `fk_Criticidade` FOREIGN KEY (`Criticidade_ID`) REFERENCES `criticidade` (`ID`),
  ADD CONSTRAINT `projetos_ibfk_1` FOREIGN KEY (`Situacao_ID`) REFERENCES `situacao` (`ID`),
  ADD CONSTRAINT `projetos_ibfk_2` FOREIGN KEY (`Tipo_Projeto_ID`) REFERENCES `tipo_projeto` (`ID`),
  ADD CONSTRAINT `projetos_ibfk_3` FOREIGN KEY (`Subtipo_Projeto_ID`) REFERENCES `subtipo_projeto` (`ID`),
  ADD CONSTRAINT `projetos_ibfk_4` FOREIGN KEY (`Nivel_Prioridade_Projeto_ID`) REFERENCES `nivel_prioridade_projeto` (`ID`),
  ADD CONSTRAINT `projetos_ibfk_7` FOREIGN KEY (`Tipos_Contatos_ID`) REFERENCES `tipos_contatos` (`ID`),
  ADD CONSTRAINT `projetos_ibfk_8` FOREIGN KEY (`Contatos_Tecnicos_ID`) REFERENCES `contatos_tecnicos` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
