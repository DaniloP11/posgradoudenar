<?php
include "../complementos/conexion.php";
session_start();

// Verificar que el usuario está logueado
if (!isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: ../index.html");
    exit();
}

$conexion = conexion();
if (!$conexion) {
    echo "<script>alert('Error de conexión a la base de datos');</script>";
    exit();
}

// Obtener docentes
$docentes = [];
$docenteQuery = "SELECT id_docente, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM docentes";
$docenteResult = mysqli_query($conexion, $docenteQuery);
if ($docenteResult) {
    while ($row = mysqli_fetch_assoc($docenteResult)) {
        $docentes[] = $row;
    }
}

// Obtener programas
$programas = [];
$programaQuery = "SELECT id_programa, nombre_programa FROM programas";
$programaResult = mysqli_query($conexion, $programaQuery);
if ($programaResult) {
    while ($row = mysqli_fetch_assoc($programaResult)) {
        $programas[] = $row;
    }
}

// Verificar si se ha enviado un ID de curso para editar
if (isset($_GET['id'])) {
    $id_curso = intval($_GET['id']);

    // Obtener los datos del curso
    $cursoQuery = "SELECT * FROM cursos WHERE id_curso = ?";
    $stmt = mysqli_prepare($conexion, $cursoQuery);
    mysqli_stmt_bind_param($stmt, 'i', $id_curso);
    mysqli_stmt_execute($stmt);
    $cursoResult = mysqli_stmt_get_result($stmt);

    if ($cursoResult) {
        $curso = mysqli_fetch_assoc($cursoResult);
    } else {
        echo "<script>alert('Curso no encontrado'); window.location.href='listarCurso.php';</script>";
        exit();
    }

    // Procesar el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre_curso = $_POST['nombre_curso'];
        $id_programa = $_POST['id_programa'];
        $id_docente = $_POST['id_docente'];

        // Actualizar curso
        $updateQuery = "UPDATE cursos SET nombre_curso = ?, id_programa = ?, id_docente = ? WHERE id_curso = ?";
        $stmt = mysqli_prepare($conexion, $updateQuery);
        mysqli_stmt_bind_param($stmt, 'siii', $nombre_curso, $id_programa, $id_docente, $id_curso);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Curso actualizado correctamente'); window.location.href='listarCurso.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar el curso');</script>";
        }
    }
} else {
    echo "<script>alert('ID de curso no especificado'); window.location.href='listarCurso.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Curso</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
    <script src='../js/validacion.js'></script>

    <style>
        body {
            background-image: url(../img/font.png);
            background-size: cover;
        }
    </style>
</head>
<body>

<div class="col-3">
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="btn-group">
                <button type="button" class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Sesión <?php echo ($_SESSION['rol'] == '1' ? 'Administrador' : ($_SESSION['rol'] == '2' ? 'Asistente' : 'Coordinador')); ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="../index.html">Cerrar sesión</a></li>
                </ul>
            </div>

            <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel"><?php echo ($_SESSION['rol'] == '1' ? 'Administrador' : ($_SESSION['rol'] == '2' ? 'Asistente' : 'Coordinador')); ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-start flex-grow-1 pe-3">
                        <?php if ($_SESSION['rol'] == '1'): ?>
                            <li class="nav-item">
                                <a class="nav-link active text-white" href="../Admin/InicioAdmi.php">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="../Admin/UsuariosAdmin.html">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="../Admin/misdatos.php">Mis datos</a>
                            </li>
                        <?php elseif ($_SESSION['rol'] == '2'): ?>
                            <li class="nav-item">
                                <a class="nav-link active text-white" href="../Asistente/InicioAsiste.php">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="../Asistente/UsuariosAsiste.html">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="../Asistente/misdatos.php">Mis datos</a>
                            </li>
                        <?php elseif ($_SESSION['rol'] == '3'): ?>
                            <li class="nav-item">
                                <a class="nav-link active text-white" href="../Coordinador/InicioCoord.php">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="../Coordinador/UsuariosCoord.html">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="../Coordinador/misdatos.php">Mis datos</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>

<div class="form_registro">
    <hr><br><br>
    <div class="container px-4">
        <h3 align="center">EDITAR CURSO</h3>
        <div class="row">
            <div class="col py-5">
                <div class="mx-5 bg-light" style="border-radius: 2%; ">
                    <form action="" method="post">
                        <div class="row mb-3 needs-validation" novalidate>
                            <div class="col mx-5 px-5">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="id_curso" class="form-label">ID Curso</label>
                                        <input type="number" class="form-control" id="id_curso" name="id_curso" value="<?php echo htmlspecialchars($curso['id_curso']); ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="nombre_curso" class="form-label">Nombre del Curso</label>
                                        <input type="text" class="form-control" id="nombre_curso" name="nombre_curso" value="<?php echo htmlspecialchars($curso['nombre_curso']); ?>" required>
                                        <div class="invalid-feedback">
                                            Por favor ingrese el nombre del curso.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="id_programa" class="form-label">Programa</label>
                                        <select class="form-select" id="id_programa" name="id_programa" required>
                                            <option value="" disabled>Seleccione un programa</option>
                                            <?php foreach ($programas as $programa): ?>
                                                <option value="<?php echo $programa['id_programa']; ?>" <?php echo ($curso['id_programa'] == $programa['id_programa'] ? 'selected' : ''); ?>>
                                                    <?php echo $programa['nombre_programa']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor seleccione un programa.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="id_docente" class="form-label">Docente</label>
                                        <select class="form-select" id="id_docente" name="id_docente" required>
                                            <option value="" disabled>Seleccione un docente</option>
                                            <?php foreach ($docentes as $docente): ?>
                                                <option value="<?php echo $docente['id_docente']; ?>" <?php echo ($curso['id_docente'] == $docente['id_docente'] ? 'selected' : ''); ?>>
                                                    <?php echo $docente['nombre_completo']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor seleccione un docente.
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../js/validacion.js"></script>
</body>
</html>
