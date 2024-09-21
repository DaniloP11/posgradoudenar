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

// Consulta para obtener la lista de estudiantes con la descripción del programa
$sql = "SELECT e.*, p.descripcion, c.nombre AS cohorte_nombre 
        FROM estudiantes e
        JOIN programas p ON e.id_programa = p.id_programa
        JOIN cohortes c ON e.id_cohorte = c.id_cohorte";
$query = mysqli_query($conexion, $sql);

if (!$query) {
    die("Error en la consulta: " . mysqli_error($conexion));
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Estudiantes</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style2.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>

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

<div class="container mt-5 pt-5">
    <div class="card">
        <h3 class="card-header text-center">Listado de Estudiantes</h3>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">ID Estudiante</th>
                            <th scope="col">Fotografía</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Identificación</th>
                            <th scope="col">Código Estudiantil</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Dirección</th>
                            <th scope="col">Género</th>
                            <th scope="col">Fecha de Nacimiento</th>
                            <th scope="col">Semestre</th>
                            <th scope="col">Estado Civil</th>
                            <th scope="col">Cohorte</th>
                            <th scope="col">Programa</th>
                            <th scope="col">Fecha de Ingreso</th>
                            <th scope="col">Fecha de Egreso</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_array($query)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id_estudiante']); ?></td>
                            <td>
                                <?php if ($row['fotografia']): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($row['fotografia']); ?>" alt="Fotografía" style="width: 60px; height: auto;">
                                <?php else: ?>
                                    No disponible
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['identificacion']); ?></td>
                            <td><?php echo htmlspecialchars($row['codigo_estudiantil']); ?></td>
                            <td><?php echo htmlspecialchars($row['correo']); ?></td>
                            <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                            <td><?php echo htmlspecialchars($row['genero']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_nacimiento']); ?></td>
                            <td><?php echo htmlspecialchars($row['semestre']); ?></td>
                            <td><?php echo htmlspecialchars($row['estado_civil']); ?></td>
                            <td><?php echo htmlspecialchars($row['cohorte_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_ingreso']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_egreso']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="editarEstudiante.php?id=<?php echo $row['id_estudiante']; ?>" class="btn btn-success btn-sm">Modificar</a>
                                    <a href="eliminarEstudiante.php?id_estudiante=<?php echo urlencode($row['id_estudiante']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este estudiante?')">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
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

        </div>
    </div>
</div>

</body>

</html>

<?php
mysqli_close($conexion);
?>
