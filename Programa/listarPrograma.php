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

// Consulta para obtener la lista de programas
$sql = "SELECT id_programa, codigo_SNIES, descripcion, logo, correo_contacto, telefono_contacto, lineas_trabajo, resolucion, fecha_generacion FROM programas";
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
    <title>Listado de Programas</title>
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
        .btn-green {
            background-color: #28a745;
            color: #fff;
        }
        .btn-green:hover {
            background-color: #218838;
            color: #fff;
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
        <h3 class="card-header text-center">Listado de Programas</h3>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">ID Programa</th>
                            <th scope="col">Código SNIES</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Logo</th>
                            <th scope="col">Correo de Contacto</th>
                            <th scope="col">Teléfono de Contacto</th>
                            <th scope="col">Líneas de Trabajo</th>
                            <th scope="col">Resolución</th>
                            <th scope="col">Fecha de Generación</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_array($query)) {
                            // Eliminar cualquier prefijo que empiece por 'contacto.'
                            $correo_contacto = preg_replace('/^contacto\./', '', $row['correo_contacto']);
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id_programa']); ?></td>
                            <td><?php echo htmlspecialchars($row['codigo_SNIES']); ?></td>
                            <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                            <td>
                                <?php if ($row['logo']): ?>
                                    <img src="../Programa/<?php echo htmlspecialchars($row['logo']); ?>" alt="Logo" style="width: 60px; height: auto;">
                                <?php else: ?>
                                    Sin Logo
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($correo_contacto); ?></td>
                            <td><?php echo htmlspecialchars($row['telefono_contacto']); ?></td>
                            <td><?php echo htmlspecialchars($row['lineas_trabajo']); ?></td>
                            <td><?php echo htmlspecialchars($row['resolucion']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_generacion']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="editarPrograma.php?id_programa=<?php echo urlencode($row['id_programa']); ?>" class="btn btn-green btn-sm">Modificar</a>
                                    <a href="eliminarPrograma.php?id_programa=<?php echo urlencode($row['id_programa']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este programa?')">Eliminar</a>
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
        </div>
    </div>
</div>

<?php
mysqli_close($conexion);
?>

</body>

</html>
