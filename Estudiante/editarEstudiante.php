<?php
include "../complementos/conexion.php";
session_start();

// Verificar que el usuario está logueado
if (!isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: ../index.html");
    exit();
}

// Mostrar mensaje de éxito si existe
if (!empty($_SESSION['mensaje'])) {
    echo "<script>alert('" . htmlspecialchars($_SESSION['mensaje']) . "');</script>";
    $_SESSION['mensaje'] = ''; // Limpiar el mensaje después de mostrarlo
}

// Obtener ID del estudiante a editar
$id_estudiante = $_GET['id'] ?? null;

if ($id_estudiante === null) {
    die("ID de estudiante no proporcionado");
}

$conexion = conexion();
if (!$conexion) {
    die("Error de conexión a la base de datos");
}

// Consultar datos del estudiante
$id_programa = $_SESSION['id_programa'];
$query = ($_SESSION['rol'] == '1') 
    ? "SELECT * FROM estudiantes WHERE id_estudiante = ?" 
    : "SELECT * FROM estudiantes WHERE id_estudiante = ? AND id_programa = ?";
$stmt = mysqli_prepare($conexion, $query);
if ($_SESSION['rol'] == '1') {
    mysqli_stmt_bind_param($stmt, 'i', $id_estudiante);
} else {
    mysqli_stmt_bind_param($stmt, 'ii', $id_estudiante, $id_programa);
}

mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$estudiante = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if (!$estudiante) {
    die("Estudiante no encontrado o no tienes permiso para ver este registro.");
}

