-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-09-2024 a las 09:23:24
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
  `admin_clave` varchar(200) NOT NULL,
  `admin_email` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`admin_id`, `admin_nombre`, `admin_apellido`, `admin_clave`, `admin_email`) VALUES
(1, 'Administrador', 'Principal', '12345', 'administrador@udenar.edu.co');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistentes`
--

CREATE TABLE `asistentes` (
  `id_asistente` int(10) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `correo` varchar(70) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `contraseña` varchar(200) NOT NULL,
  `fotografia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `asistentes`
--

INSERT INTO `asistentes` (`id_asistente`, `nombre`, `correo`, `telefono`, `direccion`, `genero`, `fecha_nacimiento`, `contraseña`, `fotografia`) VALUES
(1, 'Ana Gómez', 'ana.gomez@udenar.edu.co', '3007654321', 'Avenida 456, Pasto', 'Femenino', '1990-08-20', '$2y$10$f6c6SKImM.BLjW5Me.sY0.om4ywtsIp2jx9Y0SZTOnv4hZcbHmkLG', '147458183-vector-logo-de-reparación-de-electrónica.jpg');

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
(1, '2024-02-01', '2026-02-01', 1),
(2, '2024-03-01', '2026-03-01', 2),
(3, '2024-04-01', '2026-04-01', 3),
(4, '2024-09-03', '2024-09-12', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coordinadores`
--

CREATE TABLE `coordinadores` (
  `id_coordinador` int(10) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `identificacion` varchar(20) NOT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `correo` varchar(70) NOT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `fecha_vinculacion` date DEFAULT NULL,
  `acuerdo_nombramiento` varchar(255) DEFAULT NULL,
  `contraseña` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `coordinadores`
--

INSERT INTO `coordinadores` (`id_coordinador`, `nombre`, `identificacion`, `direccion`, `telefono`, `correo`, `genero`, `fecha_nacimiento`, `fecha_vinculacion`, `acuerdo_nombramiento`, `contraseña`) VALUES
(1, 'Oscar Revelo', '1234567890', 'Calle 78, Pasto', '3002345678', 'oscar.revelo@udenar.edu.co', 'Masculino', '1975-11-25', '2010-01-15', 'Información Posgrados Sistemas.pdf', '12345'),
(2, 'Ricardo Timarán', '0987654321', 'Avenida 321, Pasto', '3008765432', 'ricardo.timaran@udenar.edu.co', 'Masculino', '1982-03-10', '2015-06-01', 'acuerdo_ricardo.pdf', '12345');

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
(1, 'Desarrollo Ágil de Software', 1, 1),
(2, 'Algoritmos y Estructuras de Datos', 1, 1),
(3, 'Arquitectura de Sistemas', 2, 2),
(4, 'Redes y Seguridad', 2, 2),
(5, 'Fundamentos de Inteligencia Artificial', 3, 2),
(6, 'Sistemas Embebidos', 3, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id_docente` int(10) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `identificacion` varchar(20) NOT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `correo` varchar(70) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `formacion_pregrado` varchar(100) DEFAULT NULL,
  `formacion_posgrado` varchar(100) DEFAULT NULL,
  `areas_conocimiento` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id_docente`, `nombre`, `identificacion`, `direccion`, `telefono`, `correo`, `foto`, `formacion_pregrado`, `formacion_posgrado`, `areas_conocimiento`) VALUES
(1, 'Luis Fernández', '1112233445', 'Calle 123, Ciudad', '3004567890', 'luis.fernandez@univalle.edu.co', 'uploads/images.jpeg', 'Ingeniería de Sistemas', 'Máster en Inteligencia Artificial', 'Inteligencia Artificial, Ciencia de Datos'),
(2, 'María Gómez', '5556677889', 'Avenida 456, Ciudad', '3002345678', 'maria.gomez@univalle.edu.co', 'maria_gomez.jpg', 'Ingeniería de Software', 'Doctorado en Ingeniería de Sistemas', 'Ingeniería de Software, IoT y Tecnologías 4.0'),
(3, 'Juan Pablo', '5466568466', 'Barrio Santander', '3104545856', 'juant@gmail.com', 'uploads/images.png', 'sistemas', 'IA', 'qwerfqewtryr5wey');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id_estudiante` int(10) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `identificacion` varchar(20) NOT NULL,
  `codigo_estudiantil` varchar(20) NOT NULL,
  `correo` varchar(70) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `semestre` int(2) DEFAULT NULL,
  `estado_civil` varchar(20) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_egreso` date DEFAULT NULL,
  `id_cohorte` int(10) DEFAULT NULL,
  `fotografia` varchar(255) DEFAULT NULL,
  `id_programa` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_estudiante`, `nombre`, `identificacion`, `codigo_estudiantil`, `correo`, `telefono`, `direccion`, `genero`, `fecha_nacimiento`, `semestre`, `estado_civil`, `fecha_ingreso`, `fecha_egreso`, `id_cohorte`, `fotografia`, `id_programa`) VALUES
