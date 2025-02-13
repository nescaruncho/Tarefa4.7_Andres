-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: dbXDebug
-- Tiempo de generación: 13-02-2025 a las 22:23:32
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tarefa4.7`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `idComentario` int NOT NULL,
  `usuario` int NOT NULL,
  `idProduto` int NOT NULL,
  `comentario` text NOT NULL,
  `dataCreacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `dataModeracion` datetime DEFAULT NULL,
  `moderado` enum('si','non') DEFAULT 'non'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produto`
--

CREATE TABLE `produto` (
  `idProduto` int NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricion` text,
  `imaxe` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `produto`
--

INSERT INTO `produto` (`idProduto`, `nome`, `descricion`, `imaxe`) VALUES
(1, 'Teclado Mecánico RGB', 'Teclado mecánico con switches rojos y retroiluminación RGB personalizable.', 'images/teclado_rgb.jpg'),
(2, 'Ratón Gaming 16000 DPI', 'Ratón ergonómico con sensor óptico de alta precisión y botones programables.', 'images/raton_gaming.jpg'),
(3, 'Monitor 27\" 144Hz', 'Monitor Full HD con tasa de refresco de 144Hz y 1ms de respuesta.', 'images/monitor_27_144hz.jpg'),
(4, 'Silla Gaming Ergonómica', 'Silla ergonómica con reposabrazos ajustables y cojín lumbar.', 'images/silla_gaming.jpg'),
(5, 'Procesador Ryzen 7 5800X', 'Procesador de 8 núcleos y 16 hilos con una frecuencia de hasta 4.7 GHz.', 'images/ryzen_7_5800x.jpg'),
(6, 'Tarjeta Gráfica RTX 3060', 'Tarjeta gráfica con 12GB GDDR6 y tecnología Ray Tracing.', 'images/rtx_3060.jpg'),
(7, 'Placa Base B550M', 'Placa base compatible con procesadores AMD Ryzen y PCIe 4.0.', 'images/placa_base_b550m.jpg'),
(8, 'Memoria RAM 16GB DDR4', 'Memoria RAM DDR4 3200MHz en módulo de 16GB.', 'images/ram_16gb_ddr4.jpg'),
(9, 'Disco SSD NVMe 1TB', 'Unidad de almacenamiento SSD NVMe con velocidades de hasta 3500MB/s.', 'images/ssd_nvme_1tb.jpg'),
(10, 'Fuente de Alimentación 750W', 'Fuente de alimentación 80 Plus Gold con cables modulares.', 'images/fuente_750w.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nomeUsuario` varchar(50) NOT NULL,
  `nomeCompleto` varchar(100) NOT NULL,
  `contrasinal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) NOT NULL,
  `ultimaConexion` datetime DEFAULT NULL,
  `rol` enum('usuario','moderador','administrador') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nomeUsuario`, `nomeCompleto`, `contrasinal`, `email`, `ultimaConexion`, `rol`) VALUES
(1, 'caruncho', 'Andres Caruncho', '$2y$10$nHv7EO3.gg37/7BLDA.aoeJOYjZZayLS1UCXvIGKC4yhJAbTGZ0iG', 'andresc@gmail.com', '2025-02-13 22:22:16', 'usuario'),
(2, 'admin', 'Administrador', 'abc123.', 'admin@tienda.com', NULL, 'administrador'),
(3, 'moderador', 'Moderador', '$2y$10$NbXVkZt.PsTzKI4ThoKuJODdqPywfKW5DmU2SpCaQd0P7NdoVt2wm', 'moderador@tienda.es', '2025-02-13 22:22:46', 'moderador');

--
-- Disparadores `usuarios`
--
DELIMITER $$
CREATE TRIGGER `prevent_admin_delete` BEFORE DELETE ON `usuarios` FOR EACH ROW BEGIN 
    IF OLD.nomeUsuario = 'admin' THEN 
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'No se puede eliminar el usuario administrador.';
    END IF; 
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`idComentario`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `idProduto` (`idProduto`);

--
-- Indices de la tabla `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`idProduto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomeUsuario` (`nomeUsuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `idComentario` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `produto`
--
ALTER TABLE `produto`
  MODIFY `idProduto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`idProduto`) REFERENCES `produto` (`idProduto`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