// Procesar la actualización del estudiante
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requiredFields = [
        'nombre', 'codigo_estudiantil', 'correo', 'telefono',
        'direccion', 'genero', 'fecha_nacimiento', 'semestre',
        'estado_civil', 'id_cohorte', 'fecha_ingreso', 'fecha_egreso', 'id_programa'
    ];

    // Validar campos requeridos
    $allFieldsFilled = array_reduce($requiredFields, function ($carry, $field) {
        return $carry && !empty($_POST[$field]);
    }, true);

    // Verificar si se ha subido una nueva fotografía
    if (empty($_FILES['fotografia']['name'])) {
        echo "<script>alert('Se debe subir una nueva fotografía');</script>";
        $allFieldsFilled = false;
    }

    if (!$allFieldsFilled) {
        echo "<script>alert('Todos los campos obligatorios deben estar llenos');</script>";
    } else {
        // Preparar datos para la actualización
        $nombre = $_POST['nombre'];
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
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($fotografia);
        $uploadOk = true;

        // Validaciones para la imagen
        $check = getimagesize($_FILES['fotografia']['tmp_name']);
        if ($check === false) {
            echo "<script>alert('El archivo no es una imagen.');</script>";
            $uploadOk = false;
        } elseif (file_exists($target_file)) {
            echo "<script>alert('Lo siento, el archivo ya existe.');</script>";
            $uploadOk = false;
        } elseif ($_FILES['fotografia']['size'] > 500000) {
            echo "<script>alert('Lo siento, el archivo es demasiado grande.');</script>";
            $uploadOk = false;
        } elseif (!in_array(strtolower(pathinfo($target_file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "<script>alert('Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.');</script>";
            $uploadOk = false;
        }

        // Si todo está bien, intenta subir el archivo
        if ($uploadOk) {
            if (move_uploaded_file($_FILES['fotografia']['tmp_name'], $target_file)) {
                // Actualizar estudiante en la base de datos
                $stmt = mysqli_prepare($conexion, "UPDATE estudiantes SET nombre = ?, codigo_estudiantil = ?, correo = ?, telefono = ?, direccion = ?, genero = ?, fecha_nacimiento = ?, semestre = ?, estado_civil = ?, id_cohorte = ?, fotografia = ?, fecha_ingreso = ?, fecha_egreso = ?, id_programa = ? WHERE id_estudiante = ?");
                mysqli_stmt_bind_param($stmt, 'sssssssssssssii', $nombre, $codigo_estudiantil, $correo, $telefono, $direccion, $genero, $fecha_nacimiento, $semestre, $estado_civil, $id_cohorte, $fotografia, $fecha_ingreso, $fecha_egreso, $id_programa, $id_estudiante);

                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['mensaje'] = 'Datos actualizados exitosamente';
                    header("Location: listarEstudiante.php");
                    exit();
                } else {
                    echo "<script>alert('Error al actualizar el estudiante');</script>";
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "<script>alert('Lo siento, hubo un error al subir tu archivo.');</script>";
            }
        }
    }
}

// Cargar cohortes y programas para los dropdowns
$conexion = conexion();
if (!$conexion) {
    die("Error de conexión a la base de datos");
}

// Consultas según rol
$cohortes_query = ($_SESSION["rol"] == '1')
    ? mysqli_query($conexion, "SELECT id_cohorte, nombre FROM cohortes")
    : mysqli_query($conexion, "SELECT id_cohorte, nombre FROM cohortes WHERE id_programa = $id_programa");

$programas_query = mysqli_query($conexion, "SELECT id_programa, descripcion FROM programas" . 
    ($_SESSION["rol"] != '1' ? " WHERE id_programa = $id_programa" : ""));
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Estudiante</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
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
        <h3 align="center">EDITAR ESTUDIANTE</h3>
        <div class="row">
            <div class="col py-5">
                <div class="mx-5 bg-light" style="border-radius: 2%;">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row mb-3 needs-validation" novalidate>
                            <div class="col mx-5 px-5">
                                <!-- Formulario con los valores prellenados del estudiante -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($estudiante['nombre']); ?>" required>
                                    </div>
                                    <div class="col">
                                        <label for="codigo_estudiantil" class="form-label">Código Estudiantil</label>
                                        <input type="text" class="form-control" id="codigo_estudiantil" name="codigo_estudiantil" value="<?php echo htmlspecialchars($estudiante['codigo_estudiantil']); ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    
                                    <div class="col">
                                        <label for="correo" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($estudiante['correo']); ?>" required>
                                    </div>
                                    <div class="col">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($estudiante['telefono']); ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    
                                    <div class="col">
                                        <label for="direccion" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($estudiante['direccion']); ?>" required>
                                    </div>
                                    <div class="col">
                                        <label for="genero" class="form-label">Género</label>
                                        <select class="form-select" id="genero" name="genero" required>
                                            <option value="">Seleccione un género</option>
                                            <option value="Masculino" <?php echo ($estudiante['genero'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                                            <option value="Femenino" <?php echo ($estudiante['genero'] == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                                            <option value="Otro" <?php echo ($estudiante['genero'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    
                                    <div class="col">
                                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($estudiante['fecha_nacimiento']); ?>" required>
                                    </div>
                                    <div class="col">
                                        <label for="semestre" class="form-label">Semestre</label>
                                        <input type="number" class="form-control" id="semestre" name="semestre" value="<?php echo htmlspecialchars($estudiante['semestre']); ?>" required>
                                    </div>
                                    
                                </div> 
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                                        <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" value="<?php echo htmlspecialchars($estudiante['fecha_ingreso']); ?>" required>
                                    </div>
                                    <div class="col">
                                        <label for="fecha_egreso" class="form-label">Fecha de Egreso</label>
                                        <input type="date" class="form-control" id="fecha_egreso" name="fecha_egreso" value="<?php echo htmlspecialchars($estudiante['fecha_egreso']); ?>" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                        <div class="col">
                                            <label for="estado_civil" class="form-label">Estado Civil</label>
                                            <select class="form-select" id="estado_civil" name="estado_civil" required>
                                                <option value="">Seleccione un estado civil</option>
                                                <option value="Soltero" <?php echo ($estudiante['estado_civil'] == 'Soltero') ? 'selected' : ''; ?>>Soltero</option>
                                                <option value="Casado" <?php echo ($estudiante['estado_civil'] == 'Casado') ? 'selected' : ''; ?>>Casado</option>
                                                <option value="Divorciado" <?php echo ($estudiante['estado_civil'] == 'Divorciado') ? 'selected' : ''; ?>>Divorciado</option>
                                                <option value="Viudo" <?php echo ($estudiante['estado_civil'] == 'Viudo') ? 'selected' : ''; ?>>Viudo</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="id_cohorte" class="form-label">Cohorte</label>
                                            <select class="form-select" id="id_cohorte" name="id_cohorte" required>
                                                <option value="">Seleccione una cohorte</option>
                                                <?php while ($row = mysqli_fetch_assoc($cohortes_query)) {
                                                    echo "<option value='{$row['id_cohorte']}'" . ($estudiante['id_cohorte'] == $row['id_cohorte'] ? ' selected' : '') . ">{$row['nombre']}</option>";
                                                } ?>
                                            </select>
                                        </div>
                                </div>
            

                                <div class="row mb-3">
                                    <div class="col">
                                            <label for="id_programa" class="form-label">Programa</label>
                                            <select class="form-select" id="id_programa" name="id_programa" required>
                                                <option value="">Seleccione un programa</option>
                                                <?php while ($row = mysqli_fetch_assoc($programas_query)) {
                                                    echo "<option value='{$row['id_programa']}'" . ($estudiante['id_programa'] == $row['id_programa'] ? ' selected' : '') . ">{$row['descripcion']}</option>";
                                                } ?>
                                            </select>
                                    </div>
                                    <div class="col">
                                        <label for="fotografia" class="form-label">Fotografía</label>
                                        <input type="file" class="form-control" id="fotografia" name="fotografia" accept="image/*">
                                        <p><br><img src="uploads/<?php echo htmlspecialchars($estudiante['fotografia']); ?>" alt="Fotografía" style="width: 60px; height: auto;"></p>
                                    </div>
                                </div>
                                <br>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-primary" type="submit">Actualizar</button>
                                    <a href="listarEstudiante.php" class="btn btn-secondary">Cancelar</a>
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
