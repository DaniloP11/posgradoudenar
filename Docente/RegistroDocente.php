<?php
include "../complementos/conexion.php";
session_start();

// Verificar que el usuario está logueado
if (!isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: ../index.html");
    exit();
}

$programas = [];
$cohortes = [];

// Obtener el id_programa del usuario
$id_programa_usuario = $_SESSION["id_programa"];

// Conexión a la base de datos
$conexion = conexion();
if (!$conexion) {
    die("Error de conexión a la base de datos");
}

// Consultas según rol
if ($_SESSION["rol"] == '1') { // Administrador
    $programas_query = mysqli_query($conexion, "SELECT id_programa, descripcion FROM programas");
    $cohortes_query = mysqli_query($conexion, "SELECT id_cohorte, nombre FROM cohortes");
} else { // Asistentes y Coordinadores
    $programas_query = mysqli_query($conexion, "SELECT id_programa, descripcion FROM programas WHERE id_programa = $id_programa_usuario");
    $cohortes_query = mysqli_query($conexion, "SELECT id_cohorte, nombre FROM cohortes WHERE id_programa = $id_programa_usuario");
}

// Cargar programas
while ($row = mysqli_fetch_assoc($programas_query)) {
    $programas[] = $row;
}

// Cargar cohortes
while ($row = mysqli_fetch_assoc($cohortes_query)) {
    $cohortes[] = $row;
}

// Manejo del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que todos los campos obligatorios están llenos
    $requiredFields = ['nombre', 'identificacion', 'direccion', 'telefono', 'correo', 'formacion_pregrado', 'formacion_posgrado', 'areas_conocimiento', 'id_programa', 'id_cohorte'];
    $missingFields = array_filter($requiredFields, fn($field) => empty($_POST[$field]));

    if ($missingFields) {
        echo "<script>alert('Todos los campos obligatorios deben estar llenos');</script>";
    } else {
        $nombre = $_POST['nombre'];
        $identificacion = $_POST['identificacion'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $formacion_pregrado = $_POST['formacion_pregrado'];
        $formacion_posgrado = $_POST['formacion_posgrado'];
        $areas_conocimiento = $_POST['areas_conocimiento'];
        $id_programa = $_POST['id_programa'];
        $id_cohorte = $_POST['id_cohorte'];

        // Manejo de la fotografía
        $foto = null;
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

        // Verificar si el correo ya existe
        $stmt = mysqli_prepare($conexion, "SELECT correo FROM docentes WHERE correo = ?");
        mysqli_stmt_bind_param($stmt, 's', $correo);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            echo "<script>alert('El correo ya existe');</script>";
        } else {
            // Insertar nuevo docente
            $stmt = mysqli_prepare($conexion, "INSERT INTO docentes (nombre, identificacion, direccion, telefono, correo, foto, formacion_pregrado, formacion_posgrado, areas_conocimiento, id_programa, id_cohorte) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'sssssssssss', $nombre, $identificacion, $direccion, $telefono, $correo, $foto, $formacion_pregrado, $formacion_posgrado, $areas_conocimiento, $id_programa, $id_cohorte);

            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Docente creado correctamente');</script>";
            } else {
                echo "<script>alert('Error al crear el docente');</script>";
            }
        }

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
        <h3 align="center">REGISTRO DE DOCENTES</h3>
        <div class="row">
            <div class="col py-5">
                <div class="mx-5 bg-light" style="border-radius: 2%;">
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
                                        <label for="direccion" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" required>
                                    </div>
                                    <div class="col">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="correo" class="form-label">Correo</label>
                                        <input type="email" class="form-control" id="correo" name="correo" required>
                                    </div>
                                    <div class="col">
                                        <label for="foto" class="form-label">Foto</label>
                                        <input type="file" class="form-control" id="foto" name="foto">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="formacion_pregrado" class="form-label">Formación de Pregrado</label>
                                        <input type="text" class="form-control" id="formacion_pregrado" name="formacion_pregrado" required>
                                    </div>
                                    <div class="col">
                                        <label for="formacion_posgrado" class="form-label">Formación de Posgrado</label>
                                        <input type="text" class="form-control" id="formacion_posgrado" name="formacion_posgrado" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="areas_conocimiento" class="form-label">Áreas de Conocimiento</label>
                                        <textarea class="form-control" id="areas_conocimiento" name="areas_conocimiento" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="id_programa" class="form-label">Programa</label>
                                        <select class="form-select" id="id_programa" name="id_programa" required>
                                            <option value="" disabled selected>Seleccione un programa</option>
                                            <?php foreach ($programas as $programa): ?>
                                                <option value="<?php echo htmlspecialchars($programa['id_programa']); ?>">
                                                    <?php echo htmlspecialchars($programa['descripcion']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="id_cohorte" class="form-label">Cohorte</label>
                                        <select class="form-select" id="id_cohorte" name="id_cohorte" required>
                                            <option value="" disabled selected>Seleccione una cohorte</option>
                                            <?php foreach ($cohortes as $cohorte): ?>
                                                <option value="<?php echo htmlspecialchars($cohorte['id_cohorte']); ?>">
                                                    <?php echo htmlspecialchars($cohorte['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
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
