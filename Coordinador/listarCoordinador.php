<?php
include "../complementos/conexion.php";
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Coordinadores</title>
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
        .foto-coordinador {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
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
                            <a class="nav-link text-white" href="../Admin/misdatos.php">Mis datos</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>

<form method="post" action="Calificar_Trabajo.php">
    <div class="container mt-5 pt-5">
        <div class="card">
            <h3 class="card-header text-center">Coordinadores Registrados</h3>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Foto</th> <!-- Nueva columna para la foto -->
                                <th scope="col">Nombre</th>
                                <th scope="col">Identificación</th>
                                <th scope="col">Correo</th>
                                <th scope="col">Teléfono</th>
                                <th scope="col">Dirección</th>
                                <th scope="col">Género</th>
                                <th scope="col">Fecha de Nacimiento</th>
                                <th scope="col">Fecha de Vinculación</th>
                                <th scope="col">Acuerdo de Nombramiento</th>
                                <th scope="col">Programa</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $con = conexion();

                            // Consulta para obtener los datos de 'coordinadores' y el nombre del 'programa'
                            $sql = "SELECT coordinadores.*, programas.descripcion AS programa_descripcion 
                                    FROM coordinadores 
                                    JOIN programas ON coordinadores.id_programa = programas.id_programa";
                            $query = mysqli_query($con, $sql);
                            $i = 0;

                            while ($row = mysqli_fetch_array($query)) {
                                $i++;
                                $pdf_url = '../Coordinador/uploads/' . $row['acuerdo_nombramiento'];
                                $foto_url = '../Coordinador/fotos/' . $row['foto']; // Ruta de la foto
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td>
                                    <?php if (!empty($row['foto'])): ?>
                                        <img src="<?php echo htmlspecialchars($foto_url); ?>" alt="Foto" class="foto-coordinador">
                                    <?php else: ?>
                                        No disponible
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['identificacion']); ?></td>
                                <td><?php echo htmlspecialchars($row['correo']); ?></td>
                                <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                                <td><?php echo htmlspecialchars($row['genero']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_nacimiento']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_vinculacion']); ?></td>
                                <td>
                                    <?php if (!empty($row['acuerdo_nombramiento'])): ?>
                                        <a href="<?php echo htmlspecialchars($pdf_url); ?>" class="btn btn-download btn-primary btn-sm" download>
                                            <i class="bi bi-download"></i><span> Descargar PDF</span>
                                        </a>
                                    <?php else: ?>
                                        No disponible
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['programa_descripcion']); ?></td> <!-- Mostrar nombre del programa -->
                                <td>
                                    <div class="btn-group">
                                        <a href="editarCoordinador.php?id_coordinador=<?php echo $row['id_coordinador']; ?>" class="btn btn-success btn-sm">Modificar</a>
                                        <a href="#" class="btn btn-danger btn-sm" onclick="confirmDelete('eliminarCoordinador.php?id_coordinador=<?php echo $row['id_coordinador']; ?>')">Eliminar</a>
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
</form>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>

</body>

</html>
