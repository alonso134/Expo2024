-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-09-2024 a las 14:05:06
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sge`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id_asistencia` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `id_profesor` int(11) NOT NULL,
  `estado` enum('asistio','falta') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`id_asistencia`, `id_estudiante`, `fecha`, `id_profesor`, `estado`) VALUES
(1, 8, '2024-07-09', 1, 'asistio'),
(3, 9, '2024-07-09', 1, 'asistio'),
(4, 5, '2024-02-20', 1, 'asistio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ausencias`
--

CREATE TABLE `ausencias` (
  `id_ausencia` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `id_profesor` int(11) NOT NULL,
  `estado_justificacion` enum('injustificada','justificada') DEFAULT 'injustificada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ausencias`
--

INSERT INTO `ausencias` (`id_ausencia`, `id_estudiante`, `fecha`, `id_profesor`, `estado_justificacion`) VALUES
(1, 5, '2020-02-20', 1, 'injustificada'),
(2, 8, '2024-04-08', 2, 'justificada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comportamiento`
--

CREATE TABLE `comportamiento` (
  `id_comportamiento` int(11) NOT NULL,
  `codigo` enum('falta leve','falta grave','falta muy grave') NOT NULL,
  `descripcion` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comportamiento`
--

INSERT INTO `comportamiento` (`id_comportamiento`, `codigo`, `descripcion`) VALUES
(1, 'falta leve', 'Llegar tarde a clase'),
(5, 'falta grave', 'Uso inapropiado del teléfono móvil en clase'),
(7, 'falta muy grave', 'Vandalismo en las instalaciones escolares'),
(15, 'falta muy grave', 'papa oso come en clase');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comportamiento_estudiante`
--

CREATE TABLE `comportamiento_estudiante` (
  `id_comportamiento_estudiante` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_comportamiento` int(11) NOT NULL,
  `id_profesor` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `descripcion_adicional` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comportamiento_estudiante`
--

INSERT INTO `comportamiento_estudiante` (`id_comportamiento_estudiante`, `id_estudiante`, `id_comportamiento`, `id_profesor`, `fecha`, `descripcion_adicional`) VALUES
(1, 8, 1, 1, '2020-02-20', 'holi'),
(3, 8, 1, 1, '2024-08-27', 'Pelo largo'),
(4, 8, 5, 1, '2024-08-28', 'Le pego a Tamayo'),
(5, 7, 7, 1, '2024-08-15', 'papa oso come en clase'),
(6, 1, 5, 1, '2024-02-21', 'Se escapa de clase'),
(7, 9, 1, 1, '2024-03-20', 'No hizo la tarea'),
(8, 7, 1, 1, '2024-05-16', 'No hizo la tarea');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id_estudiante` int(11) NOT NULL,
  `nombre_estudiante` varchar(30) NOT NULL,
  `apellido_estudiante` varchar(60) NOT NULL,
  `correo_estudiante` varchar(100) NOT NULL,
  `clave_estudiante` varchar(100) NOT NULL,
  `fecha_de_nacimiento` date DEFAULT NULL,
  `id_grado` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_estudiante`, `nombre_estudiante`, `apellido_estudiante`, `correo_estudiante`, `clave_estudiante`, `fecha_de_nacimiento`, `id_grado`, `fecha_registro`) VALUES
(1, 'Fernando', 'Moreno', 'fa3528028@gmail.com', '$2y$10$sfZfLO/uFQNhnfYtVD/jluRbw05k9ZmR/4edNK1o9cezUy8qvhk7W', '2000-02-20', 1, '2024-07-03 22:02:33'),
(2, 'Juan', 'Pérez', 'juan.perez@example.com', 'clave1234', '2006-05-15', 1, '2024-06-29 12:00:00'),
(3, 'María', 'González', 'maria.gonzalez@example.com', 'clave5678', '2005-08-22', 2, '2024-06-29 12:00:00'),
(4, 'Carlos', 'López', 'carlos.lopez@example.com', 'clave9012', '2007-11-30', 3, '2024-06-29 12:00:00'),
(5, 'Ana', 'Martínez', 'ana.martinez@example.com', 'clave3456', '2006-02-18', 4, '2024-06-29 12:00:00'),
(6, 'Luis', 'Rodríguez', 'luis.rodriguez@example.com', 'clave7890', '2005-07-25', 5, '2024-06-29 12:00:00'),
(7, 'Laura', 'Hernández', 'laura.hernandez@example.com', 'clave2345', '2007-09-10', 6, '2024-06-29 12:00:00'),
(8, 'David', 'García', 'david.garcia@example.com', 'clave6789', '2006-01-20', 7, '2024-06-29 12:00:00'),
(9, 'Isabel', 'Sánchez', 'isabel.sanchez@example.com', 'clave1122', '2005-04-14', 8, '2024-06-29 12:00:00'),
(10, 'Miguel', 'Fernández', 'miguel.fernandez@example.com', 'clave3344', '2007-12-05', 9, '2024-06-29 12:00:00'),
(11, 'Sofía', 'Jiménez', 'sofia.jimenez@example.com', 'clave5566', '2006-06-17', 1, '2024-06-29 12:00:00'),
(12, 'alonso', 'moreno', 'fernando@gmail.com', '$2y$10$TTdXya.F33x5B/oMfLIZvOWDYmS1FgYkhk5IM2jhtdZ32luWgyeru', '2020-02-20', 11, '2024-08-28 22:26:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grados`
--

CREATE TABLE `grados` (
  `id_grado` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `nombre_seccion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grados`
--

INSERT INTO `grados` (`id_grado`, `nombre`, `nombre_seccion`) VALUES
(1, 'Primero', 'Sección A'),
(2, 'Primero', 'Sección B'),
(3, 'Segundo', 'Sección A'),
(4, 'Segundo', 'Sección B'),
(5, 'Tercero', 'Sección A'),
(6, 'Tercero', 'Sección B'),
(7, 'Cuarto', 'Sección A'),
(8, 'Cuarto', 'Sección B'),
(9, 'Quinto', 'Sección A'),
(10, 'Quinto', 'Sección B'),
(11, 'Sexto', 'Sección A'),
(12, 'Sexto', 'Sección B'),
(13, 'Séptimo', 'Sección A'),
(14, 'Séptimo', 'Sección B'),
(15, 'Octavo', 'Sección A'),
(16, 'Octavo', 'Sección B'),
(17, 'Noveno', 'Sección A'),
(18, 'Noveno', 'Sección B');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion`
--

CREATE TABLE `inscripcion` (
  `id_inscripcion` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `llegadas_tarde`
--

CREATE TABLE `llegadas_tarde` (
  `id_llegada` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_materia` int(11) NOT NULL,
  `id_profesor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `llegadas_tarde`
--

INSERT INTO `llegadas_tarde` (`id_llegada`, `id_estudiante`, `fecha`, `hora`, `id_materia`, `id_profesor`) VALUES
(2, 8, '2024-08-27', '00:00:08', 1, 1),
(3, 8, '2024-08-28', '00:00:08', 2, 1),
(4, 8, '2024-08-28', '00:00:08', 1, 1),
(5, 8, '2024-08-28', '08:06:05', 6, 1),
(6, 8, '2024-02-20', '07:20:42', 1, 1),
(7, 8, '2024-08-21', '09:23:05', 2, 1),
(8, 7, '2024-08-28', '03:27:51', 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `llegadas_tarde_institucion`
--

CREATE TABLE `llegadas_tarde_institucion` (
  `id_llegada_tarde_institucion` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_profesor` int(11) NOT NULL,
  `estado` enum('justificado','injustificado') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `llegadas_tarde_institucion`
--

INSERT INTO `llegadas_tarde_institucion` (`id_llegada_tarde_institucion`, `fecha`, `hora`, `id_profesor`, `estado`) VALUES
(1, '2020-02-20', '12:00:00', 1, 'justificado'),
(2, '2024-04-08', '16:53:09', 2, 'injustificado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id_materia` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `id_profesor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`id_materia`, `nombre`, `descripcion`, `id_profesor`) VALUES
(1, 'Ciencias', 'nose xd', 1),
(2, 'Matematicas', 'es mate', 1),
(3, 'Sociales', 'es sociales', 1),
(4, 'Lenguaje', 'es lenguaje', 1),
(5, 'informatica', 'programar en word', 1),
(6, 'informatica', 'programar en word', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `id_nota` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `nota` decimal(5,2) NOT NULL,
  `trimestre` enum('primer trimestre','segundo trimestre','tercer trimestre') NOT NULL,
  `fecha_calificacion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notas`
--

INSERT INTO `notas` (`id_nota`, `id_estudiante`, `id_materia`, `nota`, `trimestre`, `fecha_calificacion`) VALUES
(1, 2, 1, 9.00, 'primer trimestre', '2024-07-08'),
(3, 3, 2, 8.00, 'primer trimestre', '2024-08-27'),
(4, 1, 2, 8.00, 'primer trimestre', '2024-08-27'),
(5, 8, 1, 7.00, 'primer trimestre', '2024-08-27'),
(6, 8, 1, 9.00, 'primer trimestre', '2024-08-28'),
(7, 8, 1, 9.00, 'primer trimestre', '2024-08-26'),
(8, 8, 2, 9.00, 'primer trimestre', '2024-08-28'),
(9, 8, 4, 10.00, 'primer trimestre', '2024-08-28'),
(10, 8, 3, 5.00, 'primer trimestre', '2024-08-26'),
(11, 8, 2, 7.00, 'primer trimestre', '2024-08-27'),
(12, 8, 4, 7.00, 'primer trimestre', '2024-08-21'),
(13, 8, 3, 10.00, 'primer trimestre', '2024-08-27'),
(14, 8, 4, 9.00, 'primer trimestre', '2024-08-23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `observaciones`
--

CREATE TABLE `observaciones` (
  `id_observacion` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_profesor` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `observacion` varchar(700) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `observaciones`
--

INSERT INTO `observaciones` (`id_observacion`, `id_estudiante`, `id_profesor`, `fecha`, `observacion`) VALUES
(1, 8, 1, '2024-07-02', 'estuvo durmiendo durante hora clase'),
(3, 5, 1, '2000-02-20', 'hola'),
(4, 5, 1, '2024-02-20', 'llego tarde'),
(5, 8, 1, '2024-08-28', 'Le cromo el rifle a Indu');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id_profesor` int(11) NOT NULL,
  `nombre_profesor` varchar(30) NOT NULL,
  `apellido_profesor` varchar(60) NOT NULL,
  `correo_profesor` varchar(100) NOT NULL,
  `alias_profesor` varchar(25) NOT NULL,
  `clave_profesor` varchar(100) NOT NULL,
  `fecha_cambio_clave` datetime DEFAULT NULL,
  `intentos_fallidos` int(11) NOT NULL,
  `bloqueado_hasta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id_profesor`, `nombre_profesor`, `apellido_profesor`, `correo_profesor`, `alias_profesor`, `clave_profesor`, `fecha_cambio_clave`, `intentos_fallidos`, `bloqueado_hasta`) VALUES
(1, 'alonso', 'moreno', 'fa3528028@gmail.com', 'alonso', '$2y$10$GNYPp0qmYkxIXx2cWubate.tiLhsbsChK4hPAU9uS/vtPdUu8OD8e', '2024-09-26 23:04:15', 0, NULL),
(2, 'cesar', 'andres', 'cesar@gmail.com', 'cesar12', '$2y$10$oKan4lMxvXiLSpzQ/E0wQuxhnBBtaIPy4WWGIfhdKCwiptR91rfzi', NULL, 0, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_profesor` (`id_profesor`);

--
-- Indices de la tabla `ausencias`
--
ALTER TABLE `ausencias`
  ADD PRIMARY KEY (`id_ausencia`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_profesor` (`id_profesor`);

--
-- Indices de la tabla `comportamiento`
--
ALTER TABLE `comportamiento`
  ADD PRIMARY KEY (`id_comportamiento`);

--
-- Indices de la tabla `comportamiento_estudiante`
--
ALTER TABLE `comportamiento_estudiante`
  ADD PRIMARY KEY (`id_comportamiento_estudiante`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_comportamiento` (`id_comportamiento`),
  ADD KEY `id_profesor` (`id_profesor`) USING BTREE;

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD KEY `id_grado` (`id_grado`) USING BTREE;

--
-- Indices de la tabla `grados`
--
ALTER TABLE `grados`
  ADD PRIMARY KEY (`id_grado`);

--
-- Indices de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD PRIMARY KEY (`id_inscripcion`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_materia` (`id_materia`);

--
-- Indices de la tabla `llegadas_tarde_institucion`
--
ALTER TABLE `llegadas_tarde_institucion`
  ADD PRIMARY KEY (`id_llegada_tarde_institucion`),
  ADD KEY `id_profesor` (`id_profesor`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id_materia`),
  ADD KEY `id_profesor` (`id_profesor`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id_nota`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_materia` (`id_materia`);

--
-- Indices de la tabla `observaciones`
--
ALTER TABLE `observaciones`
  ADD PRIMARY KEY (`id_observacion`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_profesor` (`id_profesor`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id_profesor`),
  ADD UNIQUE KEY `correo_profesor` (`correo_profesor`),
  ADD UNIQUE KEY `alias_profesor` (`alias_profesor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ausencias`
--
ALTER TABLE `ausencias`
  MODIFY `id_ausencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `comportamiento`
--
ALTER TABLE `comportamiento`
  MODIFY `id_comportamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `comportamiento_estudiante`
--
ALTER TABLE `comportamiento_estudiante`
  MODIFY `id_comportamiento_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `grados`
--
ALTER TABLE `grados`
  MODIFY `id_grado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `llegadas_tarde_institucion`
--
ALTER TABLE `llegadas_tarde_institucion`
  MODIFY `id_llegada_tarde_institucion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `id_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `id_nota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `observaciones`
--
ALTER TABLE `observaciones`
  MODIFY `id_observacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id_profesor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `asistencia_ibfk_2` FOREIGN KEY (`id_profesor`) REFERENCES `profesores` (`id_profesor`);

--
-- Filtros para la tabla `ausencias`
--
ALTER TABLE `ausencias`
  ADD CONSTRAINT `ausencias_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `ausencias_ibfk_2` FOREIGN KEY (`id_profesor`) REFERENCES `profesores` (`id_profesor`);

--
-- Filtros para la tabla `comportamiento_estudiante`
--
ALTER TABLE `comportamiento_estudiante`
  ADD CONSTRAINT `comportamiento_estudiante_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `comportamiento_estudiante_ibfk_2` FOREIGN KEY (`id_comportamiento`) REFERENCES `comportamiento` (`id_comportamiento`),
  ADD CONSTRAINT `comportamiento_estudiante_ibfk_3` FOREIGN KEY (`id_profesor`) REFERENCES `profesores` (`id_profesor`);

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`);

--
-- Filtros para la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD CONSTRAINT `inscripcion_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `inscripcion_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`);

--
-- Filtros para la tabla `llegadas_tarde_institucion`
--
ALTER TABLE `llegadas_tarde_institucion`
  ADD CONSTRAINT `llegadas_tarde_institucion_ibfk_1` FOREIGN KEY (`id_profesor`) REFERENCES `profesores` (`id_profesor`);

--
-- Filtros para la tabla `materias`
--
ALTER TABLE `materias`
  ADD CONSTRAINT `materias_ibfk_1` FOREIGN KEY (`id_profesor`) REFERENCES `profesores` (`id_profesor`);

--
-- Filtros para la tabla `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`);

--
-- Filtros para la tabla `observaciones`
--
ALTER TABLE `observaciones`
  ADD CONSTRAINT `observaciones_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `observaciones_ibfk_2` FOREIGN KEY (`id_profesor`) REFERENCES `profesores` (`id_profesor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