(1, 'Laura Martínez', '3216549870', 'E0001', 'laura.martinez@univalle.edu.co', '3001234567', 'Calle 1, Ciudad', 'Femenino', '1995-06-30', 1, 'Casado', '2024-02-01', '2024-09-12', 1, 'descarga.jpeg', 1),
(2, 'Carlos López', '6549873210', 'E0002', 'carlos.lopez@univalle.edu.co', '3007654321', 'Calle 2, Ciudad', 'Masculino', '1994-07-15', 2, 'Casado', '2024-02-01', '2023-02-01', 1, 'images.jpeg', 1),
(4, 'Danilo stiven', '5464963', '218036108', 'danilo1111@gmail.com', '3104259105', 'santander', 'Masculino', '1996-02-05', 6, 'Soltero', '2015-05-02', '2021-06-02', 4, 'descarga (1).jpeg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas`
--

CREATE TABLE `programas` (
  `id_programa` int(10) NOT NULL,
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

INSERT INTO `programas` (`id_programa`, `codigo_SNIES`, `descripcion`, `logo`, `correo_contacto`, `telefono_contacto`, `lineas_trabajo`, `id_coordinador`, `resolucion`, `fecha_generacion`) VALUES
(1, '102625', 'Especialización en Construcción de Software', 'logo_especializacion.jpg', 'especializacion@univalle.edu.co', '3001122334', 'Ingeniería de Software', 1, 'resolucion_especializacion.pdf', '2024-01-01'),
(2, '108094', 'Maestría en Ingeniería de Sistemas y Computación', 'uploads/images.jpeg', 'sistemas@univalle.edu.co', '3005566778', 'Ciencia de Datos', 1, 'resolucion_maestria_sistemas.pdf', '2024-01-01'),
(3, '108092', 'Maestría en Tecnologías de la Información y el Conocimiento', 'logo_maestria_tecnologias.jpg', 'tecnologias@univalle.edu.co', '3009988776', 'Inteligencia Artificial', 2, 'resolucion_maestria_tecnologias.pdf', '2024-01-01');

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
  ADD PRIMARY KEY (`id_asistente`);

--
-- Indices de la tabla `cohortes`
--
ALTER TABLE `cohortes`
  ADD PRIMARY KEY (`id_cohorte`),
  ADD KEY `id_programa` (`id_programa`);

--
-- Indices de la tabla `coordinadores`
--
ALTER TABLE `coordinadores`
  ADD PRIMARY KEY (`id_coordinador`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`),
  ADD KEY `id_programa` (`id_programa`),
  ADD KEY `id_docente` (`id_docente`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id_docente`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD KEY `id_cohorte` (`id_cohorte`),
  ADD KEY `id_programa` (`id_programa`);

--
-- Indices de la tabla `programas`
--
ALTER TABLE `programas`
  ADD PRIMARY KEY (`id_programa`),
  ADD KEY `id_coordinador` (`id_coordinador`);

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
  MODIFY `id_asistente` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `cohortes`
--
ALTER TABLE `cohortes`
  MODIFY `id_cohorte` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `coordinadores`
--
ALTER TABLE `coordinadores`
  MODIFY `id_coordinador` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id_docente` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `programas`
--
ALTER TABLE `programas`
  MODIFY `id_programa` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cohortes`
--
ALTER TABLE `cohortes`
  ADD CONSTRAINT `cohortes_ibfk_1` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`);

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`),
  ADD CONSTRAINT `cursos_ibfk_2` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`);

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`id_cohorte`) REFERENCES `cohortes` (`id_cohorte`),
  ADD CONSTRAINT `estudiantes_ibfk_2` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`);

--
-- Filtros para la tabla `programas`
--
ALTER TABLE `programas`
  ADD CONSTRAINT `programas_ibfk_1` FOREIGN KEY (`id_coordinador`) REFERENCES `coordinadores` (`id_coordinador`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
