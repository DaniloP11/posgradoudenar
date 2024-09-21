<?php
include "../complementos/conexion.php";
session_start();

// Verificar que el usuario está logueado
if (!isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: ../index.html");
    exit();
}

// Manejo del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que todos los campos obligatorios están llenos
    $requiredFields = [
        'nombre', 'identificacion', 'codigo_estudiantil', 'correo', 
        'telefono', 'direccion', 'genero', 'fecha_nacimiento', 'semestre', 
        'estado_civil', 'id_cohorte', 'fecha_ingreso', 'fecha_egreso', 'id_programa'
    ];

    $allFieldsFilled = array_reduce($requiredFields, function($carry, $field) {
        return $carry && !empty($_POST[$field]);
    }, true);

    // Verificar si el archivo fotografía ha sido subido correctamente
    if (empty($_FILES['fotografia']['name'])) {
        $allFieldsFilled = false;
    }

    if (!$allFieldsFilled) {
        echo "<script>alert('Todos los campos obligatorios deben estar llenos');</script>";
    } else {
        $nombre = $_POST['nombre'];
        $identificacion = $_POST['identificacion'];
        $codigo_estudiantil = $_POST['codigo_estudiantil'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $genero = $_POST['genero'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $semestre = $_POST['semestre'];
        $estado_civil = $_POST['estado_civil'];
        $id_cohorte = $_POST['id_cohorte'];
        $fecha_ingreso = $_POST['fecha_ingreso'];
        $fecha_egreso = $_POST['fecha_egreso'];
        $id_programa = $_POST['id_programa'];

        // Manejo de la fotografía
        $fotografia = $_FILES['fotografia']['name'];
        $target_dir = "uploads/"; // Asegúrate de que esta carpeta exista
        $target_file = $target_dir . basename($fotografia);
        $uploadOk = 1;

        // Verificar si el archivo es una imagen
        $check = getimagesize($_FILES['fotografia']['tmp_name']);
        if ($check === false) {
            echo "<script>alert('El archivo no es una imagen.');</script>";
            $uploadOk = 0;
        }

        // Verificar si el archivo ya existe
        if (file_exists($target_file)) {
            echo "<script>alert('Lo siento, el archivo ya existe.');</script>";
            $uploadOk = 0;
        }

        // Verificar el tamaño del archivo
        if ($_FILES['fotografia']['size'] > 500000) {
            echo "<script>alert('Lo siento, el archivo es demasiado grande.');</script>";
            $uploadOk = 0;
        }

        // Permitir ciertos formatos de archivo
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo "<script>alert('Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.');</script>";
            $uploadOk = 0;
        }

        // Si todo está bien, intenta subir el archivo
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES['fotografia']['tmp_name'], $target_file)) {
                // Conexión a la base de datos
                $conexion = conexion();
                if (!$conexion) {
                    echo "<script>alert('Error de conexión a la base de datos');</script>";
                    exit();
                }

                // Verificar si el correo ya existe
                $stmt = mysqli_prepare($conexion, "SELECT correo FROM estudiantes WHERE correo = ?");
                mysqli_stmt_bind_param($stmt, 's', $correo);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) > 0) {
                    echo "<script>alert('El correo ya existe');</script>";
                } else {
                    // Insertar nuevo estudiante sin `id_estudiante` (auto-incremental)
                    $stmt = mysqli_prepare($conexion, "INSERT INTO estudiantes (nombre, identificacion, codigo_estudiantil, correo, telefono, direccion, genero, fecha_nacimiento, semestre, estado_civil, id_cohorte, fotografia, fecha_ingreso, fecha_egreso, id_programa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt, 'sssssssssssssss', $nombre, $identificacion, $codigo_estudiantil, $correo, $telefono, $direccion, $genero, $fecha_nacimiento, $semestre, $estado_civil, $id_cohorte, $fotografia, $fecha_ingreso, $fecha_egreso, $id_programa);

                    if (mysqli_stmt_execute($stmt)) {
                        echo "<script>alert('Estudiante creado correctamente');</script>";
                    } else {
                        echo "<script>alert('Error al crear el estudiante');</script>";
                    }
                }

                // Cerrar la declaración y la conexión
                mysqli_stmt_close($stmt);
                mysqli_close($conexion);
            } else {
                echo "<script>alert('Lo siento, hubo un error al subir tu archivo.');</script>";
            }
        }
    }
}

// Cargar cohortes y programas para el dropdown
$conexion = conexion();
if (!$conexion) {
    die("Error de conexión a la base de datos");
}

