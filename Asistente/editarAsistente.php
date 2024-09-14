<?php
include "../complementos/conexion.php";

// Verificar si se ha pasado un ID de asistente
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_asistente = intval($_GET['id']);
    
    // Consultar los datos del asistente
    $query = mysqli_query(conexion(), "SELECT * FROM asistentes WHERE id_asistente = $id_asistente");
    $asistente = mysqli_fetch_assoc($query);

    // Si el asistente no existe, redirigir o mostrar error
    if (!$asistente) {
        echo "<script>alert('Asistente no encontrado'); window.location.href='listarAsistente.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID de asistente no proporcionado'); window.location.href='listarAsistente.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validación de campos obligatorios
    if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['contraseña']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['genero']) || empty($_POST['fecha_nacimiento'])) {
        echo "<script>alert('Todos los campos obligatorios deben estar llenos');</script>";
    } else {
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Encriptar contraseña
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $genero = $_POST['genero'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];

        // Manejo de archivo de fotografía
        $fotografia = $asistente['fotografia'];
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
        }

        // Actualización en la base de datos
        if (!isset($error)) {
            $query_update_asistente = mysqli_query(conexion(), "UPDATE asistentes SET nombre='$nombre', correo='$correo', contraseña='$contraseña', telefono='$telefono', direccion='$direccion', genero='$genero', fecha_nacimiento='$fecha_nacimiento', fotografia='$fotografia' WHERE id_asistente=$id_asistente");

            if ($query_update_asistente) {
                echo "<script>alert('Asistente actualizado correctamente'); window.location.href='listarAsistente.php';</script>";
            } else {
                echo "<script>alert('Error al actualizar el asistente');</script>";
            }
        } else {
            echo "<script>alert('$error');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Asistente</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.png">
    <link rel="stylesheet" href="../css/style.css">
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
        <h3 align="center">EDITAR ASISTENTE</h3>
        <div class="row">
            <div class="col py-5">
                <div class="mx-5 bg-light" style="border-radius: 2%;">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row mb-3 needs-validation" novalidate>
                            <div class="col mx-5 px-5">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($asistente['nombre']); ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="correo" class="form-label">Correo</label>
                                        <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($asistente['correo']); ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="contraseña" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($asistente['telefono']); ?>">
                                    </div>
                                    <div class="col">
                                        <label for="direccion" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($asistente['direccion']); ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="genero" class="form-label">Género</label>
                                        <select id="genero" name="genero" class="form-select">
                                            <option <?php if ($asistente['genero'] == 'Masculino') echo 'selected'; ?> value="Masculino">Masculino</option>
                                            <option <?php if ($asistente['genero'] == 'Femenino') echo 'selected'; ?> value="Femenino">Femenino</option>
                                            <option <?php if ($asistente['genero'] == 'Otro') echo 'selected'; ?> value="Otro">Otro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($asistente['fecha_nacimiento']); ?>">
                                    </div>
                                    <div class="col">
                                        <label for="fotografia" class="form-label">Fotografía</label>
                                        <input type="file" class="form-control" id="fotografia" name="fotografia">
                                        <?php if ($asistente['fotografia']): ?>
                                            <img src="../Asistente/uploads/<?php echo htmlspecialchars($asistente['fotografia']); ?>" alt="Fotografía Actual" width="100">
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <br>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-primary" type="submit">Actualizar</button>
                                    <a href="listarAsistente.php" class="btn btn-secondary">Cancelar</a>
                                </div>
                                <br><br>
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
