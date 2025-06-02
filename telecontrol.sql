-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 02-Jun-2025 às 20:16
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
-- Banco de dados: `telecontrol`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `clients`
--

INSERT INTO `clients` (`id`, `name`, `cpf`, `address`) VALUES
(2, 'Renato Nunes Pineda', '404.712.788-40', 'Av Maria Fernandes Cavalari, 3190 casa 84');

-- --------------------------------------------------------

--
-- Estrutura da tabela `logs`
--

CREATE TABLE `logs` (
  `message` varchar(1000) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `logs`
--

INSERT INTO `logs` (`message`, `created_at`) VALUES
('Ordem de serviço alterada:{\"consumer_cpf\":\"404.712.788-40\",\"consumer_name\":\"Renato Nunes Pineda1\",\"product_id\":5}', '2025-06-02 06:08:18'),
('Ordem de serviço 4 alterada:{\"consumer_cpf\":\"404.712.788-40\",\"consumer_name\":\"Renato Nunes Pineda\",\"product_id\":5}', '2025-06-02 06:09:59'),
('Ordem de serviço id:4 alterada:{\"consumer_name\":\"Renato Nunes Pineda teste\",\"consumer_cpf\":\"404.712.788-40\",\"open_date\":\"2025-06-02\",\"product_id\":\"5\"}', '2025-06-02 12:41:07'),
('Ordem de serviço id:6 alterada:{\"consumer_name\":\"Generic H2 (Em\",\"consumer_cpf\":\"19450268800\",\"open_date\":\"2025-06-02\",\"product_id\":\"5\"}', '2025-06-02 12:45:28'),
('Ordem de serviço id:4 alterada:{\"consumer_name\":\"Renato Nunes Pineda\",\"consumer_cpf\":\"404.712.788-40\",\"open_date\":\"2025-06-02\",\"product_id\":\"5\"}', '2025-06-02 13:17:09');

-- --------------------------------------------------------

--
-- Estrutura da tabela `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` varchar(11) DEFAULT NULL,
  `warranty_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `products`
--

INSERT INTO `products` (`id`, `description`, `status`, `warranty_time`) VALUES
(5, 'TV1', 'active', 6),
(6, 'MICROWAVE', 'active', 6);

-- --------------------------------------------------------

--
-- Estrutura da tabela `service_order`
--

CREATE TABLE `service_order` (
  `id` int(11) NOT NULL,
  `open_date` date NOT NULL DEFAULT current_timestamp(),
  `consumer_name` varchar(255) NOT NULL,
  `consumer_cpf` varchar(14) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `service_order`
--

INSERT INTO `service_order` (`id`, `open_date`, `consumer_name`, `consumer_cpf`, `product_id`) VALUES
(4, '2025-06-02', 'Renato Nunes Pineda', '404.712.788-40', 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `papel` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `papel`) VALUES
(1, 'renato92.nunes@gmail.com', 'teste', 'admin');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`) USING BTREE;

--
-- Índices para tabela `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `service_order`
--
ALTER TABLE `service_order`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `service_order`
--
ALTER TABLE `service_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
