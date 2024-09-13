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

// Consulta para obtener la lista de docentes
$sql = "SELECT id_docente, nombre, apellido, correo, telefono, direccion, foto, formacion_pregrado, formacion_posgrado, areas_conocimiento, id_programa
        FROM docentes";
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
    <title>Listado de Docentes</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style2.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>
    <script src="../js/script.js"></script>

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
        <h3 class="card-header text-center">Listado de Docentes</h3>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">ID Docente</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellido</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Dirección</th>
                            <th scope="col">Foto</th>
                            <th scope="col">Formación Pregrado</th>
                            <th scope="col">Formación Posgrado</th>
                            <th scope="col">Áreas de Conocimiento</th>
                            <th scope="col">Programa</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query)) {
                            $id_docente = htmlspecialchars($row['id_docente']);
                            $nombre = htmlspecialchars($row['nombre']);
                            $apellido = htmlspecialchars($row['apellido']);
                            $correo = htmlspecialchars($row['correo']);
                            $telefono = htmlspecialchars($row['telefono']);
                            $direccion = htmlspecialchars($row['direccion']);
                            $foto = htmlspecialchars($row['foto']);
                            $formacion_pregrado = htmlspecialchars($row['formacion_pregrado']);
                            $formacion_posgrado = htmlspecialchars($row['formacion_posgrado']);
                            $areas_conocimiento = htmlspecialchars($row['areas_conocimiento']);
                            $id_programa = htmlspecialchars($row['id_programa']);

                            // Obtener nombre del programa
                            $programaSql = "SELECT nombre_programa FROM programas WHERE id_programa = ?";
                            $stmt = mysqli_prepare($conexion, $programaSql);
                            mysqli_stmt_bind_param($stmt, 'i', $id_programa);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_bind_result($stmt, $nombre_programa);
                            mysqli_stmt_fetch($stmt);
                            mysqli_stmt_close($stmt);
                        ?>
                        <tr>
                            <td><?php echo $id_docente; ?></td>
                            <td><?php echo $nombre; ?></td>
                            <td><?php echo $apellido; ?></td>
                            <td><?php echo $correo; ?></td>
                            <td><?php echo $telefono; ?></td>
                            <td><?php echo $direccion; ?></td>
                            <td>
                                <?php if ($foto): ?>
                                    <img src="<?php echo $foto; ?>" alt="Foto del docente" style="width: 60px; height: auto;">
                                <?php else: ?>
                                    No disponible
                                <?php endif; ?>
                            </td>
                            <td><?php echo $formacion_pregrado; ?></td>
                            <td><?php echo $formacion_posgrado; ?></td>
                            <td><?php echo $areas_conocimiento; ?></td>
                            <td><?php echo $nombre_programa ? $nombre_programa : 'No asignado'; ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="editarDocente.php?id=<?php echo $id_docente; ?>" class="btn btn-success btn-sm">Modificar</a>
                                    <a href="#" class="btn btn-danger btn-sm" onclick="confirmDelete('eliminarDocente.php?id_docente=<?php echo $row['id_docente']; ?>')">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Placeholder (if needed) -->
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
