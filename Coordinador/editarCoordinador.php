<?php
include "../complementos/conexion.php";

// Verificar si se ha proporcionado un ID de coordinador
if (isset($_GET['id_coordinador'])) {
    $id_coordinador = intval($_GET['id_coordinador']);

    // Obtener la información del coordinador
    $query = mysqli_query(conexion(), "SELECT * FROM coordinadores WHERE id_coordinador = $id_coordinador");
    $coordinador = mysqli_fetch_assoc($query);

    if (!$coordinador) {
        echo "<script>alert('Coordinador no encontrado'); window.location.href='listarCoordinador.php';</script>";
        exit;
    }

    // Cargar programas y asistentes para el dropdown
    $programas_query = mysqli_query(conexion(), "SELECT id_programa, nombre_programa FROM programas");
    $asistentes_query = mysqli_query(conexion(), "SELECT id_asistente, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM asistentes");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $genero = $_POST['genero'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $fecha_vinculacion = $_POST['fecha_vinculacion'];
        $id_programa = $_POST['id_programa'];
        $contraseña = $_POST['contraseña'];
        $id_asistente = $_POST['id_asistente'];

        // Verificar coincidencia de contraseñas
        if ($contraseña !== $_POST['confcontraseña']) {
            echo "<script>alert('La contraseña no coincide');</script>";
        } else {
            // Manejo del archivo PDF para el acuerdo de nombramiento
            $acuerdo_nombramiento = $coordinador['acuerdo_nombramiento'];
            if (isset($_FILES['acuerdo_nombramiento']) && $_FILES['acuerdo_nombramiento']['error'] == 0) {
                $upload_dir = '../Coordinador/uploads';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $upload_file = $upload_dir . basename($_FILES['acuerdo_nombramiento']['name']);
                if (move_uploaded_file($_FILES['acuerdo_nombramiento']['tmp_name'], $upload_file)) {
                    // Eliminar el archivo viejo si existe
                    if ($acuerdo_nombramiento && file_exists($upload_dir . $acuerdo_nombramiento)) {
                        unlink($upload_dir . $acuerdo_nombramiento);
                    }
                    $acuerdo_nombramiento = $_FILES['acuerdo_nombramiento']['name'];
                } else {
                    echo "<script>alert('Error al cargar el acuerdo de nombramiento');</script>";
                }
            }

            // Actualización en la base de datos
            $query_update_coordinador = mysqli_query(conexion(), "UPDATE coordinadores SET nombre = '$nombre', apellido = '$apellido', correo = '$correo', telefono = '$telefono', direccion = '$direccion', genero = '$genero', fecha_nacimiento = '$fecha_nacimiento', fecha_vinculacion = '$fecha_vinculacion', acuerdo_nombramiento = '$acuerdo_nombramiento', contraseña = '$contraseña', id_programa = '$id_programa', id_asistente = '$id_asistente' WHERE id_coordinador = $id_coordinador");

            if ($query_update_coordinador) {
                echo "<script>alert('Coordinador actualizado correctamente'); window.location.href='listarCoordinador.php';</script>";
            } else {
                echo "<script>alert('Error al actualizar el coordinador');</script>";
            }
        }
    }
} else {
    echo "<script>alert('ID de coordinador no proporcionado'); window.location.href='listarCoordinador.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Coordinador</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
    <script src='main.js'></script>
    <script src='validacion.js'></script>

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
                                <a class="nav-link text-white" href="../Admin/misdatos.php">Mis datos</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <div class="form_registro">
        <hr><br><br>
        <div class="container px-4">
            <h3 align="center">EDITAR COORDINADOR</h3>
            <div class="row">
                <div class="col py-5">
                    <div class="mx-5 bg-light" style="border-radius: 2%;">
                        <form action="" method="post" enctype="multipart/form-data">

                            <div class="row mb-3">
                                <div class="col mx-5 px-5">
                                    <label for="id_coordinador" class="form-label">ID Coordinador</label>
                                    <input type="text" class="form-control" id="id_coordinador" name="id_coordinador" value="<?php echo $coordinador['id_coordinador']; ?>" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col mx-5 px-5">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $coordinador['nombre']; ?>">
                                </div>
                                <div class="col mx-5 px-5">
                                    <label for="apellido" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $coordinador['apellido']; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col mx-5 px-5">
                                    <label for="correo" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $coordinador['correo']; ?>">
                                </div>
                                <div class="col mx-5 px-5">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $coordinador['telefono']; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col mx-5 px-5">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $coordinador['direccion']; ?>">
                                </div>
                                <div class="col mx-5 px-5">
                                    <label for="genero" class="form-label">Género</label>
                                    <select id="genero" name="genero" class="form-select">
                                        <option value="Masculino" <?php echo ($coordinador['genero'] === 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                                        <option value="Femenino" <?php echo ($coordinador['genero'] === 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                                        <option value="Otro" <?php echo ($coordinador['genero'] === 'Otro') ? 'selected' : ''; ?>>Otro</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col mx-5 px-5">
                                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $coordinador['fecha_nacimiento']; ?>">
                                </div>
                                <div class="col mx-5 px-5">
                                    <label for="fecha_vinculacion" class="form-label">Fecha de Vinculación</label>
                                    <input type="date" class="form-control" id="fecha_vinculacion" name="fecha_vinculacion" value="<?php echo $coordinador['fecha_vinculacion']; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col mx-5 px-5">
                                    <label for="id_programa" class="form-label">Programa</label>
                                    <select id="id_programa" name="id_programa" class="form-select">
                                        <option value="" selected>Seleccionar</option>
                                        <?php while ($programa = mysqli_fetch_assoc($programas_query)): ?>
                                            <option value="<?php echo $programa['id_programa']; ?>" <?php echo ($coordinador['id_programa'] == $programa['id_programa']) ? 'selected' : ''; ?>><?php echo $programa['nombre_programa']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col mx-5 px-5">
                                    <label for="acuerdo_nombramiento" class="form-label">Acuerdo de Nombramiento (PDF)</label>
                                    <input type="file" class="form-control" id="acuerdo_nombramiento" name="acuerdo_nombramiento">
                                    <?php if ($coordinador['acuerdo_nombramiento']): ?>
                                        <a href="../Coordinador/uploads<?php echo $coordinador['acuerdo_nombramiento']; ?>" target="_blank">Ver archivo actual</a>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col mx-5 px-5">
                                    <label for="id_asistente" class="form-label">Asistente</label>
                                    <select id="id_asistente" name="id_asistente" class="form-select">
                                        <?php while ($row = mysqli_fetch_assoc($asistentes_query)): ?>
                                            <option value="<?php echo $row['id_asistente']; ?>" <?php echo ($coordinador['id_asistente'] == $row['id_asistente']) ? 'selected' : ''; ?>><?php echo $row['nombre_completo']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col text-center">
                                    <button type="submit" class="btn btn-primary">Actualizar Coordinador</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
