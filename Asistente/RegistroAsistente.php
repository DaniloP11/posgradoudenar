<?php
include "../complementos/conexion.php";

if (!empty($_POST)) {
    // Validación de campos obligatorios
    if (empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['correo']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['genero']) || empty($_POST['fecha_nacimiento']) || empty($_POST['contraseña']) || empty($_POST['id_programa']) || empty($_POST['id_coordinador'])) {
        ?>
        <script>
        alert("Todos los campos obligatorios deben estar llenos");
        </script>
        <?php
    } else {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $genero = $_POST['genero'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $contraseña = $_POST['contraseña'];
        $id_programa = $_POST['id_programa'];
        $id_coordinador = $_POST['id_coordinador'];

        // Verificar coincidencia de contraseñas
        if ($_POST['contraseña'] != $_POST['confcontraseña']) {
            ?>
            <script>
            alert("La contraseña no coincide");
            </script>
            <?php
        } else {
            // Verificar si el correo ya existe
            $query = mysqli_query(conexion(), "SELECT correo FROM asistentes WHERE correo = '$correo'");
            $result = mysqli_fetch_array($query);
            if ($result) {
                ?>
                <script>
                alert("El correo ya existe");
                </script>
                <?php
            } else {
                // Manejo de archivo de fotografía
                $fotografia = NULL;
                if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] == 0) {
                    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                    $max_size = 2 * 1024 * 1024; // 2MB
                    $upload_dir = '../Asistente/uploads/';
                    $upload_file = $upload_dir . basename($_FILES['fotografia']['name']);

                    // Verifica el tipo de archivo
                    if (!in_array($_FILES['fotografia']['type'], $allowed_types)) {
                        $error = 'Tipo de archivo no permitido.';
                    }
                    // Verifica el tamaño del archivo
                    elseif ($_FILES['fotografia']['size'] > $max_size) {
                        $error = 'El archivo es demasiado grande.';
                    } else {
                        // Verifica que el directorio exista
                        if (!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0755, true);
                        }
                        // Intenta mover el archivo
                        if (move_uploaded_file($_FILES['fotografia']['tmp_name'], $upload_file)) {
                            $fotografia = $_FILES['fotografia']['name'];
                        } else {
                            $error = 'Error al mover el archivo. Verifica los permisos y la ruta.';
                        }
                    }
                } else {
                    $error = 'Error en la carga del archivo: ' . $_FILES['fotografia']['error'];
                }

                // Inserción en la base de datos
                if (!isset($error)) {
                    $query_insert_asistente = mysqli_query(conexion(), "INSERT INTO asistentes (nombre, apellido, correo, telefono, direccion, genero, fecha_nacimiento, contraseña, fotografia, id_programa, id_coordinador)
                        VALUES ('$nombre', '$apellido', '$correo', '$telefono', '$direccion', '$genero', '$fecha_nacimiento', '$contraseña', '$fotografia', '$id_programa', '$id_coordinador')");

                    if ($query_insert_asistente) {
                        ?>
                        <script>
                        alert("Asistente creado correctamente");
                        </script>
                        <?php
                    } else {
                        ?>
                        <script>
                        alert("Error al crear el asistente");
                        </script>
                        <?php
                    }
                } else {
                    ?>
                    <script>
                    alert("<?php echo $error; ?>");
                    </script>
                    <?php
                }
            }
        }
    }
}

// Cargar programas para el dropdown
$programas_query = mysqli_query(conexion(), "SELECT id_programa, nombre_programa FROM programas");

// Cargar coordinadores para el dropdown
$coordinadores_query = mysqli_query(conexion(), "SELECT id_coordinador, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM coordinadores");

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registro de Asistentes</title>
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
        <h3 align="center">REGISTRO DE ASISTENTES</h3>
        <div class="row">
            <div class="col py-5">
                <div class="mx-5 bg-light " style="border-radius: 2%; ">
                    <form action="" method="post" enctype="multipart/form-data">
                    <div class="row mb-3 needs-validation" novalidate>
                        <div class="col mx-5 px-5">
                            <div class="row mb-3">
                            <div class="col">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre">
                            </div>
                            <div class="col">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido">
                            </div>
                            </div>
                            <div class="row mb-3">
                            <div class="col">
                                <label for="correo" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="correo" name="correo">
                            </div>
                            <div class="col">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono">
                            </div>
                            </div>
                            <div class="row mb-3">
                            <div class="col">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion">
                            </div>
                            <div class="col">
                                <label for="genero" class="form-label">Género</label>
                                <select id="genero" name="genero" class="form-select">
                                <option selected>Selecciona...</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                                </select>
                            </div>
                            </div>
                            <div class="row mb-3">
                            <div class="col">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                            </div>
                            <div class="col">
                                <label for="contraseña" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="contraseña" name="contraseña">
                            </div>
                            </div>
                            <div class="row mb-3">
                            <div class="col">
                                <label for="confcontraseña" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="confcontraseña" name="confcontraseña">
                            </div>
                            <div class="col">
                                <label for="fotografia" class="form-label">Fotografía</label>
                                <input type="file" class="form-control" id="fotografia" name="fotografia">
                            </div>
                            </div>
                            <div class="row mb-3">
                            <div class="col">
                                <label for="id_programa" class="form-label">Programa</label>
                                <select id="id_programa" name="id_programa" class="form-select">
                                <?php while ($row = mysqli_fetch_assoc($programas_query)): ?>
                                    <option value="<?php echo $row['id_programa']; ?>"><?php echo $row['nombre_programa']; ?></option>
                                <?php endwhile; ?>
                                </select>
                            </div>
                            </div>
                            <div class="col">
                                <label for="id_coordinador" class="form-label">Coordinador</label>
                                <select id="id_coordinador" name="id_coordinador" class="form-select">
                                    <?php while ($row = mysqli_fetch_assoc($coordinadores_query)): ?>
                                        <option value="<?php echo $row['id_coordinador']; ?>"><?php echo $row['nombre_completo']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>                             
                            <div class="row mb-3">
                            <div class="col">
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </div>
                            </div>
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
