<?php
include "../complementos/conexion.php";
session_start();

// Verificar que el usuario está logueado
if (!isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: ../index.html");
    exit();
}

// Verificar si se ha proporcionado un ID de docente en la URL
if (!isset($_GET['id'])) {
    echo "<script>alert('ID de docente no proporcionado.');</script>";
    exit();
}

$id_docente = $_GET['id'];
$conexion = conexion();
if (!$conexion) {
    die("Error de conexión a la base de datos");
}

// Obtener los datos del docente, incluyendo id_programa y id_cohorte
$sql = "SELECT d.id_docente, d.nombre, d.identificacion, d.direccion, d.telefono, d.correo, d.foto, 
               d.formacion_pregrado, d.formacion_posgrado, d.areas_conocimiento, 
               d.id_programa, d.id_cohorte, 
               p.descripcion AS programa, c.nombre AS cohorte 
        FROM docentes d 
        LEFT JOIN programas p ON d.id_programa = p.id_programa 
        LEFT JOIN cohortes c ON d.id_cohorte = c.id_cohorte 
        WHERE d.id_docente = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id_docente);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Docente no encontrado.');</script>";
    exit();
}

$docente = mysqli_fetch_assoc($result);

// Manejo de la actualización de datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $identificacion = $_POST['identificacion'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $formacion_pregrado = $_POST['formacion_pregrado'];
    $formacion_posgrado = $_POST['formacion_posgrado'];
    $areas_conocimiento = $_POST['areas_conocimiento'];
    $id_programa = $_POST['id_programa'];
    $id_cohorte = $_POST['id_cohorte'];

    // Manejo de la fotografía
    $foto = $docente['foto']; // Mantener la foto existente por defecto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fotoNombre = basename($_FILES['foto']['name']);
        $fotoRuta = $uploadDir . $fotoNombre;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $fotoRuta)) {
            $foto = $fotoRuta;
        } else {
            echo "<script>alert('Error al subir la imagen');</script>";
        }
    }

    // Actualizar los datos del docente
    $updateSql = "UPDATE docentes SET nombre = ?, identificacion = ?, correo = ?, telefono = ?, direccion = ?, foto = ?, formacion_pregrado = ?, formacion_posgrado = ?, areas_conocimiento = ?, id_programa = ?, id_cohorte = ? WHERE id_docente = ?";
    $stmt = mysqli_prepare($conexion, $updateSql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ssssssssssi', $nombre, $identificacion, $correo, $telefono, $direccion, $foto, $formacion_pregrado, $formacion_posgrado, $areas_conocimiento, $id_programa, $id_cohorte, $id_docente);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Docente actualizado correctamente');</script>";
        } else {
            echo "<script>alert('Error al actualizar el docente');</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error al preparar la consulta');</script>";
    }
}

// Obtener programas y cohortes para los select
$programasSql = "SELECT id_programa, descripcion FROM programas";
$programasResult = mysqli_query($conexion, $programasSql);

$cohortesSql = "SELECT id_cohorte, nombre FROM cohortes";
$cohortesResult = mysqli_query($conexion, $cohortesSql);

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Docente</title>
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
                            <li class="nav-item"><a class="nav-link active text-white" href="../Admin/InicioAdmi.php">Inicio</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="../Admin/UsuariosAdmin.html">Usuarios</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="../Admin/misdatos.php">Mis datos</a></li>
                        <?php elseif ($_SESSION['rol'] == '2'): ?>
                            <li class="nav-item"><a class="nav-link active text-white" href="../Asistente/InicioAsiste.php">Inicio</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="../Asistente/UsuariosAsiste.html">Usuarios</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="../Asistente/misdatos.php">Mis datos</a></li>
                        <?php elseif ($_SESSION['rol'] == '3'): ?>
                            <li class="nav-item"><a class="nav-link active text-white" href="../Coordinador/InicioCoord.php">Inicio</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="../Coordinador/UsuariosCoord.html">Usuarios</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="../Coordinador/misdatos.php">Mis datos</a></li>
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
        <h3 align="center">EDITAR DOCENTE</h3>
        <div class="row">
            <div class="col py-5">
                <div class="mx-5 bg-light" style="border-radius: 2%;">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row mb-3 needs-validation" novalidate>
                            <div class="col mx-5 px-5">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($docente['nombre']); ?>">
                                    </div>
                                    <div class="col">
                                        <label for="identificacion" class="form-label">Identificación</label>
                                        <input type="text" class="form-control" id="identificacion" name="identificacion" value="<?php echo htmlspecialchars($docente['identificacion']); ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="correo" class="form-label">Correo</label>
                                        <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($docente['correo']); ?>">
                                    </div>
                                    <div class="col">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($docente['telefono']); ?>">
                                    </div>
                                    <div class="col">
                                        <label for="direccion" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($docente['direccion']); ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="formacion_pregrado" class="form-label">Formación de Pregrado</label>
                                        <input type="text" class="form-control" id="formacion_pregrado" name="formacion_pregrado" value="<?php echo htmlspecialchars($docente['formacion_pregrado']); ?>">
                                    </div>
                                    <div class="col">
                                        <label for="formacion_posgrado" class="form-label">Formación de Posgrado</label>
                                        <input type="text" class="form-control" id="formacion_posgrado" name="formacion_posgrado" value="<?php echo htmlspecialchars($docente['formacion_posgrado']); ?>">
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="id_programa" class="form-label">Programa</label>
                                        <select class="form-select" id="id_programa" name="id_programa" required>
                                            <option value="">Seleccione un programa</option>
                                            <?php while ($programa = mysqli_fetch_assoc($programasResult)): ?>
                                                <option value="<?php echo $programa['id_programa']; ?>" <?php echo $programa['id_programa'] == $docente['id_programa'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($programa['descripcion']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="id_cohorte" class="form-label">Cohorte</label>
                                        <select class="form-select" id="id_cohorte" name="id_cohorte" required>
                                            <option value="">Seleccione una cohorte</option>
                                            <?php while ($cohorte = mysqli_fetch_assoc($cohortesResult)): ?>
                                                <option value="<?php echo $cohorte['id_cohorte']; ?>" <?php echo $cohorte['id_cohorte'] == $docente['id_cohorte'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($cohorte['nombre']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="areas_conocimiento" class="form-label">Áreas de Conocimiento</label>
                                        <input type="text" class="form-control" id="areas_conocimiento" name="areas_conocimiento" value="<?php echo htmlspecialchars($docente['areas_conocimiento']); ?>">
                                    </div>
                                    <div class="col">
                                        <label for="foto" class="form-label">Foto</label>
                                        <input type="file" class="form-control" id="foto" name="foto">
                                        <?php if ($docente['foto']): ?>
                                            <img src="<?php echo htmlspecialchars($docente['foto']); ?>" alt="Foto del docente" class="img-thumbnail mt-2" width="150">
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <br>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-primary" type="submit">Actualizar</button>
                                    <a href="listarDocente.php" class="btn btn-secondary">Cancelar</a>
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