// Obtener id_programa del usuario
$id_programa_usuario = $_SESSION["id_programa"];

// Consultas según rol
if ($_SESSION["rol"] == '1') { // Administrador
    $cohortes_query = mysqli_query($conexion, "SELECT id_cohorte, nombre FROM cohortes");
    $programas_query = mysqli_query($conexion, "SELECT id_programa, descripcion FROM programas");
} else { // Asistentes y Coordinadores
    $cohortes_query = mysqli_query($conexion, "SELECT id_cohorte, nombre FROM cohortes WHERE id_programa = $id_programa_usuario");
    $programas_query = mysqli_query($conexion, "SELECT id_programa, descripcion FROM programas WHERE id_programa = $id_programa_usuario");
}

mysqli_close($conexion);
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Estudiantes</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
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

<div class="form_registro">
    <hr><br><br>
    <div class="container px-4">
        <h3 align="center">REGISTRO DE ESTUDIANTES</h3>
        <div class="row">
            <div class="col py-5">
                <div class="mx-5 bg-light" style="border-radius: 2%; ">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row mb-3 needs-validation" novalidate>
                            <div class="col mx-5 px-5">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    </div> 
                                    <div class="col">
                                        <label for="identificacion" class="form-label">Identificación</label>
                                        <input type="text" class="form-control" id="identificacion" name="identificacion" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="codigo_estudiantil" class="form-label">Código Estudiantil</label>
                                        <input type="text" class="form-control" id="codigo_estudiantil" name="codigo_estudiantil" required>
                                    </div>
                                    <div class="col">
                                        <label for="correo" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="correo" name="correo" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" required>
                                    </div>
                                    <div class="col">
                                        <label for="direccion" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="genero" class="form-label">Género</label>
                                        <select class="form-select" id="genero" name="genero" required>
                                            <option value="">Seleccione un género</option>
                                            <option value="Masculino">Masculino</option>
                                            <option value="Femenino">Femenino</option>
                                            <option value="Otro">Otro</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="estado_civil" class="form-label">Estado Civil</label>
                                        <select class="form-select" id="estado_civil" name="estado_civil" required>
                                            <option value="">Seleccione un estado civil</option>
                                            <option value="Soltero">Soltero</option>
                                            <option value="Casado">Casado</option>
                                            <option value="Divorciado">Divorciado</option>
                                            <option value="Viudo">Viudo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                                    </div>
                                    <div class="col">
                                        <label for="semestre" class="form-label">Semestre</label>
                                        <input type="number" class="form-control" id="semestre" name="semestre" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                                        <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
                                    </div>
                                    <div class="col">
                                        <label for="fecha_egreso" class="form-label">Fecha de Egreso</label>
                                        <input type="date" class="form-control" id="fecha_egreso" name="fecha_egreso" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="id_cohorte" class="form-label">Cohorte</label>
                                        <select class="form-select" id="id_cohorte" name="id_cohorte" required>
                                            <option value="">Seleccione una cohorte</option>
                                            <?php while ($row = mysqli_fetch_assoc($cohortes_query)) {
                                                echo "<option value='{$row['id_cohorte']}'>{$row['nombre']}</option>"; // Modificado
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="id_programa" class="form-label">Programa</label>
                                        <select class="form-select" id="id_programa" name="id_programa" required>
                                            <option value="">Seleccione un programa</option>
                                            <?php while ($row = mysqli_fetch_assoc($programas_query)) {
                                                echo "<option value='{$row['id_programa']}'>{$row['descripcion']}</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="fotografia" class="form-label">Fotografía</label>
                                        <input type="file" class="form-control" id="fotografia" name="fotografia" accept="image/*" required>
                                    </div>
                                </div>
                                <br>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-primary" type="submit">Registrar</button>
                                    <button id="cancelar-btn" class="btn btn-secondary" type="button">Cancelar</button>
                                </div>
                                <br><br>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('cancelar-btn').addEventListener('click', function() {
    // Obtén el rol del usuario desde una variable PHP insertada en JavaScript
    var rol = "<?php echo $_SESSION['rol']; ?>";

    var url = '';
    switch (rol) {
        case '1':
            url = '../Admin/UsuariosAdmin.html';
            break;
        case '2':
            url = '../Asistente/UsuariosAsiste.html';
            break;
        case '3':
            url = '../Coordinador/UsuariosCoord.html';
            break;
        default:
            url = '../index.html'; // Redirigir a una página predeterminada si no se encuentra el rol
            break;
    }

    window.location.href = url;
});
</script>

</body>
</html>
