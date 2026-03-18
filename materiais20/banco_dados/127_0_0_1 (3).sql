-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Mar-2026 às 02:00
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `loja_ferragem`
--
CREATE DATABASE IF NOT EXISTS `loja_ferragem` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `loja_ferragem`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `caixa`
--

CREATE TABLE `caixa` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `status` enum('aberto','fechado') DEFAULT 'aberto',
  `data_abertura` datetime NOT NULL,
  `data_fechamento` datetime DEFAULT NULL,
  `valor_abertura` decimal(10,2) NOT NULL,
  `valor_fechamento` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `caixa`
--

INSERT INTO `caixa` (`id`, `usuario_id`, `status`, `data_abertura`, `data_fechamento`, `valor_abertura`, `valor_fechamento`) VALUES
(1, 1, 'fechado', '2025-12-26 23:11:33', '2025-12-29 18:49:51', 0.00, 19350.00),
(2, 2, 'fechado', '2025-12-27 01:29:09', '2025-12-27 01:29:56', 0.00, 0.00),
(3, 2, 'fechado', '2025-12-27 01:30:03', '2025-12-28 22:00:18', 100.00, 333110.00),
(4, 10, 'fechado', '2025-12-28 16:53:01', '2025-12-28 21:17:37', 0.00, 6650.00),
(5, 10, 'fechado', '2025-12-28 21:17:45', '2025-12-29 18:19:16', 0.00, 0.00),
(6, 2, 'fechado', '2025-12-28 22:00:22', '2026-01-03 20:36:12', 20.00, 20.00),
(7, 11, 'fechado', '2025-12-29 17:52:18', '2026-01-05 11:19:07', 2020.00, 2020.00),
(8, 10, 'fechado', '2025-12-29 18:19:25', '2025-12-29 18:53:23', 200.00, 1400.00),
(9, 1, 'fechado', '2025-12-29 18:50:05', '2025-12-31 02:01:23', 0.20, 53295.20),
(10, 10, 'fechado', '2025-12-29 18:53:32', '2025-12-31 04:09:24', 0.00, 49845.00),
(11, 1, 'fechado', '2025-12-31 02:01:30', '2025-12-31 04:08:21', 0.00, 32200.00),
(12, 1, 'fechado', '2025-12-31 04:08:27', '2026-01-03 20:27:34', 100.00, 10296.00),
(13, 10, 'fechado', '2025-12-31 04:09:38', '2026-01-05 11:21:48', 2.00, 9402.00),
(14, 1, 'fechado', '2026-01-03 20:27:44', '2026-01-04 19:59:35', 10.00, 10.00),
(15, 2, 'fechado', '2026-01-03 20:36:17', '2026-01-05 11:27:40', 100.00, 7700.00),
(16, 1, 'fechado', '2026-01-04 20:00:17', '2026-01-05 11:24:53', 20.00, 5470.00),
(17, 8, 'fechado', '2026-01-04 20:06:01', '2026-01-05 11:29:59', 20.00, 23320.00),
(18, 11, 'fechado', '2026-01-05 11:19:18', '2026-01-05 11:20:30', 10.00, 10.00),
(19, 1, 'fechado', '2026-01-05 12:00:00', '2026-01-05 13:24:15', 10.00, 220630.00),
(20, 1, 'fechado', '2026-01-05 13:24:32', '2026-01-05 13:44:35', 200.00, 200.00),
(21, 10, 'fechado', '2026-01-05 13:44:05', '2026-01-31 01:19:06', 0.00, 0.00),
(22, 1, 'fechado', '2026-01-05 13:44:40', '2026-01-05 13:46:15', 0.00, 0.00),
(23, 1, 'fechado', '2026-01-05 13:46:20', '2026-01-05 13:47:14', 0.00, 0.00),
(24, 1, 'fechado', '2026-01-07 19:27:35', '2026-01-15 01:01:25', 10.00, 48360.00),
(25, 8, 'fechado', '2026-01-07 21:37:11', '2026-01-12 22:33:15', 10.00, 33150.00),
(26, 8, 'fechado', '2026-01-12 22:33:23', '2026-01-14 19:46:08', 10.00, 10.00),
(27, 2, 'fechado', '2026-01-12 22:34:33', '2026-01-30 23:00:06', 10.00, 10.00),
(28, 8, 'fechado', '2026-01-14 19:46:16', '2026-01-15 01:02:23', 10.00, 460.00),
(29, 1, 'fechado', '2026-01-15 01:01:39', '2026-01-30 21:42:53', 10.00, 160.00),
(30, 8, 'fechado', '2026-01-15 01:02:28', '2026-01-31 00:03:22', 0.00, 0.00),
(31, 1, 'fechado', '2026-01-30 22:06:48', '2026-01-30 22:15:18', 0.00, 140.00),
(32, 1, 'fechado', '2026-01-30 22:50:00', '2026-01-30 22:58:35', 15.00, 3965.00),
(33, 2, 'fechado', '2026-01-30 23:00:28', '2026-01-30 23:11:37', 30.00, 3568.00),
(34, 1, 'fechado', '2026-01-30 23:12:37', '2026-01-30 23:12:47', 0.00, 0.00),
(35, 1, 'fechado', '2026-01-31 00:02:58', '2026-01-31 00:03:59', 0.00, 0.00),
(36, 1, 'fechado', '2026-01-31 00:05:15', '2026-01-31 00:34:28', 0.00, 0.00),
(37, 1, 'fechado', '2026-01-31 00:34:56', '2026-01-31 00:56:28', 10.00, 107675.00),
(38, 2, 'fechado', '2026-01-31 00:52:13', '2026-01-31 00:53:26', 20.00, 20.00),
(39, 2, 'fechado', '2026-01-31 00:53:56', '2026-01-31 00:55:03', 20.00, 20.00),
(40, 1, 'fechado', '2026-01-31 00:56:53', '2026-01-31 00:59:07', 20.00, 28618.00),
(41, 1, 'fechado', '2026-01-31 00:59:29', '2026-01-31 00:59:54', 20.00, 20.00),
(42, 2, 'fechado', '2026-01-31 01:00:10', '2026-01-31 01:00:41', 20.00, 20.00),
(43, 2, 'fechado', '2026-01-31 01:13:19', '2026-01-31 01:14:00', 20.00, 170.00),
(44, 8, 'fechado', '2026-01-31 01:14:21', '2026-01-31 01:18:27', 50.00, 26630.00),
(45, 10, 'fechado', '2026-01-31 01:19:33', '2026-01-31 01:20:40', 30.00, 24229.50),
(46, 1, 'fechado', '2026-01-31 13:54:43', '2026-01-31 14:43:37', 10.00, 2730.00),
(47, 1, 'fechado', '2026-01-31 15:18:55', '2026-01-31 15:20:30', 10.00, 10.00),
(48, 2, 'fechado', '2026-01-31 15:19:56', '2026-01-31 15:20:14', 10.00, 10.00),
(49, 8, 'fechado', '2026-01-31 15:25:17', '2026-01-31 15:26:21', 10.00, 310.00),
(50, 1, 'fechado', '2026-02-01 13:48:57', '2026-02-01 13:50:22', 10.00, 609.80),
(51, 2, 'fechado', '2026-02-01 15:02:25', '2026-02-01 15:15:44', 10.00, 3160.00),
(52, 2, 'fechado', '2026-02-01 15:19:49', '2026-02-01 15:19:56', 0.00, 0.00),
(53, 2, 'fechado', '2026-02-02 14:15:53', '2026-02-02 14:16:12', 0.00, 0.00),
(54, 1, 'fechado', '2026-02-02 14:16:41', '2026-02-02 14:21:21', 0.00, 600.00),
(55, 1, 'fechado', '2026-02-02 15:55:39', '2026-02-02 15:57:19', 0.00, 0.00),
(56, 2, 'fechado', '2026-02-02 15:56:10', '2026-02-02 15:56:58', 20.00, 20.00),
(57, 1, 'fechado', '2026-02-02 16:05:42', '2026-02-02 17:04:25', 0.00, 0.00),
(58, 1, 'fechado', '2026-02-02 17:15:47', '2026-02-02 17:48:04', 0.00, 6550.00),
(59, 2, 'fechado', '2026-02-02 17:42:04', '2026-02-02 17:46:59', 10.00, 1210.00),
(60, 1, 'fechado', '2026-02-02 19:01:06', '2026-02-02 20:28:15', 0.00, 39250.00),
(61, 2, 'fechado', '2026-02-02 20:30:14', '2026-02-02 20:32:20', 20.00, 3610.00),
(62, 10, 'fechado', '2026-02-02 20:33:08', '2026-02-02 20:36:06', 0.00, 0.00),
(63, 1, 'aberto', '2026-03-03 20:50:11', NULL, 100.00, NULL),
(64, 10, 'fechado', '2026-03-03 21:15:25', '2026-03-13 13:43:30', 10.00, 10.00),
(65, 10, 'aberto', '2026-03-13 13:44:03', NULL, 200.00, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `devedores`
--

CREATE TABLE `devedores` (
  `id_devedor` int(11) NOT NULL,
  `nome_cliente` varchar(150) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `material` varchar(150) DEFAULT NULL,
  `valor_divida` decimal(10,2) DEFAULT NULL,
  `data_divida` date DEFAULT NULL,
  `data_vencimento` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `devedores`
--

INSERT INTO `devedores` (`id_devedor`, `nome_cliente`, `telefone`, `material`, `valor_divida`, `data_divida`, `data_vencimento`, `status`) VALUES
(1, 'Joao', '844388815', 'cimento, ferro, prego, tubo 110, porta', 25000.00, '2026-03-13', '2026-03-18', 'PAGO'),
(5, 'Contrução Pemba', '5198457610', 'cimento, ferro, prego, tubo 110, porta', 55000.00, '2026-03-13', '2026-04-10', 'ABERTO');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `id_fornecedor` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `cnpj_cpf` varchar(20) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `endereco` varchar(200) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `data_cadastro` date DEFAULT NULL,
  `tipo_material` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `fornecedores`
--

INSERT INTO `fornecedores` (`id_fornecedor`, `nome`, `cnpj_cpf`, `telefone`, `email`, `endereco`, `cidade`, `estado`, `data_cadastro`, `tipo_material`) VALUES
(3, 'Lino - venda cimento', '51246852130', '843141563', 'chafimchinamulungo@gmail.com', 'Rua Porto Alegre, 007', 'Porto', 'RS', '2026-03-13', 'cimento vermelho'),
(4, 'Mario Chineider', '85647123512', '5198457610', 'chafim@gmail.com', 'farapos, 1001', 'Porto Alegre', 'RS', '2026-03-13', 'vaso sanitario'),
(5, 'Mario Chineider', '85647123512', '5198457610', 'chafim@gmail.com', 'farapos, 1001', 'Porto Alegre', 'RS', '2026-03-13', 'ferro - varão 14'),
(6, 'Natalia Chinamulungo', '31659874514', '5198457610', 'chafim@gmail.com', 'farapos, 1001', 'Porto Alegre', 'RS', '2026-03-13', 'Pregos 1 -3'),
(7, 'Mario Chineider', '12563489562', '5198457611', 'mario@gmail.com', 'farapos, 1001', 'Porto Alegre', 'RS', '2026-03-13', 'tintas '),
(8, 'solar', '12536498512', '5198457610', 'solar@gmail.com', 'Rua Porto Alegre, 007', 'Porto', 'RS', '2026-03-13', 'capacetes');

-- --------------------------------------------------------

--
-- Estrutura da tabela `itens_venda`
--

CREATE TABLE `itens_venda` (
  `id` int(11) NOT NULL,
  `venda_id` int(11) DEFAULT NULL,
  `material_id` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `itens_venda`
--

INSERT INTO `itens_venda` (`id`, `venda_id`, `material_id`, `quantidade`, `preco_unitario`, `total`) VALUES
(1, 1, 1, 10, 550.00, 5500.00),
(2, 1, 5, 5, 600.00, 3000.00),
(3, 1, 17, 3, 450.00, 1350.00),
(4, 2, 1, 10, 550.00, 5500.00),
(5, 2, 5, 5, 600.00, 3000.00),
(6, 2, 17, 3, 450.00, 1350.00),
(7, 3, 1, 10, 550.00, 5500.00),
(8, 3, 5, 5, 600.00, 3000.00),
(9, 3, 10, 4, 150.00, 600.00),
(10, 3, 17, 3, 450.00, 1350.00),
(11, 4, 5, 7, 600.00, 4200.00),
(12, 4, 1, 4, 550.00, 2200.00),
(13, 4, 6, 10, 1100.00, 11000.00),
(14, 4, 9, 40, 1500.00, 60000.00),
(15, 5, 10, 10, 150.00, 1500.00),
(16, 5, 14, 2, 1200.00, 2400.00),
(17, 5, 13, 5, 260.00, 1300.00),
(18, 5, 3, 6, 100.00, 600.00),
(19, 5, 8, 8, 2200.00, 17600.00),
(20, 6, 10, 10, 150.00, 1500.00),
(21, 6, 17, 3, 450.00, 1350.00),
(22, 6, 14, 2, 1200.00, 2400.00),
(23, 6, 13, 5, 260.00, 1300.00),
(24, 6, 3, 6, 100.00, 600.00),
(25, 6, 8, 8, 2200.00, 17600.00),
(26, 7, 1, 5, 550.00, 2750.00),
(27, 7, 17, 10, 450.00, 4500.00),
(28, 8, 14, 500, 1200.00, 600000.00),
(29, 9, 1, 10, 550.00, 5500.00),
(30, 9, 7, 10, 600.00, 6000.00),
(31, 9, 17, 10, 450.00, 4500.00),
(32, 10, 10, 20, 150.00, 3000.00),
(33, 11, 1, 1, 550.00, 550.00),
(34, 11, 2, 40, 250.00, 10000.00),
(35, 11, 3, 5, 100.00, 500.00),
(36, 12, 3, 4, 100.00, 400.00),
(37, 12, 5, 6, 600.00, 3600.00),
(38, 13, 3, 5, 100.00, 500.00),
(39, 13, 5, 7, 600.00, 4200.00),
(40, 14, 5, 4, 600.00, 2400.00),
(41, 14, 3, 3, 100.00, 300.00),
(42, 15, 5, 4, 600.00, 2400.00),
(43, 15, 3, 3, 100.00, 300.00),
(44, 15, 4, 3, 2800.00, 8400.00),
(45, 16, 4, 4, 2800.00, 11200.00),
(46, 16, 5, 4, 600.00, 2400.00),
(47, 16, 6, 7, 1100.00, 7700.00),
(48, 17, 1, 20, 550.00, 11000.00),
(49, 18, 1, 100, 550.00, 55000.00),
(50, 19, 10, 2, 150.00, 300.00),
(51, 19, 7, 6, 600.00, 3600.00),
(52, 20, 4, 4, 2800.00, 11200.00),
(53, 24, 8, 4, 2200.00, NULL),
(54, 25, 10, 10, 150.00, NULL),
(55, 25, 6, 4, 1100.00, NULL),
(56, 26, 1, 10, 550.00, NULL),
(57, 27, 1, 10, 550.00, NULL),
(58, 27, 5, 2, 600.00, NULL),
(59, 27, 4, 6, 2800.00, NULL),
(60, 28, 4, 7, 2800.00, NULL),
(61, 29, 4, 4, 2800.00, NULL),
(62, 30, 4, 4, 2800.00, NULL),
(63, 31, 4, 4, 2800.00, NULL),
(64, 31, 7, 6, 600.00, NULL),
(65, 32, 4, 4, 2800.00, NULL),
(66, 33, 4, 4, 2800.00, NULL),
(67, 34, 4, 4, 2800.00, NULL),
(68, 35, 4, 4, 2800.00, NULL),
(69, 35, 2, 4, 250.00, NULL),
(70, 36, 4, 4, 2800.00, NULL),
(71, 36, 2, 4, 250.00, NULL),
(72, 37, 4, 5, 2800.00, NULL),
(73, 38, 4, 2, 2800.00, NULL),
(74, 39, 4, 2, 2800.00, NULL),
(75, 40, 4, 4, 2800.00, NULL),
(76, 40, 8, 4, 2200.00, NULL),
(77, 41, 8, 7, 2200.00, NULL),
(78, 42, 8, 8, 2200.00, NULL),
(79, 43, 8, 7, 2200.00, NULL),
(80, 44, 8, 1, 2200.00, NULL),
(81, 45, 8, 4, 2200.00, NULL),
(82, 45, 3, 6, 100.00, NULL),
(83, 45, 4, 9, 2800.00, NULL),
(84, 46, 4, 4, 2800.00, NULL),
(85, 47, 4, 7, 2800.00, NULL),
(86, 48, 4, 4, 2800.00, NULL),
(87, 48, 8, 4, 2200.00, NULL),
(88, 49, 8, 4, 2200.00, NULL),
(89, 50, 8, 7, 2200.00, NULL),
(90, 52, 17, 4, 450.00, 1800.00),
(91, 52, 10, 3, 150.00, 450.00),
(92, 53, 8, 6, 2200.00, NULL),
(93, 53, 10, 4, 150.00, NULL),
(94, 54, 8, 6, 2200.00, NULL),
(95, 54, 10, 4, 150.00, NULL),
(96, 56, 17, 8, 450.00, 3600.00),
(97, 56, 10, 2, 150.00, 300.00),
(98, 57, 10, 7, 150.00, NULL),
(99, 58, 10, 7, 150.00, NULL),
(100, 59, 10, 8, 150.00, NULL),
(101, 59, 16, 5, 350.00, NULL),
(102, 60, 16, 4, 350.00, NULL),
(103, 61, 16, 5, 350.00, NULL),
(104, 62, 16, 50, 350.00, NULL),
(105, 63, 16, 7, 350.00, NULL),
(106, 64, 16, 10, 350.00, NULL),
(107, 65, 10, 5, 150.00, NULL),
(108, 66, 10, 7, 150.00, NULL),
(109, 67, 1, 10, 550.00, NULL),
(110, 67, 10, 4, 150.00, NULL),
(111, 68, 10, 20, 150.00, NULL),
(112, 69, 3, 20, 100.00, NULL),
(113, 70, 1, 850, 550.00, NULL),
(114, 71, 7, 99, 600.00, NULL),
(115, 72, 1, 10, 550.00, NULL),
(116, 73, 5, 3, 600.00, NULL),
(117, 74, 5, 3, 600.00, NULL),
(118, 75, 5, 1, 600.00, NULL),
(119, 76, 5, 1, 600.00, NULL),
(120, 77, 5, 1, 600.00, NULL),
(121, 78, 5, 1, 600.00, NULL),
(122, 79, 5, 1, 600.00, NULL),
(123, 80, 5, 10, 600.00, NULL),
(124, 80, 6, 4, 1100.00, NULL),
(125, 80, 9, 6, 1500.00, NULL),
(126, 81, 9, 10, 1500.00, NULL),
(127, 81, 6, 5, 1100.00, NULL),
(128, 82, 6, 1, 1100.00, NULL),
(129, 83, 6, 6, 1100.00, NULL),
(130, 83, 9, 4, 1500.00, NULL),
(131, 84, 9, 50, 1500.00, NULL),
(132, 85, 9, 10, 1500.00, NULL),
(133, 85, 10, 5, 150.00, NULL),
(134, 86, 10, 10, 150.00, NULL),
(135, 87, 10, 1, 150.00, NULL),
(136, 88, 10, 2, 150.00, NULL),
(137, 89, 10, 1, 150.00, NULL),
(138, 90, 10, 1, 150.00, NULL),
(139, 91, 10, 1, 150.00, NULL),
(140, 92, 10, 1, 150.00, NULL),
(141, 93, 10, 1, 150.00, NULL),
(142, 94, 10, 10, 150.00, NULL),
(143, 94, 6, 6, 1100.00, NULL),
(144, 95, 4, 6, 2800.00, NULL),
(145, 96, 1, 5, 550.00, NULL),
(146, 97, 10, 5, 150.00, NULL),
(147, 98, 10, 10, 150.00, NULL),
(148, 99, 10, 10, 150.00, NULL),
(149, 100, 10, 10, 150.00, NULL),
(150, 100, 1, 6, 550.00, NULL),
(151, 101, 10, 10, 150.00, NULL),
(152, 102, 4, 1, 2800.00, NULL),
(153, 103, 4, 110, 2800.00, NULL),
(154, 104, 10, 2, 150.00, NULL),
(155, 104, 16, 6, 350.00, NULL),
(156, 105, 10, 1, 150.00, NULL),
(157, 106, 10, 1, 150.00, NULL),
(158, 107, 10, 2, 150.00, NULL),
(159, 108, 10, 2, 150.00, NULL),
(160, 109, 10, 2, 150.00, NULL),
(161, 110, 10, 2, 150.00, NULL),
(162, 110, 4, 1, 2800.00, NULL),
(163, 111, 4, 1, 2800.00, NULL),
(164, 112, 10, 1, 150.00, NULL),
(165, 112, 8, 1, 2200.00, NULL),
(166, 112, 5, 1, 600.00, NULL),
(167, 112, 16, 1, 350.00, NULL),
(168, 112, 3, 1, 100.00, NULL),
(169, 112, 9, 1, 1500.00, NULL),
(170, 112, 6, 1, 1100.00, NULL),
(171, 113, 10, 1, 150.00, NULL),
(172, 113, 8, 1, 2200.00, NULL),
(173, 113, 16, 1, 350.00, NULL),
(174, 113, 9, 1, 1500.00, NULL),
(175, 113, 6, 1, 1100.00, NULL),
(176, 113, 7, 1, 600.00, NULL),
(177, 113, 1, 1, 550.00, NULL),
(178, 113, 12, 1, 50.00, NULL),
(179, 114, 10, 1, 150.00, NULL),
(180, 114, 8, 1, 2200.00, NULL),
(181, 114, 16, 1, 350.00, NULL),
(182, 114, 9, 1, 1500.00, NULL),
(183, 114, 6, 1, 1100.00, NULL),
(184, 114, 7, 1, 600.00, NULL),
(185, 114, 1, 1, 550.00, NULL),
(186, 114, 12, 1, 50.00, NULL),
(187, 115, 10, 1, 150.00, NULL),
(188, 116, 10, 1, 150.00, NULL),
(189, 117, 10, 1, 150.00, NULL),
(190, 118, 10, 5, 150.00, NULL),
(191, 118, 18, 10, 600.00, NULL),
(192, 119, 10, 1, 150.00, NULL),
(193, 120, 10, 1, 150.00, NULL),
(194, 121, 5, 2, 600.00, NULL),
(195, 122, 8, 2, 2200.00, NULL),
(196, 123, 18, 20, 600.00, NULL),
(197, 124, 27, 9, 0.00, NULL),
(198, 124, 8, 4, 0.00, NULL),
(199, 125, 10, 1, 0.00, NULL),
(200, 126, 10, 2, 0.00, NULL),
(201, 127, 4, 1, 0.00, NULL),
(202, 128, 10, 1, 0.00, NULL),
(203, 129, 10, 1, 0.00, NULL),
(204, 130, 4, 1, 0.00, NULL),
(205, 131, 4, 1, 2800.00, NULL),
(206, 131, 16, 1, 350.00, NULL),
(207, 132, 10, 1, 150.00, NULL),
(208, 133, 8, 1, 2200.00, NULL),
(209, 133, 5, 1, 600.00, NULL),
(210, 134, 8, 1, 2200.00, NULL),
(211, 134, 5, 1, 600.00, NULL),
(212, 135, 8, 1, 2200.00, NULL),
(213, 135, 5, 1, 600.00, NULL),
(214, 136, 8, 1, 2200.00, NULL),
(215, 136, 5, 1, 600.00, NULL),
(216, 137, 8, 1, 2200.00, NULL),
(217, 137, 5, 1, 600.00, NULL),
(218, 138, 8, 1, 2200.00, NULL),
(219, 138, 5, 1, 600.00, NULL),
(220, 139, 8, 1, 2200.00, NULL),
(221, 139, 5, 1, 600.00, NULL),
(222, 140, 8, 1, 2200.00, NULL),
(223, 140, 5, 1, 600.00, NULL),
(224, 141, 10, 10, 150.00, NULL),
(225, 141, 1, 13, 550.00, NULL),
(226, 142, 10, 10, 150.00, NULL),
(227, 142, 1, 13, 550.00, NULL),
(228, 142, 16, 11, 350.00, NULL),
(229, 143, 1, 10, 550.00, NULL),
(230, 144, 1, 1, 550.00, NULL),
(231, 144, 2, 1, 250.00, NULL),
(232, 145, 15, 1, 1200.00, NULL),
(233, 146, 8, 4, 2200.00, NULL),
(234, 147, 4, 1, 2800.00, NULL),
(235, 148, 4, 1, 2800.00, NULL),
(236, 149, 4, 1, 2800.00, NULL),
(237, 150, 10, 10, 150.00, NULL),
(238, 151, 1, 12, 550.00, NULL),
(239, 151, 2, 4, 250.00, NULL),
(240, 152, 1, 1, 550.00, NULL),
(241, 153, 1, 1, 550.00, NULL),
(242, 154, 1, 1, 550.00, 550.00),
(243, 155, 20, 1, 250.00, 250.00),
(244, 156, 4, 1, 2800.00, 2800.00),
(245, 157, 16, 1, 350.00, 350.00),
(246, 158, 10, 1, 150.00, 150.00),
(247, 158, 4, 1, 2800.00, 2800.00),
(248, 158, 16, 1, 350.00, 350.00),
(249, 159, 10, 1, 150.00, 150.00),
(250, 159, 4, 1, 2800.00, 2800.00),
(251, 159, 16, 1, 350.00, 350.00),
(252, 160, 10, 1, 150.00, 150.00),
(253, 161, 16, 1, 350.00, 350.00),
(254, 162, 10, 2, 150.00, 300.00),
(255, 163, 10, 1, 150.00, 150.00),
(256, 164, 5, 4, 600.00, 2400.00),
(257, 164, 1, 4, 550.00, 2200.00),
(258, 164, 8, 3, 2200.00, 6600.00),
(259, 164, 16, 14, 350.00, 4900.00),
(260, 164, 28, 4, 2800.00, 11200.00),
(261, 165, 8, 3, 2200.00, 6600.00),
(262, 166, 16, 2, 350.00, 700.00),
(263, 167, 17, 5, 450.00, 2250.00),
(264, 168, 10, 1, 150.00, 150.00),
(265, 169, 10, 1, 150.00, 150.00),
(266, 169, 4, 1, 2800.00, 2800.00),
(267, 169, 8, 1, 2200.00, 2200.00),
(268, 169, 16, 1, 350.00, 350.00),
(269, 169, 5, 1, 600.00, 600.00),
(270, 169, 9, 1, 1500.00, 1500.00),
(271, 170, 10, 2, 150.00, 300.00),
(272, 171, 10, 2, 150.00, 300.00),
(273, 171, 8, 4, 2200.00, 8800.00),
(274, 172, 16, 3, 350.00, 1050.00),
(275, 172, 8, 2, 2200.00, 4400.00),
(276, 173, 10, 2, 150.00, 300.00),
(277, 174, 10, 2, 150.00, 300.00),
(278, 174, 4, 4, 2800.00, 11200.00),
(279, 175, 10, 2, 150.00, 300.00),
(280, 175, 4, 4, 2800.00, 11200.00),
(281, 176, 18, 5, 600.00, 3000.00),
(282, 176, 5, 1, 600.00, 600.00),
(283, 176, 10, 7, 150.00, 1050.00),
(284, 177, 35, 999, 160.00, 159840.00),
(285, 178, 35, 101, 160.00, 16160.00),
(286, 179, 35, 102, 160.00, 16320.00),
(287, 180, 35, 10, 160.00, 1600.00),
(288, 181, 4, 1, 2800.00, 2800.00),
(289, 182, 40, 55, 350.00, 19250.00),
(290, 183, 1, 10, 550.00, 5500.00),
(291, 183, 2, 15, 250.00, 3750.00),
(292, 184, 10, 1, 150.00, 150.00),
(293, 184, 4, 1, 2800.00, 2800.00),
(294, 184, 8, 1, 2200.00, 2200.00),
(295, 184, 16, 1, 350.00, 350.00),
(296, 184, 5, 1, 600.00, 600.00),
(297, 185, 10, 2, 150.00, 300.00),
(298, 185, 8, 4, 2200.00, 8800.00),
(299, 185, 16, 4, 350.00, 1400.00),
(300, 185, 5, 5, 600.00, 3000.00),
(301, 185, 12, 1, 50.00, 50.00),
(302, 186, 10, 2, 150.00, 300.00),
(303, 186, 8, 4, 2200.00, 8800.00),
(304, 186, 16, 4, 350.00, 1400.00),
(305, 186, 5, 5, 600.00, 3000.00),
(306, 186, 12, 1, 50.00, 50.00),
(307, 187, 17, 2, 450.00, 900.00),
(308, 187, 1, 10, 550.00, 5500.00),
(309, 187, 2, 2, 250.00, 500.00),
(310, 187, 10, 5, 150.00, 750.00),
(311, 187, 9, 1, 1500.00, 1500.00),
(312, 187, 12, 1, 50.00, 50.00),
(313, 187, 14, 1, 1200.00, 1200.00),
(314, 187, 15, 1, 1200.00, 1200.00),
(315, 187, 16, 1, 350.00, 350.00),
(316, 188, 10, 2, 150.00, 300.00),
(317, 189, 4, 1, 2800.00, 2800.00),
(318, 190, 10, 1, 150.00, 150.00),
(319, 191, 4, 1, 2800.00, 2800.00),
(320, 192, 16, 4, 350.00, 1400.00),
(321, 193, 4, 1, 2800.00, 2800.00),
(322, 194, 1, 4, 550.00, 2200.00),
(323, 195, 1, 1, 550.00, 550.00),
(324, 195, 2, 1, 250.00, 250.00),
(325, 195, 3, 1, 100.00, 100.00),
(326, 195, 4, 1, 2800.00, 2800.00),
(327, 195, 5, 1, 600.00, 600.00),
(328, 195, 6, 1, 1100.00, 1100.00),
(329, 195, 7, 1, 600.00, 600.00),
(330, 195, 8, 1, 2200.00, 2200.00),
(331, 195, 9, 1, 1500.00, 1500.00),
(332, 195, 10, 1, 150.00, 150.00),
(333, 195, 11, 1, 75.00, 75.00),
(334, 195, 12, 1, 50.00, 50.00),
(335, 195, 14, 1, 1200.00, 1200.00),
(336, 195, 15, 1, 1200.00, 1200.00),
(337, 195, 16, 1, 350.00, 350.00),
(338, 195, 17, 1, 450.00, 450.00),
(339, 195, 18, 1, 600.00, 600.00),
(340, 195, 19, 1, 1500.00, 1500.00),
(341, 195, 20, 1, 250.00, 250.00),
(342, 195, 21, 1, 350.00, 350.00),
(343, 195, 22, 1, 2500.00, 2500.00),
(344, 195, 24, 1, 1500.00, 1500.00),
(345, 195, 25, 1, 1400.00, 1400.00),
(346, 195, 26, 1, 1600.00, 1600.00),
(347, 195, 27, 1, 2400.00, 2400.00),
(348, 195, 28, 1, 2800.00, 2800.00),
(349, 195, 29, 1, 550.00, 550.00),
(350, 195, 30, 1, 25.00, 25.00),
(351, 196, 2, 4, 250.00, 1000.00),
(352, 197, 10, 1, 150.00, 150.00),
(353, 197, 4, 1, 2800.00, 2800.00),
(354, 198, 10, 2, 150.00, 300.00),
(355, 199, 10, 1, 150.00, 150.00),
(356, 200, 47, 3, 100.00, 300.00),
(357, 201, 10, 1, 150.00, 150.00),
(358, 202, 4, 1, 2800.00, 2800.00),
(359, 203, 10, 1, 150.00, 150.00),
(360, 204, 10, 3, 150.00, 450.00),
(361, 205, 10, 1, 150.00, 150.00),
(362, 206, 10, 1, 150.00, 150.00),
(363, 207, 10, 3, 150.00, 450.00),
(364, 207, 16, 10, 350.00, 3500.00),
(365, 208, 16, 5, 350.00, 1750.00),
(366, 208, 5, 3, 600.00, 1800.00),
(367, 209, 5, 12, 600.00, 7200.00),
(368, 209, 8, 18, 2200.00, 39600.00),
(369, 209, 4, 4, 2800.00, 11200.00),
(370, 209, 10, 10, 150.00, 1500.00),
(371, 209, 19, 13, 1500.00, 19500.00),
(372, 209, 30, 32, 25.00, 800.00),
(373, 210, 10, 10, 150.00, 1500.00),
(374, 210, 8, 12, 2200.00, 26400.00),
(375, 211, 8, 13, 2200.00, 28600.00),
(376, 212, 10, 1, 150.00, 150.00),
(377, 213, 10, 6, 150.00, 900.00),
(378, 213, 8, 9, 2200.00, 19800.00),
(379, 214, 4, 2, 2800.00, 5600.00),
(380, 215, 10, 2, 150.00, 300.00),
(381, 216, 8, 11, 2200.00, 24200.00),
(382, 217, 40, 4, 350.00, 1400.00),
(383, 217, 16, 4, 350.00, 1400.00),
(384, 218, 10, 2, 150.00, 300.00),
(385, 219, 10, 4, 150.00, 600.00),
(386, 220, 16, 9, 350.00, 3150.00),
(387, 221, 10, 4, 150.00, 600.00),
(388, 222, 10, 3, 150.00, 450.00),
(389, 223, 16, 3, 350.00, 1050.00),
(390, 224, 4, 2, 2800.00, 5600.00),
(391, 225, 5, 2, 600.00, 1200.00),
(392, 226, 4, 5, 2800.00, 14000.00),
(393, 226, 8, 5, 2200.00, 11000.00),
(394, 226, 5, 6, 600.00, 3600.00),
(395, 226, 7, 5, 600.00, 3000.00),
(396, 226, 9, 5, 1500.00, 7500.00),
(397, 226, 10, 1, 150.00, 150.00),
(398, 227, 5, 6, 600.00, 3600.00),
(399, 228, 1, 1, 550.00, 550.00),
(400, 228, 10, 1, 150.00, 150.00),
(401, 229, 1, 12, 550.00, 6600.00),
(402, 229, 17, 2, 450.00, 900.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `acao` varchar(50) NOT NULL,
  `material_id` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `data_hora` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `logs`
--

INSERT INTO `logs` (`id`, `usuario`, `acao`, `material_id`, `quantidade`, `data_hora`) VALUES
(1, 'Chafim', 'cancelar', 4, 4, '2025-12-21 03:51:08'),
(2, 'Chafim', 'venda_cancelada', NULL, NULL, '2025-12-21 03:51:08'),
(3, '1', 'Cancelou a venda ID 165. Motivo: cliente nao pagou', NULL, NULL, '2026-01-02 17:32:13'),
(4, '1', 'Cancelou a venda ID 163. Motivo: sem saldo na cont', NULL, NULL, '2026-01-02 17:34:00'),
(5, '1', 'Cancelou a venda ID 155. Motivo: cliente reclamou ', NULL, NULL, '2026-01-02 17:35:09'),
(6, '1', 'Cancelou a venda ID 152. Motivo: cliente reclamou ', NULL, NULL, '2026-01-02 17:36:06'),
(7, '1', 'Cancelou a venda ID 157. Motivo: cliente sem total', NULL, NULL, '2026-01-02 17:44:14'),
(8, '1', 'Cancelou a venda ID 162. Motivo: cheque falso', NULL, NULL, '2026-01-02 17:57:05'),
(9, '1', 'Cancelou a venda ID 160. Motivo: cliente desistiu ', NULL, NULL, '2026-01-02 18:37:09'),
(10, '1', 'Cancelou a venda ID 160. Motivo: cliente desistiu ', NULL, NULL, '2026-01-02 18:37:50'),
(11, '1', 'Cancelou a venda ID 160. Motivo: cliente desistiu ', NULL, NULL, '2026-01-02 18:38:17'),
(12, '1', 'Cancelou a venda ID 158. Motivo: vvvvvvvvvvv', NULL, NULL, '2026-01-02 18:40:11'),
(13, '1', 'Cancelou a venda ID 4. Motivo: bnbnbn', NULL, NULL, '2026-01-02 18:40:47'),
(14, '1', 'Cancelou a venda ID 4. Motivo: sem dinheiro', NULL, NULL, '2026-01-02 18:41:18'),
(15, '1', 'Cancelou a venda ID 2. Motivo: sem dinheiro', NULL, NULL, '2026-01-02 18:46:47'),
(16, '1', 'Cancelou a venda ID 164. Motivo: sem transporte, p', NULL, NULL, '2026-01-02 18:49:07'),
(17, '1', 'Cancelou a venda ID 1. Motivo: nao quer mais , e n', NULL, NULL, '2026-01-02 18:57:45'),
(18, '1', 'Cancelou a venda ID 183. Motivo: cheque sem cobert', NULL, NULL, '2026-01-05 13:29:43'),
(19, '1', 'Cancelou a venda ID 187. Motivo: sem dinhero compl', NULL, NULL, '2026-01-12 22:25:04'),
(20, '1', 'Cancelou a venda ID 204. Motivo: sem dinheiro comp', NULL, NULL, '2026-01-14 20:54:00'),
(21, 'Chafim', 'Estornou 1 unidades do material ID 10 da venda 206', 10, 1, '2026-01-31 00:29:43'),
(22, 'Chafim', 'Cancelou a venda ID 206. Motivo: teve disconto com', NULL, NULL, '2026-01-31 00:29:43'),
(23, 'Chafim', 'Estornou 5 unidades do material ID 16 da venda 208', 16, 5, '2026-01-31 00:30:30'),
(24, 'Chafim', 'Estornou 3 unidades do material ID 5 da venda 208', 5, 3, '2026-01-31 00:30:30'),
(25, 'Chafim', 'Cancelou a venda ID 208. Motivo: nao quis mais lev', NULL, NULL, '2026-01-31 00:30:30'),
(26, 'Chafim', 'Registrou venda ID 209 com total R$ 79.780,00 e de', NULL, NULL, '2026-01-31 00:37:00'),
(27, 'Chafim', 'Registrou venda ID 210 com total R$ 27.885,00 e de', NULL, NULL, '2026-01-31 00:42:56'),
(28, 'Chafim', 'Registrou venda ID 211 com total R$ 28.598,00 e de', NULL, NULL, '2026-01-31 00:57:22'),
(29, 'Mario', 'Registrou venda ID 212 com total R$ 150,00 e desco', NULL, NULL, '2026-01-31 01:13:33'),
(30, 'Natalia1', 'Registrou venda ID 213 com total R$ 20.690,00 e de', NULL, NULL, '2026-01-31 01:15:37'),
(31, 'Natalia1', 'Registrou venda ID 214 com total R$ 5.600,00 e des', NULL, NULL, '2026-01-31 01:17:51'),
(32, 'Natalia1', 'Registrou venda ID 215 com total R$ 290,00 e desco', NULL, NULL, '2026-01-31 01:17:59'),
(33, 'Zarah', 'Registrou venda ID 216 com total R$ 24.199,50 e de', NULL, NULL, '2026-01-31 01:20:06'),
(34, 'Chafim', 'Registrou venda ID 217 com total R$ 2.720,00 e des', NULL, NULL, '2026-01-31 13:55:35'),
(35, 'Natalia1', 'Registrou venda ID 218 com total R$ 300,00 e desco', NULL, NULL, '2026-01-31 15:25:37'),
(36, 'Chafim', 'Registrou venda ID 219 com total R$ 599,80 e desco', NULL, NULL, '2026-02-01 13:49:41'),
(37, 'Mario', 'Registrou venda ID 220 com total R$ 3.150,00 e des', NULL, NULL, '2026-02-01 15:02:59'),
(38, 'Chafim', 'Registrou venda ID 221 com total R$ 600,00 e desco', NULL, NULL, '2026-02-02 14:18:37'),
(39, 'Chafim', 'Estornou 4 unidades do material ID 10 da venda 221', 10, 4, '2026-02-02 16:18:13'),
(40, 'Chafim', 'Cancelou a venda ID 221. Motivo: venda sem cobertu', NULL, NULL, '2026-02-02 16:18:13'),
(41, 'Chafim', 'Registrou venda ID 222 com total R$ 450,00 e desco', NULL, NULL, '2026-02-02 16:51:54'),
(42, 'Chafim', 'Estornou 3 unidades do material ID 10 da venda 222', 10, 3, '2026-02-02 16:53:06'),
(43, 'Chafim', 'Cancelou a venda ID 222. Motivo: teste do pdf', NULL, NULL, '2026-02-02 16:53:06'),
(44, 'Chafim', 'Registrou venda ID 223 com total R$ 1.050,00 e des', NULL, NULL, '2026-02-02 17:18:40'),
(45, 'Chafim', 'Registrou venda ID 224 com total R$ 5.500,00 e des', NULL, NULL, '2026-02-02 17:28:44'),
(46, 'Mario', 'Registrou venda ID 225 com total R$ 1.200,00 e des', NULL, NULL, '2026-02-02 17:46:22'),
(47, 'Chafim', 'Registrou venda ID 226 com total R$ 39.250,00 e de', NULL, NULL, '2026-02-02 20:22:45'),
(48, 'Mario', 'Registrou venda ID 227 com total R$ 3.590,00 e des', NULL, NULL, '2026-02-02 20:30:55'),
(49, 'Chafim', 'Registrou venda ID 228 com total R$ 680,00 e desco', NULL, NULL, '2026-03-03 20:52:13'),
(50, 'Chafim', 'Registrou venda ID 229 com total R$ 7.490,00 e des', NULL, NULL, '2026-03-13 13:41:37');

-- --------------------------------------------------------

--
-- Estrutura da tabela `materiais`
--

CREATE TABLE `materiais` (
  `id` int(11) NOT NULL,
  `codigo_barra` varchar(50) DEFAULT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantidade` int(11) NOT NULL,
  `custo_compra` decimal(10,2) NOT NULL,
  `custo_venda` decimal(10,2) NOT NULL,
  `cor` varchar(50) DEFAULT NULL,
  `peso` decimal(10,2) DEFAULT NULL,
  `altura` decimal(10,2) DEFAULT NULL,
  `largura` decimal(10,2) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `materiais`
--

INSERT INTO `materiais` (`id`, `codigo_barra`, `nome`, `quantidade`, `custo_compra`, `custo_venda`, `cor`, `peso`, `altura`, `largura`, `data_cadastro`) VALUES
(1, '7000000000027', 'Cimento Castanho 35', 49988, 450.00, 550.00, NULL, NULL, NULL, NULL, '2025-12-20 04:45:22'),
(2, '7000000000026', 'ferro 11', 90, 150.00, 250.00, NULL, NULL, NULL, NULL, '2025-12-20 04:46:19'),
(3, '7000000000025', 'prego 3', 420, 80.00, 100.00, NULL, NULL, NULL, NULL, '2025-12-20 04:48:50'),
(4, '7000000000024', 'tinta 20L branco', 249, 2000.00, 2800.00, NULL, NULL, NULL, NULL, '2025-12-20 04:49:36'),
(5, '7000000000023', 'cimneto vermelho 500', 298, 500.00, 600.00, NULL, NULL, NULL, NULL, '2025-12-20 04:50:48'),
(6, '7000000000004', 'tubo pvc 110', 755, 750.00, 1100.00, NULL, NULL, NULL, NULL, '2025-12-20 04:51:56'),
(7, '7000000000003', 'cantoneira 80mm', 4992, 400.00, 600.00, NULL, NULL, NULL, NULL, '2025-12-20 04:53:07'),
(8, '7000000000022', 'vaso sanitario chau', 921, 1500.00, 2200.00, NULL, NULL, NULL, NULL, '2025-12-20 05:07:33'),
(9, '7000000000021', 'chapas ibr 10 mm', 1374, 1000.00, 1500.00, NULL, NULL, NULL, NULL, '2025-12-20 05:14:21'),
(10, '7000000000020', 'balde de construcao', 637, 100.00, 150.00, NULL, NULL, NULL, NULL, '2025-12-20 19:55:00'),
(11, '7000000000019', 'culher de pedreiro tamanho medio', 971, 50.00, 75.00, NULL, NULL, NULL, NULL, '2025-12-20 19:55:53'),
(12, '7000000000018', 'culher de pedreiro tamanho pequeno', 991, 30.00, 50.00, NULL, NULL, NULL, NULL, '2025-12-20 19:56:14'),
(13, '7000000000017', 'culher de pedreiro tamanho grande', 990, 160.00, 260.00, NULL, NULL, NULL, NULL, '2025-12-20 19:56:40'),
(14, '7000000000016', 'barrotes de pinho', 475, 800.00, 1200.00, NULL, NULL, NULL, NULL, '2025-12-20 19:57:41'),
(15, '7000000000015', 'farta de pedreiro', 548, 800.00, 1200.00, NULL, NULL, NULL, NULL, '2025-12-20 19:59:08'),
(16, '7000000000014', 'coleto refletor', 146, 200.00, 350.00, NULL, NULL, NULL, NULL, '2025-12-20 20:00:09'),
(17, '7000000000013', 'balde', 5986, 250.00, 450.00, NULL, NULL, NULL, NULL, '2025-12-20 20:07:39'),
(18, '7000000000012', 'cimento Vermelho 50', 149964, 400.00, 600.00, NULL, NULL, NULL, NULL, '2025-12-21 00:04:07'),
(19, '7000000000011', 'torneira de lavatorio quente e fria', 286, 1000.00, 1500.00, NULL, NULL, NULL, NULL, '2025-12-21 03:24:59'),
(20, '7000000000010', 'Martelo', 599, 150.00, 250.00, NULL, NULL, NULL, NULL, '2025-12-21 03:44:26'),
(21, '7000000000009', 'Martelo de carpinteiro', 999, 200.00, 350.00, NULL, NULL, NULL, NULL, '2025-12-21 04:16:09'),
(22, '7000000000008', 'Martelo de Quebra parede', 199, 1500.00, 2500.00, NULL, NULL, NULL, NULL, '2025-12-21 04:32:53'),
(23, '7000000000007', 'nivel curto 1m', 500, 100.00, 150.00, NULL, NULL, NULL, NULL, '2025-12-21 04:43:54'),
(24, '7000000000006', 'Lona para camiao', 1199, 1000.00, 1500.00, NULL, NULL, NULL, NULL, '2025-12-22 19:55:53'),
(25, '7000000000005', 'Lona para camioneta', 49999, 900.00, 1400.00, NULL, NULL, NULL, NULL, '2025-12-22 20:52:40'),
(26, '7000000000000', 'andaime completo 4 p', 100, 800.00, 1600.00, NULL, NULL, NULL, NULL, '2025-12-27 01:58:24'),
(27, '7000000000001', 'andaime completo 8 p', 990, 1200.00, 2400.00, NULL, NULL, NULL, NULL, '2025-12-27 02:50:29'),
(28, '7000000000002', 'andaime completo 16 pm', 1000, 1600.00, 2800.00, NULL, NULL, NULL, NULL, '2025-12-27 03:01:47'),
(29, '7000000000029', 'ventilador portatil', 199, 400.00, 550.00, NULL, NULL, NULL, NULL, '2025-12-27 22:03:10'),
(30, '12345678911', 'pano de chau branca', 1167, 15.00, 25.00, NULL, NULL, NULL, NULL, '2025-12-28 19:51:49'),
(31, '9113048000000', 'vassoura', 99, 4.50, 8.00, NULL, NULL, NULL, NULL, '2025-12-28 19:55:22'),
(32, '9113048000026', 'Ancinho', 1000, 100.00, 150.00, NULL, NULL, NULL, NULL, '2025-12-28 23:33:15'),
(33, '9113048000027', 'Ancinho de ferro', 99, 140.00, 220.00, NULL, NULL, NULL, NULL, '2025-12-28 23:36:28'),
(34, '9113048000028', 'Ancinho de plastico', 1000, 80.00, 140.00, NULL, NULL, NULL, NULL, '2025-12-28 23:37:22'),
(35, '9113048000029', 'Ancinho de plástico acrílico', 45, 90.00, 160.00, NULL, NULL, NULL, NULL, '2025-12-28 23:46:47'),
(39, '9113048000030', 'Ancinho de plástico acrílico branco', 1200, 90.00, 160.00, NULL, NULL, NULL, NULL, '2025-12-28 23:49:17'),
(40, '9113048000031', 'chave de rouda 17', 91, 200.00, 350.00, NULL, NULL, NULL, NULL, '2025-12-28 23:50:11'),
(41, '9113048000032', 'chave de rouda 32', 200, 200.00, 350.00, NULL, NULL, NULL, NULL, '2025-12-29 00:14:22'),
(43, '9113048000034', 'pa de ferro', 1200, 200.00, 350.00, 'cinza', 1.00, 1.20, 0.22, '2025-12-30 03:34:04'),
(44, '9113048000035', 'pa de ferro de 16', 4000, 200.00, 350.00, 'cinza', 1.00, 1.20, 0.22, '2025-12-31 04:45:45'),
(45, '9113048000036', 'prego de 5', 1502, 75.00, 100.00, NULL, NULL, NULL, NULL, '2026-01-05 16:43:40'),
(46, '7000000000037', 'tinta 5L', 3000, 300.00, 500.00, 'Preta', 10.00, 20.00, 0.12, '2026-01-10 21:55:19'),
(47, '7000000000034', 'prego de 1', 1997, 65.00, 100.00, 'cinza', 0.01, 0.03, 1.00, '2026-01-14 23:20:53'),
(48, '7000000000047', 'Regua de pedreiro', 1500, 75.00, 125.00, 'amarelo', 0.40, 1.20, 10.20, '2026-02-02 19:05:13'),
(49, '7000000000077', 'pc', 45000, 5000.00, 7000.00, 'Preta', 2.00, 0.22, 0.53, '2026-03-03 23:58:12');

-- --------------------------------------------------------

--
-- Estrutura da tabela `movimentacao_estoque`
--

CREATE TABLE `movimentacao_estoque` (
  `id` int(11) NOT NULL,
  `material_id` int(11) DEFAULT NULL,
  `estoque_anterior` int(11) DEFAULT NULL,
  `quantidade_adicionada` int(11) DEFAULT NULL,
  `estoque_final` int(11) DEFAULT NULL,
  `data_movimentacao` datetime DEFAULT current_timestamp(),
  `tipo_movimento` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pagamentos_divida`
--

CREATE TABLE `pagamentos_divida` (
  `id_pagamento` int(11) NOT NULL,
  `id_devedor` int(11) DEFAULT NULL,
  `valor_pago` decimal(10,2) DEFAULT NULL,
  `data_pagamento` date DEFAULT NULL,
  `status_pagamento` enum('PARCIAL','TOTAL') NOT NULL DEFAULT 'PARCIAL',
  `descricao_pagamento` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `pagamentos_divida`
--

INSERT INTO `pagamentos_divida` (`id_pagamento`, `id_devedor`, `valor_pago`, `data_pagamento`, `status_pagamento`, `descricao_pagamento`) VALUES
(5, 1, 10000.00, '2026-03-13', 'PARCIAL', NULL),
(6, 1, 15000.00, '2026-03-13', 'PARCIAL', NULL),
(9, 1, 1000.00, '2026-03-14', 'PARCIAL', NULL),
(10, 1, 10000.00, '2026-03-14', 'PARCIAL', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel` varchar(20) NOT NULL DEFAULT 'user',
  `tentativas` int(11) DEFAULT 0,
  `bloqueado_ate` datetime DEFAULT NULL,
  `nivel_bloqueio` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `senha`, `nivel`, `tentativas`, `bloqueado_ate`, `nivel_bloqueio`) VALUES
(1, 'Chafim', '$2y$10$vS2XNCGWiMwF8qSOgqDxgOqAPCIhNvPufhVsTu.krWaImktaG/c2e', 'admin', 0, NULL, 0),
(2, 'Mario', '$2y$10$viwWzS6wEhj5qq3ZD.iqse6Qxjbo4slbBl0Gn85rBqw8pGfbV9lRi', 'vendedor', 0, NULL, 0),
(8, 'Natalia1', '$2y$10$ktGarcCeMe/kmjZYX.MXSudKAxJ7OmU4AnSEEWOBr5WgB50nJ8cGO', 'vendedor', 0, NULL, 0),
(10, 'Zarah', '$2y$10$lAdEM3TfbC9QdLXP6hbyJegX./fXS4nkNpv4PStJN/yFbGfwg3fU6', 'admin', 0, NULL, 0),
(11, 'Martina', '$2y$10$KXkk/RgsSRHGezgCllsxkuCzMseLneCjC7lPUAH72ItcQHpQuY/Ay', 'vendedor', 0, NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `vendas`
--

CREATE TABLE `vendas` (
  `id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('concluida','cancelada') DEFAULT 'concluida',
  `cliente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `data_venda` datetime NOT NULL DEFAULT current_timestamp(),
  `forma_pagamento` varchar(20) NOT NULL DEFAULT 'dinheiro',
  `desconto` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cancelado_em` datetime DEFAULT NULL,
  `cancelado_por` int(11) DEFAULT NULL,
  `motivo_cancelamento` varchar(255) DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `vendas`
--

INSERT INTO `vendas` (`id`, `total`, `status`, `cliente`, `usuario_id`, `data_venda`, `forma_pagamento`, `desconto`, `cancelado_em`, `cancelado_por`, `motivo_cancelamento`, `pdf_path`) VALUES
(1, 9850.00, 'cancelada', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, '2026-01-02 18:57:45', 1, 'nao quer mais , e nao aceito a troca', NULL),
(2, 9850.00, 'cancelada', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, '2026-01-02 18:46:46', 1, 'sem dinheiro', NULL),
(3, 10450.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(4, 77400.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(5, 23400.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(6, 24750.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(7, 7250.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(8, 600000.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(9, 16000.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(10, 3000.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(11, 11050.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(12, 4000.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(13, 4700.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(14, 2700.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(15, 11100.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(16, 21300.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(17, 11000.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(18, 55000.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(19, 3900.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(20, 11200.00, 'cancelada', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(21, 0.00, '', 'Chafim Construções', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(22, 0.00, '', 'Chafim Construções', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(23, 0.00, '', 'Chafim Construções', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(24, 0.00, '', 'Chafim Construções', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(25, 0.00, '', 'Manjate Contruções', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(26, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(27, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(28, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(29, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(30, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(31, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(32, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(33, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(34, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(35, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(36, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(37, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(38, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(39, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(40, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(41, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(42, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(43, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(44, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(45, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(46, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(47, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(48, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(49, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(50, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(51, 2250.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(52, 2250.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(53, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(54, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(55, 0.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(56, 3900.00, 'concluida', '', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(57, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(58, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(59, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(60, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(61, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(62, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(63, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(64, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(65, 0.00, '', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(66, 1050.00, '', 'Contrucao Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(67, 6100.00, '', 'samuel construcoes', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(68, 3000.00, 'concluida', 'samuel construcoes', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(69, 2000.00, 'concluida', 'Construcoes Tropicais', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(70, 467500.00, 'concluida', 'Construcoes Tropicais', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(71, 59400.00, 'concluida', 'Construcoes Tropicais', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(72, 5500.00, 'concluida', 'Construcoes Tropicais', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(73, 1800.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(74, 1800.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(75, 600.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(76, 600.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(77, 600.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(78, 600.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(79, 600.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(80, 19400.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(81, 20500.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(82, 1100.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(83, 12600.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(84, 75000.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(85, 15750.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(86, 1500.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(87, 150.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(88, 300.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(89, 150.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(90, 150.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(91, 150.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(92, 150.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(93, 150.00, 'concluida', 'Contrução Pemba', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(94, 8100.00, 'concluida', 'Natalia construções', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(95, 16800.00, 'concluida', 'S/N', NULL, '2025-12-26 23:48:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(96, 2750.00, 'concluida', 'Chafim Construções', 1, '2025-12-26 23:48:55', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(97, 750.00, 'concluida', 'Contrução Pemba', 1, '2025-12-27 00:02:43', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(98, 1500.00, 'concluida', 'Contrução Pemba', 1, '2025-12-27 00:21:35', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(99, 1500.00, 'concluida', 'samuel construcoes', 1, '2025-12-27 00:41:25', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(100, 4800.00, 'concluida', 'samuel construcoes', 1, '2025-12-27 00:49:54', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(101, 1500.00, 'concluida', 'Chafim Construções', 1, '2025-12-27 01:26:21', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(102, 2800.00, 'concluida', 'Contrução Pemba', 2, '2025-12-27 01:30:16', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(103, 308000.00, 'concluida', 'Contrução Pemba', 2, '2025-12-27 01:31:10', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(104, 2400.00, 'concluida', 'samuel construcoes', 1, '2025-12-27 01:51:25', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(105, 150.00, 'concluida', 'samuel construcoes', 1, '2025-12-27 01:52:14', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(106, 105.00, 'concluida', 'samuel construcoes', 1, '2025-12-27 01:57:17', 'dinheiro', 45.00, NULL, NULL, NULL, NULL),
(107, 210.00, 'concluida', 'samuel construcoes', 1, '2025-12-27 02:16:09', 'pix', 90.00, NULL, NULL, NULL, NULL),
(108, 250.00, 'concluida', 'samuel construcoes', 1, '2025-12-27 02:17:35', 'pix', 50.00, NULL, NULL, NULL, NULL),
(109, 250.00, 'concluida', 'samuel construcoes', 1, '2025-12-27 02:22:06', 'dinheiro', 50.00, NULL, NULL, NULL, NULL),
(110, 3050.00, 'concluida', 'samuel construcoes', 1, '2025-12-27 02:27:12', 'pix', 50.00, NULL, NULL, NULL, NULL),
(111, 2770.00, 'concluida', 'samuel construcoes', 2, '2025-12-27 02:30:37', 'dinheiro', 30.00, NULL, NULL, NULL, NULL),
(112, 5950.00, 'concluida', 'samuel construcoes', 2, '2025-12-27 02:34:01', 'cartao', 50.00, NULL, NULL, NULL, NULL),
(113, 6450.00, 'concluida', 'samuel construcoes', 2, '2025-12-27 02:36:07', 'cartao', 50.00, NULL, NULL, NULL, NULL),
(114, 6450.00, 'concluida', 'samuel construcoes', 2, '2025-12-27 02:40:23', 'cartao', 50.00, NULL, NULL, NULL, NULL),
(115, 150.00, 'concluida', 'samuel construcoes', 2, '2025-12-27 03:18:46', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(116, 150.00, 'concluida', 'samuel construcoes', 2, '2025-12-27 03:19:48', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(117, 140.00, 'concluida', 'samuel construcoes', 2, '2025-12-27 03:20:45', 'dinheiro', 10.00, NULL, NULL, NULL, NULL),
(118, 6650.00, 'concluida', 'Natalia construções', 10, '2025-12-28 17:07:02', 'dinheiro', 100.00, NULL, NULL, NULL, NULL),
(119, 135.00, 'concluida', 'Natalia construções', 1, '2025-12-28 21:23:39', 'pix', 15.00, NULL, NULL, NULL, NULL),
(120, 150.00, 'concluida', 'Natalia construções', 2, '2025-12-28 21:46:46', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(121, 1200.00, 'concluida', 'Natalia construções', 10, '2025-12-29 18:20:01', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(122, 4400.00, 'concluida', 'samuel construcoes', 1, '2025-12-29 18:50:47', 'cartao', 0.00, NULL, NULL, NULL, NULL),
(123, 11950.00, 'concluida', 'samuel construcoes', 1, '2025-12-29 22:39:54', 'cartao', 50.00, NULL, NULL, NULL, NULL),
(124, 27360.00, 'concluida', 'samuel construcoes', 1, '2025-12-30 19:42:39', 'dinheiro', 10.00, NULL, NULL, NULL, NULL),
(125, 135.00, 'concluida', 'samuel construcoes', 1, '2025-12-30 19:54:24', 'dinheiro', 10.00, NULL, NULL, NULL, NULL),
(126, 280.00, 'concluida', 'samuel construcoes', 1, '2025-12-30 20:05:22', 'dinheiro', 20.00, NULL, NULL, NULL, NULL),
(127, 2800.00, 'concluida', 'Contrução Pemba', 1, '2025-12-30 20:06:28', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(128, 140.00, 'concluida', 'Contrução Pemba', 1, '2025-12-30 20:28:12', 'dinheiro', 10.00, NULL, NULL, NULL, NULL),
(129, 140.00, 'concluida', 'Contrução Pemba', 1, '2025-12-30 20:35:33', 'dinheiro', 10.00, NULL, NULL, NULL, NULL),
(130, 2790.00, 'concluida', 'Contrução Pemba', 1, '2025-12-30 20:43:03', 'dinheiro', 10.00, NULL, NULL, NULL, NULL),
(131, 3150.00, 'concluida', 'Contrução Pemba', 1, '2025-12-30 20:56:02', 'pix', 0.00, NULL, NULL, NULL, NULL),
(132, 150.00, 'concluida', 'Contrução Pemba', 1, '2025-12-30 21:21:34', 'pix', 0.00, NULL, NULL, NULL, NULL),
(133, 2800.00, 'concluida', 'Construcoes Tropicais', 10, '2025-12-31 00:33:40', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(134, 2800.00, 'concluida', 'Construcoes Tropicais', 10, '2025-12-31 00:34:45', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(135, 2800.00, 'concluida', 'Construcoes Tropicais', 10, '2025-12-31 00:38:22', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(136, 2800.00, 'concluida', 'Construcoes Tropicais', 10, '2025-12-31 01:00:48', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(137, 2800.00, 'concluida', 'Construcoes Tropicais', 10, '2025-12-31 01:02:53', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(138, 2800.00, 'concluida', 'Construcoes Tropicais', 10, '2025-12-31 01:03:42', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(139, 2800.00, 'concluida', 'Construcoes Tropicais', 10, '2025-12-31 01:05:04', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(140, 2800.00, 'concluida', 'Construcoes Tropicais', 10, '2025-12-31 01:05:50', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(141, 8650.00, 'concluida', 'Construcoes Tropicais', 10, '2025-12-31 01:10:16', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(142, 12500.00, 'concluida', 'Construcoes Tropicais', 10, '2025-12-31 01:13:41', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(143, 5500.00, 'concluida', 'Construcoes Tropicais', 10, '2025-12-31 01:31:53', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(144, 795.00, 'concluida', 'Construcoes Tropicais', 10, '2025-12-31 01:37:28', 'pix', 5.00, NULL, NULL, NULL, NULL),
(145, 1200.00, 'concluida', 'Chafim Construções', 1, '2025-12-31 02:09:58', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(146, 8800.00, 'concluida', 'Chafim Construções', 1, '2025-12-31 02:12:31', 'dinheiro', 10.00, NULL, NULL, NULL, NULL),
(147, 2800.00, 'concluida', 'Chafim Construções', 1, '2025-12-31 02:19:04', 'dinheiro', 30.00, NULL, NULL, NULL, NULL),
(148, 2800.00, 'concluida', 'Chafim Construções', 1, '2025-12-31 02:24:04', 'dinheiro', 3.00, NULL, NULL, NULL, NULL),
(149, 2800.00, 'concluida', 'Chafim Construções', 1, '2025-12-31 02:29:59', 'dinheiro', 3.00, NULL, NULL, NULL, NULL),
(150, 1500.00, 'concluida', 'Chafim Construções', 1, '2025-12-31 02:30:28', 'dinheiro', 3.00, NULL, NULL, NULL, NULL),
(151, 7600.00, 'concluida', 'Chafim Construções', 1, '2025-12-31 02:45:54', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(152, 550.00, 'cancelada', 'Chafim Construções', 1, '2025-12-31 02:53:39', 'dinheiro', 0.00, '2026-01-02 17:36:06', NULL, 'cliente reclamou o saco e a qualidade', NULL),
(153, 550.00, 'concluida', 'Chafim Construções', 1, '2025-12-31 02:55:03', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(154, 550.00, 'concluida', 'Chafim Construções', 1, '2025-12-31 03:13:33', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(155, 250.00, 'cancelada', 'Chafim Construções', 1, '2025-12-31 03:14:32', 'dinheiro', 0.00, '2026-01-02 17:35:09', NULL, 'cliente reclamou a qualidade, pediu cancelar', NULL),
(156, 2800.00, 'concluida', 'Chafim Construções', 1, '2025-12-31 03:16:34', 'pix', 0.00, NULL, NULL, NULL, NULL),
(157, 350.00, 'cancelada', 'Contrução Pemba', 10, '2025-12-31 04:10:18', 'dinheiro', 0.00, '2026-01-02 17:44:14', NULL, 'cliente sem total a pagar', NULL),
(158, 3300.00, 'concluida', 'Contrução Pemba', 1, '2025-12-31 04:28:51', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(159, 3300.00, 'concluida', 'Contrução Pemba', 1, '2025-12-31 04:29:58', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(160, 150.00, 'concluida', 'Contrução Pemba', 1, '2025-12-31 05:02:08', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(161, 348.00, 'concluida', 'Contrução Pemba', 1, '2025-12-31 05:05:30', 'pix', 2.00, NULL, NULL, NULL, NULL),
(162, 296.00, 'cancelada', 'Contrução Pemba', 1, '2025-12-31 05:06:27', 'pix', 4.00, '2026-01-02 17:57:05', NULL, 'cheque falso', NULL),
(163, 146.00, 'cancelada', 'Contrução Pemba', 1, '2025-12-31 05:09:33', 'pix', 4.00, '2026-01-02 17:34:00', NULL, 'sem saldo na conta', NULL),
(164, 27300.00, 'cancelada', 'Contrução Pemba', 1, '2026-01-02 14:36:27', 'dinheiro', 0.00, '2026-01-02 18:49:07', 1, 'sem transporte, pediu que fosse a loja, porem, estamos sem transp', NULL),
(165, 6600.00, 'cancelada', 'samuel construcoes', 1, '2026-01-02 16:01:47', 'transf_bancaria', 0.00, '2026-01-02 17:32:13', NULL, 'cliente nao pagou, cheque sem cobertura', NULL),
(166, 698.00, 'concluida', 'samuel construcoes', 1, '2026-01-02 19:30:11', 'transf_bancaria', 2.00, NULL, NULL, NULL, NULL),
(167, 2250.00, 'concluida', 'Arco-Iris Pemba', 1, '2026-01-03 18:01:41', 'emola', 0.00, NULL, NULL, NULL, NULL),
(168, 150.00, 'concluida', 'Arco-Iris Pemba', 1, '2026-01-03 18:29:46', 'pix', 0.00, NULL, NULL, NULL, NULL),
(169, 7600.00, 'concluida', 'Arco-Iris Pemba', 2, '2026-01-03 21:02:01', 'cheque', 0.00, NULL, NULL, NULL, NULL),
(170, 300.00, 'concluida', 'Arco-Iris Pemba', 10, '2026-01-04 19:40:55', 'pix', 0.00, NULL, NULL, NULL, NULL),
(171, 9100.00, 'concluida', 'Arco-Iris Pemba', 10, '2026-01-04 19:42:19', 'pix', 0.00, NULL, NULL, NULL, NULL),
(172, 5450.00, 'concluida', 'Arco-Iris Pemba', 1, '2026-01-04 20:03:09', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(173, 300.00, 'concluida', 'Arco-Iris Pemba', 8, '2026-01-04 20:07:31', 'pix', 0.00, NULL, NULL, NULL, NULL),
(174, 11500.00, 'concluida', 'Arco-Iris Pemba', 8, '2026-01-04 20:08:21', 'pix', 0.00, NULL, NULL, NULL, NULL),
(175, 11500.00, 'concluida', 'Arco-Iris Pemba', 8, '2026-01-04 20:10:33', 'pix', 0.00, NULL, NULL, NULL, NULL),
(176, 4650.00, 'concluida', 'Contrução Pemba', 1, '2026-01-05 12:15:45', 'mpesa', 0.00, NULL, NULL, NULL, NULL),
(177, 159840.00, 'concluida', 's/n', 1, '2026-01-05 12:32:17', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(178, 16160.00, 'concluida', 'Contrução Pemba', 1, '2026-01-05 12:36:14', 'cartao', 0.00, NULL, NULL, NULL, NULL),
(179, 16320.00, 'concluida', 'Chafim Construções', 1, '2026-01-05 12:40:55', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(180, 1600.00, 'concluida', 's/n', 1, '2026-01-05 12:43:01', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(181, 2800.00, 'concluida', 'Contrução Pemba', 1, '2026-01-05 12:49:18', 'cartao', 0.00, NULL, NULL, NULL, NULL),
(182, 19250.00, 'concluida', 'Contrução Pemba', 1, '2026-01-05 12:56:04', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(183, 9250.00, 'cancelada', 'Contrução Pemba', 1, '2026-01-05 13:27:07', 'mpesa', 0.00, '2026-01-05 13:29:43', 1, 'cheque sem cobertura', NULL),
(184, 6080.00, 'concluida', 'Construcoes Tropicais', 8, '2026-01-10 22:02:38', 'pix', 20.00, NULL, NULL, NULL, NULL),
(185, 13530.00, 'concluida', 'Construcoes Tropicais', 8, '2026-01-10 22:04:24', 'pix', 20.00, NULL, NULL, NULL, NULL),
(186, 13530.00, 'concluida', 'Construcoes Tropicais', 8, '2026-01-10 22:14:21', 'pix', 20.00, NULL, NULL, NULL, NULL),
(187, 11950.00, 'cancelada', 'Almeida construções', 1, '2026-01-12 22:22:41', 'dinheiro', 0.00, '2026-01-12 22:25:04', 1, 'sem dinhero completo', NULL),
(188, 300.00, 'concluida', 'Almeida construções', 1, '2026-01-14 12:25:12', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(189, 2800.00, 'concluida', 'Almeida construções', 1, '2026-01-14 12:32:31', 'pix', 0.00, NULL, NULL, NULL, NULL),
(190, 150.00, 'concluida', 'Almeida construções', 1, '2026-01-14 13:56:45', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(191, 2800.00, 'concluida', 'Almeida construções', 1, '2026-01-14 14:15:46', 'pix', 0.00, NULL, NULL, NULL, NULL),
(192, 1400.00, 'concluida', 'Contrução Pemba', 1, '2026-01-14 14:18:05', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(193, 2800.00, 'concluida', 'Contrução Pemba', 1, '2026-01-14 14:22:37', 'cartao', 0.00, NULL, NULL, NULL, NULL),
(194, 2200.00, 'concluida', 'Natalia ABC', 1, '2026-01-14 18:22:09', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(195, 28550.00, 'concluida', 'Natalia ABC', 1, '2026-01-14 18:59:28', 'cartao', 100.00, NULL, NULL, NULL, NULL),
(196, 1000.00, 'concluida', 'Natalia ABC', 1, '2026-01-14 19:09:29', 'cartao', 0.00, NULL, NULL, NULL, NULL),
(197, 2950.00, 'concluida', 'samuel construcoes', 1, '2026-01-14 19:14:49', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(198, 300.00, 'concluida', 'Contrução Pemba', 8, '2026-01-14 19:46:45', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(199, 150.00, 'concluida', 'Contrução Pemba', 8, '2026-01-14 19:52:54', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(200, 300.00, 'concluida', 'Construcoes Tropicais', 1, '2026-01-14 20:23:08', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(201, 150.00, 'concluida', 'Construcoes Tropicais', 1, '2026-01-14 20:35:14', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(202, 2800.00, 'concluida', 'Construcoes Tropicais', 1, '2026-01-14 20:37:26', 'cartao', 0.00, NULL, NULL, NULL, NULL),
(203, 150.00, 'concluida', 'Chafim Construções', 1, '2026-01-14 20:40:56', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(204, 440.00, 'cancelada', 'Chafim Construções', 1, '2026-01-14 20:48:53', 'dinheiro', 10.00, '2026-01-14 20:54:00', 1, 'sem dinheiro completo', NULL),
(205, 150.00, 'concluida', 'Contrução Pemba', 1, '2026-01-16 17:58:08', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(206, 140.00, 'cancelada', 'Contrução Pemba', 1, '2026-01-30 22:07:18', 'dinheiro', 10.00, '2026-01-31 00:29:43', 1, 'teve disconto com erro no recibo', NULL),
(207, 3950.00, 'concluida', 'Chafim Construções', 1, '2026-01-30 22:54:18', 'cartao', 0.00, NULL, NULL, NULL, NULL),
(208, 3538.00, 'cancelada', 'Construcoes Tropicais', 2, '2026-01-30 23:01:11', 'mpesa', 12.00, '2026-01-31 00:30:30', 1, 'nao quis mais levar o material', NULL),
(209, 79780.00, 'concluida', 'Chafim Construções', 1, '2026-01-31 00:37:00', 'dinheiro', 20.00, NULL, NULL, NULL, NULL),
(210, 27885.00, 'concluida', 'Arco-Iris Pemba', 1, '2026-01-31 00:42:56', 'cartao', 15.00, NULL, NULL, NULL, NULL),
(211, 28598.00, 'concluida', 'samuel construcoes', 1, '2026-01-31 00:57:22', 'dinheiro', 2.00, NULL, NULL, NULL, NULL),
(212, 150.00, 'concluida', 'Construcoes Tropicais', 2, '2026-01-31 01:13:33', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(213, 20690.00, 'concluida', 'Chafim Construções', 8, '2026-01-31 01:15:37', 'dinheiro', 10.00, NULL, NULL, NULL, NULL),
(214, 5600.00, 'concluida', 'Contrução Pemba', 8, '2026-01-31 01:17:51', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(215, 290.00, 'concluida', 'Chafim Construções', 8, '2026-01-31 01:17:59', 'dinheiro', 10.00, NULL, NULL, NULL, NULL),
(216, 24199.50, 'concluida', 'Contrução Pemba', 10, '2026-01-31 01:20:06', 'dinheiro', 0.50, NULL, NULL, NULL, NULL),
(217, 2720.00, 'concluida', 'Arco-Iris Pemba', 1, '2026-01-31 13:55:35', 'emola', 80.00, NULL, NULL, NULL, NULL),
(218, 300.00, 'concluida', 'Contrução Pemba', 8, '2026-01-31 15:25:37', 'cartao', 0.00, NULL, NULL, NULL, NULL),
(219, 599.80, 'concluida', 'Arco-Iris Pemba', 1, '2026-02-01 13:49:41', 'dinheiro', 0.20, NULL, NULL, NULL, NULL),
(220, 3150.00, 'concluida', 'samuel construcoes', 2, '2026-02-01 15:02:59', 'emola', 0.00, NULL, NULL, NULL, NULL),
(221, 600.00, 'cancelada', 'Arco-Iris Pemba', 1, '2026-02-02 14:18:37', 'dinheiro', 0.00, '2026-02-02 16:18:13', 1, 'venda sem cobertura', NULL),
(222, 450.00, 'cancelada', 'Arco-Iris Pemba', 1, '2026-02-02 16:51:54', 'dinheiro', 0.00, '2026-02-02 16:53:06', 1, 'teste do pdf', NULL),
(223, 1050.00, 'concluida', 'samuel construcoes', 1, '2026-02-02 17:18:40', 'cheque', 0.00, NULL, NULL, NULL, NULL),
(224, 5500.00, 'concluida', 'samuel construcoes', 1, '2026-02-02 17:28:44', 'dinheiro', 100.00, NULL, NULL, NULL, NULL),
(225, 1200.00, 'concluida', 'Chafim Construções', 2, '2026-02-02 17:46:22', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(226, 39250.00, 'concluida', 'Contrução Pemba', 1, '2026-02-02 20:22:45', 'dinheiro', 0.00, NULL, NULL, NULL, NULL),
(227, 3590.00, 'concluida', 'samuel construcoes', 2, '2026-02-02 20:30:55', 'cheque', 10.00, NULL, NULL, NULL, NULL),
(228, 680.00, 'concluida', 'Contrução Pemba', 1, '2026-03-03 20:52:13', 'pix', 20.00, NULL, NULL, NULL, NULL),
(229, 7490.00, 'concluida', 'Lino', 1, '2026-03-13 13:41:37', 'mpesa', 10.00, NULL, NULL, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `caixa`
--
ALTER TABLE `caixa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario_status` (`usuario_id`,`status`);

--
-- Índices para tabela `devedores`
--
ALTER TABLE `devedores`
  ADD PRIMARY KEY (`id_devedor`);

--
-- Índices para tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`id_fornecedor`);

--
-- Índices para tabela `itens_venda`
--
ALTER TABLE `itens_venda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venda_id` (`venda_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Índices para tabela `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `materiais`
--
ALTER TABLE `materiais`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_barra` (`codigo_barra`);

--
-- Índices para tabela `movimentacao_estoque`
--
ALTER TABLE `movimentacao_estoque`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_id` (`material_id`);

--
-- Índices para tabela `pagamentos_divida`
--
ALTER TABLE `pagamentos_divida`
  ADD PRIMARY KEY (`id_pagamento`),
  ADD KEY `pagamentos_divida_ibfk_1` (`id_devedor`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Índices para tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vendas_cancelado_por` (`cancelado_por`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `caixa`
--
ALTER TABLE `caixa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de tabela `devedores`
--
ALTER TABLE `devedores`
  MODIFY `id_devedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `id_fornecedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `itens_venda`
--
ALTER TABLE `itens_venda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=403;

--
-- AUTO_INCREMENT de tabela `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de tabela `materiais`
--
ALTER TABLE `materiais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de tabela `movimentacao_estoque`
--
ALTER TABLE `movimentacao_estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pagamentos_divida`
--
ALTER TABLE `pagamentos_divida`
  MODIFY `id_pagamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `itens_venda`
--
ALTER TABLE `itens_venda`
  ADD CONSTRAINT `itens_venda_ibfk_1` FOREIGN KEY (`venda_id`) REFERENCES `vendas` (`id`),
  ADD CONSTRAINT `itens_venda_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materiais` (`id`);

--
-- Limitadores para a tabela `movimentacao_estoque`
--
ALTER TABLE `movimentacao_estoque`
  ADD CONSTRAINT `movimentacao_estoque_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `materiais` (`id`);

--
-- Limitadores para a tabela `pagamentos_divida`
--
ALTER TABLE `pagamentos_divida`
  ADD CONSTRAINT `pagamentos_divida_ibfk_1` FOREIGN KEY (`id_devedor`) REFERENCES `devedores` (`id_devedor`);

--
-- Limitadores para a tabela `vendas`
--
ALTER TABLE `vendas`
  ADD CONSTRAINT `fk_vendas_cancelado_por` FOREIGN KEY (`cancelado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;
--
-- Banco de dados: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Extraindo dados da tabela `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2026-01-28 00:31:22', '{\"Console\\/Mode\":\"collapse\",\"lang\":\"pt\"}');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Índices para tabela `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Índices para tabela `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Índices para tabela `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Índices para tabela `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Índices para tabela `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Índices para tabela `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Índices para tabela `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Índices para tabela `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Índices para tabela `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Índices para tabela `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Índices para tabela `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Índices para tabela `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Índices para tabela `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Índices para tabela `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Índices para tabela `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Índices para tabela `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Índices para tabela `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Banco de dados: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
