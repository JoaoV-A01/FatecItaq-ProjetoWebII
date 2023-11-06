-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/11/2023 às 14:09
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bd_webii`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `endereco`
--

CREATE TABLE `endereco` (
  `id` int(11) NOT NULL,
  `cep` varchar(10) NOT NULL,
  `rua` varchar(100) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `iduser` int(11) DEFAULT NULL,
  `data_criado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `endereco`
--

INSERT INTO `endereco` (`id`, `cep`, `rua`, `bairro`, `cidade`, `uf`, `iduser`, `data_criado`) VALUES
(1, '08475-080', 'Rua Cachoeira Triunfo', 'Conjunto Habitacional Castro Alves', 'São Paulo', 'SP', 2, '2023-10-28 19:18:10');

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `idades`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `idades` (
`idades` varchar(11)
,`pessoas` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_venda`
--

CREATE TABLE `itens_venda` (
  `ID` int(11) NOT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `quantidadeProduto` int(11) DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `preco` decimal(9,2) NOT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `data_criado` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `preco`, `quantidade`, `data_criado`) VALUES
(1, 'Camisa Preta', 80.00, 5, '2023-10-28 19:08:21'),
(2, 'Sanduiche', 10.00, 3, '2023-10-30 01:03:39'),
(3, 'Creatina', 100.00, 10, '2023-10-30 01:04:02');

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `produtos_por_usuario`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `produtos_por_usuario` (
`id` int(11)
,`nome` varchar(50)
,`quantidade_produtos` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `token`
--

CREATE TABLE `token` (
  `id` int(11) NOT NULL,
  `token` text DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tempo` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `datanasc` date DEFAULT NULL,
  `senha` varchar(20) DEFAULT NULL,
  `data_criado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `datanasc`, `senha`, `data_criado`) VALUES
(1, 'Lima da Silva', 'lima.silva100@gmail.com', '2000-10-18', '123', '2023-10-27 21:31:06'),
(2, 'joji', 'joji@gmail.com', '2005-02-25', '$2y$10$aNglQbwQI/0rN', '2023-10-28 19:18:10');

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendas`
--

CREATE TABLE `vendas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `data_criacao` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `vendas`
--

INSERT INTO `vendas` (`id`, `id_usuario`, `id_produto`, `data_criacao`) VALUES
(1, 2, 1, '2023-10-30 05:00:23'),
(2, 1, 1, '2023-10-30 05:04:23'),
(3, 1, 2, '2023-10-30 05:04:27'),
(4, 1, 3, '2023-10-30 05:04:29'),
(5, 2, 2, '2023-10-30 05:04:34'),
(6, 2, 3, '2023-10-30 05:04:36');

-- --------------------------------------------------------

--
-- Estrutura para view `idades`
--
DROP TABLE IF EXISTS `idades`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `idades`  AS SELECT CASE WHEN to_days(current_timestamp()) - to_days(`usuarios`.`datanasc`) <= 10 THEN 'criança' WHEN to_days(current_timestamp()) - to_days(`usuarios`.`datanasc`) > 10 AND to_days(current_timestamp()) - to_days(`usuarios`.`datanasc`) <= 18 THEN 'Adolescente' WHEN to_days(current_timestamp()) - to_days(`usuarios`.`datanasc`) > 18 THEN 'Adulto' ELSE 'Bêbe' END AS `idades`, count(`usuarios`.`datanasc`) AS `pessoas` FROM `usuarios` GROUP BY CASE WHEN to_days(current_timestamp()) - to_days(`usuarios`.`datanasc`) <= 10 THEN 'criança' WHEN to_days(current_timestamp()) - to_days(`usuarios`.`datanasc`) > 10 AND to_days(current_timestamp()) - to_days(`usuarios`.`datanasc`) <= 18 THEN 'Adolescente' WHEN to_days(current_timestamp()) - to_days(`usuarios`.`datanasc`) > 18 THEN 'Adulto' ELSE 'Bêbe' END ;

-- --------------------------------------------------------

--
-- Estrutura para view `produtos_por_usuario`
--
DROP TABLE IF EXISTS `produtos_por_usuario`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `produtos_por_usuario`  AS SELECT `u`.`id` AS `id`, `u`.`nome` AS `nome`, count(`v`.`id_produto`) AS `quantidade_produtos` FROM (`usuarios` `u` left join `vendas` `v` on(`u`.`id` = `v`.`id_usuario`)) GROUP BY `u`.`id` ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `endereco`
--
ALTER TABLE `endereco`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iduser` (`iduser`);

--
-- Índices de tabela `itens_venda`
--
ALTER TABLE `itens_venda`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_produto` (`id_produto`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `endereco`
--
ALTER TABLE `endereco`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `itens_venda`
--
ALTER TABLE `itens_venda`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `token`
--
ALTER TABLE `token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
