p
Copiar código
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
    die("Error de conexión a la base de datos");
}

$id_programa = $_SESSION['id_programa'];
$rol = $_SESSION['rol'];

// Consulta para obtener la lista de cohortes
if ($rol == '1') { // Administrador
    $sql = "SELECT cohortes.id_cohorte, cohortes.nombre, cohortes.fecha_inicio, cohortes.fecha_fin, programas.descripcion, GROUP_CONCAT(docentes.nombre SEPARATOR ', ') AS nombres_docentes
            FROM cohortes 
            JOIN programas ON cohortes.id_programa = programas.id_programa
            LEFT JOIN docentes ON cohortes.id_cohorte = docentes.id_cohorte
            GROUP BY cohortes.id_cohorte";
} else { // Asistente o Coordinador
    $sql = "SELECT cohortes.id_cohorte, cohortes.nombre, cohortes.fecha_inicio, cohortes.fecha_fin, programas.descripcion, GROUP_CONCAT(docentes.nombre SEPARATOR ', ') AS nombres_docentes
            FROM cohortes 
            JOIN programas ON cohortes.id_programa = programas.id_programa 
            LEFT JOIN docentes ON cohortes.id_cohorte = docentes.id_cohorte
            WHERE cohortes.id_programa = ?
            GROUP BY cohortes.id_cohorte";
}

$stmt = mysqli_prepare($conexion, $sql);
if ($rol != '1') {
    mysqli_stmt_bind_param($stmt, 'i', $id_programa); // 'i' para entero
}
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);

if (!$query) {
    die("Error en la consulta: " . mysqli_error($conexion));
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Cohortes</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style2.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>

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
                                <a class="nav-link text-white" href="../Admin/perfiladmin.php">Mi perfil</a>
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

<div class="container mt-5 pt-5">
    <div class="card">
        <h3 class="card-header text-center">Cohortes Registrados</h3>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Cohorte</th>
                            <th scope="col">Fecha de Inicio</th>
                            <th scope="col">Fecha de Fin</th>
                            <th scope="col">Programa</th>
                            <th scope="col">Estudiantes</th>
                            <th scope="col">Docente</th> <!-- Nueva columna para Docente -->
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_array($query)) {
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id_cohorte']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_inicio']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_fin']); ?></td>
                            <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                            <td>
                                <a href="verEstudiantes.php?id_cohorte=<?php echo $row['id_cohorte']; ?>" class="btn btn-warning btn-sm">Ver</a>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['nombres_docentes']); ?> <!-- Muestra los nombres de los docentes -->
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="editarCohorte.php?id=<?php echo $row['id_cohorte']; ?>" class="btn btn-success btn-sm">Modificar</a>
                                    <a href="eliminarCohorte.php?id_cohorte=<?php echo urlencode($row['id_cohorte']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este cohorte?')">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation example">
                <ul class="pagination mt-3">
                    <li class="page-item disabled">
                        <a class="page-link">Anterior</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Siguiente</a>
                    </li>
                </ul>
            </nav>

            <div class="text-center mt-3">
                <a href="descargar_reporte.php" class="btn btn-primary">Descargar Reporte</a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
