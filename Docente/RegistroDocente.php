<?php
include "../complementos/conexion.php";
session_start();

// Verificar que el usuario está logueado
if (!isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: ../index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que todos los campos obligatorios están llenos
    $requiredFields = ['id_docente', 'nombre', 'apellido', 'correo', 'telefono', 'direccion', 'formacion_pregrado', 'formacion_posgrado', 'areas_conocimiento'];
    $missingFields = array_filter($requiredFields, fn($field) => empty($_POST[$field]));

    if ($missingFields) {
        echo "<script>alert('Todos los campos obligatorios deben estar llenos');</script>";
    } else {
        $id_docente = $_POST['id_docente'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $formacion_pregrado = $_POST['formacion_pregrado'];
        $formacion_posgrado = $_POST['formacion_posgrado'];
        $areas_conocimiento = $_POST['areas_conocimiento'];

        // Manejo de la fotografía
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            
            // Verificar si la carpeta 'uploads' existe, si no, crearla
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);  // Crear el directorio con permisos adecuados
            }
        
            // Nombre único para la imagen para evitar sobreescrituras
            $fotoNombre = basename($_FILES['foto']['name']);
            $fotoRuta = $uploadDir . $fotoNombre;
        
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $fotoRuta)) {
                $foto = $fotoRuta;  // Guardar la ruta del archivo subido en la base de datos
            } else {
                echo "<script>alert('Error al subir la imagen');</script>";
            }
        }
        

        // Conexión a la base de datos
        $conexion = conexion();
        if (!$conexion) {
            echo "<script>alert('Error de conexión a la base de datos');</script>";
            exit();
        }

        // Verificar si el correo ya existe
        $stmt = mysqli_prepare($conexion, "SELECT correo FROM docentes WHERE correo = ?");
        mysqli_stmt_bind_param($stmt, 's', $correo);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            echo "<script>alert('El correo ya existe');</script>";
        } else {
            // Insertar nuevo docente
            $stmt = mysqli_prepare($conexion, "INSERT INTO docentes (id_docente, nombre, apellido, correo, telefono, direccion, foto, formacion_pregrado, formacion_posgrado, areas_conocimiento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'ssssssssss', $id_docente, $nombre, $apellido, $correo, $telefono, $direccion, $foto, $formacion_pregrado, $formacion_posgrado, $areas_conocimiento);

            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Docente creado correctamente');</script>";
            } else {
                echo "<script>alert('Error al crear el docente');</script>";
            }
        }

        // Cerrar la declaración y la conexión
        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Docentes</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.png">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

<div class="form_registro">
    <hr><br><br>
    <div class="container px-4">
        <h3 align="center">REGISTRO DE DOCENTES</h3>
        <div class="row">
            <div class="col py-5">
                <div class="mx-5 bg-light" style="border-radius: 2%;">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row mb-3 needs-validation" novalidate>
                            <div class="col mx-5 px-5">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="id_docente" class="form-label">ID Docente</label>
                                        <input type="text" class="form-control" id="id_docente" name="id_docente">
                                    </div>
                                </div>
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
                                        <input type="text" class="form-control" id="telefono" name="telefono">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="direccion" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="foto" class="form-label">Foto</label>
                                        <input type="file" class="form-control" id="foto" name="foto">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="formacion_pregrado" class="form-label">Formación de Pregrado</label>
                                        <input type="text" class="form-control" id="formacion_pregrado" name="formacion_pregrado">
                                    </div>
                                    <div class="col">
                                        <label for="formacion_posgrado" class="form-label">Formación de Posgrado</label>
                                        <input type="text" class="form-control" id="formacion_posgrado" name="formacion_posgrado">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="areas_conocimiento" class="form-label">Áreas de Conocimiento</label>
                                        <input type="text" class="form-control" id="areas_conocimiento" name="areas_conocimiento">
                                    </div>
                                </div>
                                <!-- Selección del programa -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="id_programa" class="form-label">Programa</label>
                                        <select class="form-control" id="id_programa" name="id_programa">
                                            <option value="">Seleccionar programa</option>
                                            <?php
                                            $conexion = conexion();
                                            $result = mysqli_query($conexion, "SELECT id_programa, nombre_programa FROM programas");
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<option value='{$row['id_programa']}'>{$row['nombre_programa']}</option>";
                                            }
                                            mysqli_close($conexion);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Registrar Docente</button>
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
