<?php
include "../complementos/conexion.php";
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Lista de Asistentes</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style2.css">
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
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
                    Sesión Administrador
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="../index.html">Cerrar sesión</a></li>
                </ul>
            </div>

            <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Administrador</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-start flex-grow-1 pe-3">
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
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>

<div class="container mt-5 pt-5">
    <div class="card">
        <h3 class="card-header text-center">Asistentes Registrados</h3>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Fotografía</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Dirección</th>
                            <th scope="col">Género</th>
                            <th scope="col">Fecha de Nacimiento</th>
                            <th scope="col">Programa</th>
                            <th scope="col">Coordinador</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $con = conexion();

                        // Consulta para obtener los datos de la tabla 'asistentes' junto con 'programas' y 'coordinadores'
                        $sql = "SELECT a.*, p.descripcion AS programa, c.nombre AS coordinador 
                                FROM asistentes a 
                                LEFT JOIN programas p ON a.id_programa = p.id_programa 
                                LEFT JOIN coordinadores c ON a.id_coordinador = c.id_coordinador";
                        $query = mysqli_query($con, $sql);
                        $i = 0;

                        while ($row = mysqli_fetch_array($query)) {
                            $i++;
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td>
                                <?php if ($row['fotografia']): ?>
                                    <img src="../Asistente/uploads/<?php echo htmlspecialchars($row['fotografia']); ?>" alt="Fotografía" style="width: 60px; height: auto;">
                                <?php else: ?>
                                    No disponible
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['correo']); ?></td>
                            <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                            <td><?php echo htmlspecialchars($row['genero']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_nacimiento']); ?></td>
                            <td><?php echo htmlspecialchars($row['programa']); ?></td>
                            <td><?php echo htmlspecialchars($row['coordinador']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="editarAsistente.php?id=<?php echo $row['id_asistente']; ?>" class="btn btn-success btn-sm">Modificar</a>
                                    <a href="#" class="btn btn-danger btn-sm" onclick="confirmDelete('eliminarAsistente.php?id_asistente=<?php echo $row['id_asistente']; ?>')">Eliminar</a>
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

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>

</body>

</html>
