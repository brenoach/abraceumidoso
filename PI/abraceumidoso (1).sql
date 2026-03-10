-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10/03/2026 às 13:35
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `abraceumidoso`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamento`
--

CREATE TABLE `agendamento` (
  `idAgendamento` int(11) NOT NULL,
  `dtAgendamento` date NOT NULL,
  `hrAgendamento` time NOT NULL,
  `voluntario_idVoluntario` int(11) NOT NULL,
  `idoso_idIdoso` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `agendamento`
--

INSERT INTO `agendamento` (`idAgendamento`, `dtAgendamento`, `hrAgendamento`, `voluntario_idVoluntario`, `idoso_idIdoso`, `status`) VALUES
(1, '2026-03-18', '11:22:00', 2, 1, 'Cancelado'),
(2, '2026-03-20', '12:05:00', 2, 1, 'Cancelado');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cartas`
--

CREATE TABLE `cartas` (
  `idCartas` int(11) NOT NULL,
  `textoCarta` text NOT NULL,
  `data` datetime NOT NULL,
  `statusCarta` char(10) DEFAULT 'Pendente',
  `voluntario_idVoluntario` int(11) NOT NULL,
  `idoso_idIdoso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contatos`
--

CREATE TABLE `contatos` (
  `idcontatos` int(11) NOT NULL,
  `email` varchar(45) NOT NULL,
  `celular` char(11) DEFAULT NULL,
  `telefone` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `contatos`
--

INSERT INTO `contatos` (`idcontatos`, `email`, `celular`, `telefone`) VALUES
(4, 'brenoach@gmail.com', '19999999999', NULL),
(5, 'contato@laresperanca.com', NULL, NULL),
(6, 'funcionario@teste.com', NULL, NULL),
(7, 'voluntario@teste.com', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `endereco`
--

CREATE TABLE `endereco` (
  `idEndereco` int(11) NOT NULL,
  `cep` char(8) NOT NULL,
  `estado` char(2) NOT NULL,
  `cidade` varchar(50) NOT NULL,
  `bairro` varchar(50) NOT NULL,
  `numero` char(6) DEFAULT NULL,
  `nmLogradouro` varchar(50) NOT NULL,
  `tpLogradouro` varchar(15) DEFAULT NULL,
  `complemento` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `endereco`
--

INSERT INTO `endereco` (`idEndereco`, `cep`, `estado`, `cidade`, `bairro`, `numero`, `nmLogradouro`, `tpLogradouro`, `complemento`) VALUES
(4, '11040201', 'SP', 'Santos', 'Embaré', '254', 'Rua São José', NULL, '09'),
(7, '', '', '', '', NULL, 'Avenida da Praia, 100', NULL, NULL),
(8, '11000000', 'SP', 'Santos', 'Gonzaga', '100', 'Avenida Ana Costa', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionario`
--

CREATE TABLE `funcionario` (
  `idFuncionario` int(11) NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `pessoa_idPessoa` int(11) NOT NULL,
  `contatos_idcontatos` int(11) NOT NULL,
  `instituicao_idinstituicao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `funcionario`
--

INSERT INTO `funcionario` (`idFuncionario`, `cargo`, `senha`, `pessoa_idPessoa`, `contatos_idcontatos`, `instituicao_idinstituicao`) VALUES
(1, '', '$2y$10$0leHcG2EjBfyYS0lCvxDtes9XHGFbhhvbr4CktdfdbacO.F9JKOWy', 2, 6, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `idoso`
--

CREATE TABLE `idoso` (
  `idIdoso` int(11) NOT NULL,
  `pessoa_idPessoa` int(11) NOT NULL,
  `instituicao_idinstituicao` int(11) NOT NULL,
  `necessidades` varchar(50) DEFAULT NULL,
  `aceita_visita` tinyint(1) DEFAULT 1,
  `aceita_carta` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `idoso`
--

INSERT INTO `idoso` (`idIdoso`, `pessoa_idPessoa`, `instituicao_idinstituicao`, `necessidades`, `aceita_visita`, `aceita_carta`) VALUES
(1, 3, 2, 'Hipertensão controlada. Usa andador.', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `instituicao`
--

CREATE TABLE `instituicao` (
  `idinstituicao` int(11) NOT NULL,
  `nmInstituicao` varchar(50) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `redesSociais` varchar(45) DEFAULT NULL,
  `endereco_idEndereco` int(11) NOT NULL,
  `contatos_idcontatos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `instituicao`
--

INSERT INTO `instituicao` (`idinstituicao`, `nmInstituicao`, `cnpj`, `senha`, `redesSociais`, `endereco_idEndereco`, `contatos_idcontatos`) VALUES
(2, 'Lar Esperança de Santos', '12345678000199', '$2y$10$tcTJfRDeJf1n5H/JyA9DB.FmRPAxzJAAJC4cy/eHQk2xTlLXZuSti', NULL, 7, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pessoa`
--

CREATE TABLE `pessoa` (
  `idPessoa` int(11) NOT NULL,
  `nmPessoa` varchar(50) NOT NULL,
  `cpf` char(11) NOT NULL,
  `dtNascimento` date NOT NULL,
  `fotoPerfil` varchar(250) DEFAULT NULL,
  `sobre` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pessoa`
--

INSERT INTO `pessoa` (`idPessoa`, `nmPessoa`, `cpf`, `dtNascimento`, `fotoPerfil`, `sobre`) VALUES
(1, 'BRENO CUNHA', '29931596821', '2026-03-01', NULL, 'dsafd'),
(2, 'Administrador Teste', '', '0000-00-00', NULL, NULL),
(3, 'Seu Joaquim Silva', '98765439331', '1945-08-20', NULL, 'Adora jogar dominó, ouvir músicas antigas e contar histórias da sua época de marinheiro em Santos.'),
(4, 'João Coração Bom', '11122288001', '1995-05-10', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `voluntario`
--

CREATE TABLE `voluntario` (
  `idVoluntario` int(11) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `pessoa_idPessoa` int(11) NOT NULL,
  `endereco_idEndereco` int(11) DEFAULT NULL,
  `contatos_idcontatos` int(11) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `voluntario`
--

INSERT INTO `voluntario` (`idVoluntario`, `senha`, `pessoa_idPessoa`, `endereco_idEndereco`, `contatos_idcontatos`, `reset_token`, `token_expira`) VALUES
(1, '$2y$10$nGV8nDbm/1dCvyrPTHxUyuXzL1sfQEQ1NvtMQl28nVKvLU6hvcTT.', 1, 4, 4, NULL, NULL),
(2, '$2y$10$ZnM92lDwXGpsBtVwKsQ8huu.ujKgISfrpu3T53qwYt7g5bKQ6ooyy', 4, 8, 7, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamento`
--
ALTER TABLE `agendamento`
  ADD PRIMARY KEY (`idAgendamento`),
  ADD KEY `voluntario_idVoluntario` (`voluntario_idVoluntario`),
  ADD KEY `idoso_idIdoso` (`idoso_idIdoso`);

--
-- Índices de tabela `cartas`
--
ALTER TABLE `cartas`
  ADD PRIMARY KEY (`idCartas`),
  ADD KEY `voluntario_idVoluntario` (`voluntario_idVoluntario`),
  ADD KEY `idoso_idIdoso` (`idoso_idIdoso`);

--
-- Índices de tabela `contatos`
--
ALTER TABLE `contatos`
  ADD PRIMARY KEY (`idcontatos`);

--
-- Índices de tabela `endereco`
--
ALTER TABLE `endereco`
  ADD PRIMARY KEY (`idEndereco`);

--
-- Índices de tabela `funcionario`
--
ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`idFuncionario`),
  ADD KEY `pessoa_idPessoa` (`pessoa_idPessoa`),
  ADD KEY `contatos_idcontatos` (`contatos_idcontatos`),
  ADD KEY `instituicao_idinstituicao` (`instituicao_idinstituicao`);

--
-- Índices de tabela `idoso`
--
ALTER TABLE `idoso`
  ADD PRIMARY KEY (`idIdoso`),
  ADD KEY `pessoa_idPessoa` (`pessoa_idPessoa`),
  ADD KEY `instituicao_idinstituicao` (`instituicao_idinstituicao`);

--
-- Índices de tabela `instituicao`
--
ALTER TABLE `instituicao`
  ADD PRIMARY KEY (`idinstituicao`),
  ADD KEY `endereco_idEndereco` (`endereco_idEndereco`),
  ADD KEY `contatos_idcontatos` (`contatos_idcontatos`);

--
-- Índices de tabela `pessoa`
--
ALTER TABLE `pessoa`
  ADD PRIMARY KEY (`idPessoa`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- Índices de tabela `voluntario`
--
ALTER TABLE `voluntario`
  ADD PRIMARY KEY (`idVoluntario`),
  ADD KEY `pessoa_idPessoa` (`pessoa_idPessoa`),
  ADD KEY `endereco_idEndereco` (`endereco_idEndereco`),
  ADD KEY `contatos_idcontatos` (`contatos_idcontatos`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamento`
--
ALTER TABLE `agendamento`
  MODIFY `idAgendamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `cartas`
--
ALTER TABLE `cartas`
  MODIFY `idCartas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contatos`
--
ALTER TABLE `contatos`
  MODIFY `idcontatos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `endereco`
--
ALTER TABLE `endereco`
  MODIFY `idEndereco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `idFuncionario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `idoso`
--
ALTER TABLE `idoso`
  MODIFY `idIdoso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `instituicao`
--
ALTER TABLE `instituicao`
  MODIFY `idinstituicao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `pessoa`
--
ALTER TABLE `pessoa`
  MODIFY `idPessoa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `voluntario`
--
ALTER TABLE `voluntario`
  MODIFY `idVoluntario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamento`
--
ALTER TABLE `agendamento`
  ADD CONSTRAINT `agendamento_ibfk_1` FOREIGN KEY (`voluntario_idVoluntario`) REFERENCES `voluntario` (`idVoluntario`),
  ADD CONSTRAINT `agendamento_ibfk_2` FOREIGN KEY (`idoso_idIdoso`) REFERENCES `idoso` (`idIdoso`);

--
-- Restrições para tabelas `cartas`
--
ALTER TABLE `cartas`
  ADD CONSTRAINT `cartas_ibfk_1` FOREIGN KEY (`voluntario_idVoluntario`) REFERENCES `voluntario` (`idVoluntario`),
  ADD CONSTRAINT `cartas_ibfk_2` FOREIGN KEY (`idoso_idIdoso`) REFERENCES `idoso` (`idIdoso`);

--
-- Restrições para tabelas `funcionario`
--
ALTER TABLE `funcionario`
  ADD CONSTRAINT `funcionario_ibfk_1` FOREIGN KEY (`pessoa_idPessoa`) REFERENCES `pessoa` (`idPessoa`),
  ADD CONSTRAINT `funcionario_ibfk_2` FOREIGN KEY (`contatos_idcontatos`) REFERENCES `contatos` (`idcontatos`),
  ADD CONSTRAINT `funcionario_ibfk_3` FOREIGN KEY (`instituicao_idinstituicao`) REFERENCES `instituicao` (`idinstituicao`);

--
-- Restrições para tabelas `idoso`
--
ALTER TABLE `idoso`
  ADD CONSTRAINT `idoso_ibfk_1` FOREIGN KEY (`pessoa_idPessoa`) REFERENCES `pessoa` (`idPessoa`),
  ADD CONSTRAINT `idoso_ibfk_2` FOREIGN KEY (`instituicao_idinstituicao`) REFERENCES `instituicao` (`idinstituicao`);

--
-- Restrições para tabelas `instituicao`
--
ALTER TABLE `instituicao`
  ADD CONSTRAINT `instituicao_ibfk_1` FOREIGN KEY (`endereco_idEndereco`) REFERENCES `endereco` (`idEndereco`),
  ADD CONSTRAINT `instituicao_ibfk_2` FOREIGN KEY (`contatos_idcontatos`) REFERENCES `contatos` (`idcontatos`);

--
-- Restrições para tabelas `voluntario`
--
ALTER TABLE `voluntario`
  ADD CONSTRAINT `voluntario_ibfk_1` FOREIGN KEY (`pessoa_idPessoa`) REFERENCES `pessoa` (`idPessoa`),
  ADD CONSTRAINT `voluntario_ibfk_2` FOREIGN KEY (`endereco_idEndereco`) REFERENCES `endereco` (`idEndereco`),
  ADD CONSTRAINT `voluntario_ibfk_3` FOREIGN KEY (`contatos_idcontatos`) REFERENCES `contatos` (`idcontatos`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
