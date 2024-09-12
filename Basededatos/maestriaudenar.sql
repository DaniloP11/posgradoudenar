-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-09-2024 a las 07:12:53
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
-- Base de datos: `maestriaudenar`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `admin_id` int(10) NOT NULL,
  `admin_nombre` varchar(40) NOT NULL,
  `admin_apellido` varchar(40) NOT NULL,
  `admin_usuario` varchar(20) NOT NULL,
  `admin_clave` varchar(200) NOT NULL,
  `admin_email` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`admin_id`, `admin_nombre`, `admin_apellido`, `admin_usuario`, `admin_clave`, `admin_email`) VALUES
(1, 'Administrador', 'Principal', 'admin', '12345', 'admin@correo.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistentes`
--

CREATE TABLE `asistentes` (
  `id_asistente` int(10) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `apellido` varchar(40) NOT NULL,
  `correo` varchar(70) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `contraseña` varchar(200) NOT NULL,
  `fotografia` varchar(255) DEFAULT NULL,
  `id_programa` int(10) DEFAULT NULL,
  `id_coordinador` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `asistentes`
--

INSERT INTO `asistentes` (`id_asistente`, `nombre`, `apellido`, `correo`, `telefono`, `direccion`, `genero`, `fecha_nacimiento`, `contraseña`, `fotografia`, `id_programa`, `id_coordinador`) VALUES
(1, 'Ana', 'Pérez', 'ana@udenar.edu.co', '3123456790', 'Calle 456', 'Femenino', '1990-05-10', '12345', 'usuario.png', 1, 2),
(2, 'stivengfgh', 'Paguay', 'danilo06@gmail.com', '3104259105', 'santa', 'M', '2024-09-27', '12345', 'facultad.png', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cohortes`
--

CREATE TABLE `cohortes` (
  `id_cohorte` int(10) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `id_programa` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `cohortes`
--

INSERT INTO `cohortes` (`id_cohorte`, `fecha_inicio`, `fecha_fin`, `id_programa`) VALUES
(1, '2022-05-31', '2024-02-06', 2),
(2, '2024-01-04', '2024-09-04', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coordinadores`
--

CREATE TABLE `coordinadores` (
  `id_coordinador` int(10) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `apellido` varchar(40) NOT NULL,
  `correo` varchar(70) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `fecha_vinculacion` date DEFAULT NULL,
  `acuerdo_nombramiento` varchar(255) DEFAULT NULL,
  `contraseña` varchar(200) NOT NULL,
  `id_programa` int(10) DEFAULT NULL,
  `id_asistente` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `coordinadores`
--

INSERT INTO `coordinadores` (`id_coordinador`, `nombre`, `apellido`, `correo`, `telefono`, `direccion`, `genero`, `fecha_nacimiento`, `fecha_vinculacion`, `acuerdo_nombramiento`, `contraseña`, `id_programa`, `id_asistente`) VALUES
(1, 'Oscar', 'Revelo', 'oscar@udenar.edu.co', '3123456789', 'Calle 123', 'Masculino', '1980-01-01', '2019-06-01', 'Información Posgrados Sistemas.pdf', '123456', 2, 2),
(2, 'Danilo', 'Paguay', 'danilo@gmail.com', '3104259105', 'Barrio Santander', 'M', '2012-01-17', '2024-09-05', 'Información Posgrados Sistemas.pdf', '12345', 1, 1),
(3, 'stivengfgh', 'Paguayuhu', 'danilo06@gmail.com', '3104259105', 'Barrio Santander', 'Masculino', '2010-02-24', '2024-09-25', 'Información Posgrados Sistemas.pdf', '12345', 1, 1),
(4, 'stiven', 'ls', 'danilo999@gmail.com', '3104259105', 'santr', 'Masculino', '2024-09-04', '2024-09-08', 'Información Posgrados Sistemas.pdf', '12345', 1, 1),
(5, 'Stefania', 'Casanova', 'stefa11@gmail.com', '3125841968', 'santander', 'Femenino', '2007-01-30', '2021-04-27', 'Información Posgrados Sistemas.pdf', '12345', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(10) NOT NULL,
  `nombre_curso` varchar(50) NOT NULL,
  `id_programa` int(10) NOT NULL,
  `id_docente` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id_curso`, `nombre_curso`, `id_programa`, `id_docente`) VALUES
(2, 'electrónica', 2, 2),
(3, 'programacion', 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id_docente` int(10) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `apellido` varchar(40) NOT NULL,
  `correo` varchar(70) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `formacion_pregrado` varchar(100) DEFAULT NULL,
  `formacion_posgrado` varchar(100) DEFAULT NULL,
  `areas_conocimiento` text DEFAULT NULL,
  `id_programa` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id_docente`, `nombre`, `apellido`, `correo`, `telefono`, `direccion`, `foto`, `formacion_pregrado`, `formacion_posgrado`, `areas_conocimiento`, `id_programa`) VALUES
(1, 'Carlos', 'López', 'carlos@correo.com', '3123456791', 'Calle 101', 'foto1.jpg', 'Ingeniería de Sistemas', 'Maestría en IA', 'Inteligencia Artificial, Ciencia de Datos', 1),
(2, 'Danilo', 'Paguay', 'danilo@udenar.edu.co', '3104259105', 'Barrio Santander', 'salir.png', 'sistemas', 'IA', 'Programación', 1),
(4, 'Carolina', 'Rosero', 'carolina@gmail.com', '3104545856', 'Ipiales', 'uploads/usuario.png', 'sistemas', 'Programacion', 'Python', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id_estudiante` int(10) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `apellido` varchar(40) NOT NULL,
  `codigo_estudiantil` varchar(20) NOT NULL,
  `correo` varchar(70) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `semestre` int(2) DEFAULT NULL,
  `estado_civil` varchar(20) DEFAULT NULL,
  `id_cohorte` int(10) DEFAULT NULL,
  `fotografia` varchar(255) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_egreso` date DEFAULT NULL,
  `id_programa` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_estudiante`, `nombre`, `apellido`, `codigo_estudiantil`, `correo`, `telefono`, `direccion`, `genero`, `fecha_nacimiento`, `semestre`, `estado_civil`, `id_cohorte`, `fotografia`, `fecha_ingreso`, `fecha_egreso`, `id_programa`) VALUES
(1, 'Juan Pablo', 'Pérez', '2023001', 'juan.perez@correo.com', '3123456780', 'Calle 789', 'Masculino', '1995-06-12', 1, 'Soltero', 1, 'icon.png', '2019-01-30', '2024-09-09', 1),
(2, 'Danilo st', 'Paguayuhu', '218036106', 'danilo11@gmail.com', '3104259105', 'santander', 'Masculino', '2024-09-02', 2, 'Soltero', NULL, 'disquete.png', '2024-09-10', '2024-09-11', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas`
--

CREATE TABLE `programas` (
  `id_programa` int(10) NOT NULL,
  `nombre_programa` varchar(50) NOT NULL,
  `codigo_SNIES` varchar(20) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `correo_contacto` varchar(70) DEFAULT NULL,
  `telefono_contacto` varchar(15) DEFAULT NULL,
  `lineas_trabajo` text DEFAULT NULL,
  `id_coordinador` int(10) NOT NULL,
  `resolucion` varchar(255) DEFAULT NULL,
  `fecha_generacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `programas`
--

INSERT INTO `programas` (`id_programa`, `nombre_programa`, `codigo_SNIES`, `descripcion`, `logo`, `correo_contacto`, `telefono_contacto`, `lineas_trabajo`, `id_coordinador`, `resolucion`, `fecha_generacion`) VALUES
(1, 'Maestría en Ingeniería de Sistemas', '108094', 'Descripción de la maestría IA Y DATOS', 'uploads/requisito.png', 'posgrados@udenar.edu.co', '3123456789', 'Inteligencia Artificial, Ciencia de Datos', 1, 'Resolución No. 021584', '2021-11-21'),
(2, 'Maestria en IA', '123456', 'Inteligencia Artificial', 'uploads/66e1232dd2aae.png', 'maestrias@udenar.edu.co', '3104258762', 'IA, Analisis de datos', 1, 'Resolución No. 006182', '2019-06-03'),
(3, 'Desarrollo Familiar', '123457', 'Acompañamiento familiar y comunitario', 'uploads/66e1233c6d607.png', 'desarrollofamiliar@udenar.edu.co', '3124568546', 'Salud', 5, 'Resolución No.1025', '2022-06-10'),
(4, 'Derecho', '456887', 'Acuerdos de Constitucion', 'uploads/Logotipo01.png', 'derecho@udenar.edu.co', '3154687524', 'Abogado', 3, 'Resolución No.1045', '2024-09-04');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indices de la tabla `asistentes`
--
ALTER TABLE `asistentes`
  ADD PRIMARY KEY (`id_asistente`),
  ADD KEY `asistente_ibfk_1` (`id_programa`),
  ADD KEY `asistente_ibfk_2` (`id_coordinador`);

--
-- Indices de la tabla `cohortes`
--
ALTER TABLE `cohortes`
  ADD PRIMARY KEY (`id_cohorte`),
  ADD KEY `cohorte_ibfk_1` (`id_programa`);

--
-- Indices de la tabla `coordinadores`
--
ALTER TABLE `coordinadores`
  ADD PRIMARY KEY (`id_coordinador`),
  ADD KEY `coordinador_ibfk_1` (`id_programa`),
  ADD KEY `coordinador_ibfk_2` (`id_asistente`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`),
  ADD KEY `curso_ibfk_1` (`id_programa`),
  ADD KEY `curso_ibfk_2` (`id_docente`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id_docente`),
  ADD KEY `fk_programa` (`id_programa`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD KEY `estudiante_ibfk_1` (`id_cohorte`),
  ADD KEY `estudiante_ibfk_2` (`id_programa`);

--
-- Indices de la tabla `programas`
--
ALTER TABLE `programas`
  ADD PRIMARY KEY (`id_programa`),
  ADD KEY `programa_ibfk_1` (`id_coordinador`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `admin_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `asistentes`
--
ALTER TABLE `asistentes`
  MODIFY `id_asistente` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `cohortes`
--
ALTER TABLE `cohortes`
  MODIFY `id_cohorte` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `coordinadores`
--
ALTER TABLE `coordinadores`
  MODIFY `id_coordinador` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1002;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id_docente` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `programas`
--
ALTER TABLE `programas`
  MODIFY `id_programa` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistentes`
--
ALTER TABLE `asistentes`
  ADD CONSTRAINT `asistente_ibfk_2` FOREIGN KEY (`id_coordinador`) REFERENCES `coordinadores` (`id_coordinador`);

--
-- Filtros para la tabla `cohortes`
--
ALTER TABLE `cohortes`
  ADD CONSTRAINT `cohorte_ibfk_1` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`);

--
-- Filtros para la tabla `coordinadores`
--
ALTER TABLE `coordinadores`
  ADD CONSTRAINT `coordinador_ibfk_2` FOREIGN KEY (`id_asistente`) REFERENCES `asistentes` (`id_asistente`);

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`),
  ADD CONSTRAINT `curso_ibfk_2` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`);

--
-- Filtros para la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD CONSTRAINT `fk_programa` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`);

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiante_ibfk_1` FOREIGN KEY (`id_cohorte`) REFERENCES `cohortes` (`id_cohorte`) ON DELETE SET NULL,
  ADD CONSTRAINT `estudiante_ibfk_2` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`);

--
-- Filtros para la tabla `programas`
--
ALTER TABLE `programas`
  ADD CONSTRAINT `programa_ibfk_1` FOREIGN KEY (`id_coordinador`) REFERENCES `coordinadores` (`id_coordinador`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
