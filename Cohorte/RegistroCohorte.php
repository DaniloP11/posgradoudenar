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
    $requiredFields = ['nombre', 'fecha_inicio', 'fecha_fin', 'id_programa'];
    $missingFields = array_filter($requiredFields, fn($field) => empty($_POST[$field]));

    if ($missingFields) {
        echo "<script>alert('Todos los campos obligatorios deben estar llenos');</script>";
    } else {
        $nombre = $_POST['nombre'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $id_programa = $_POST['id_programa'];

        // Conexión a la base de datos
        $conexion = conexion();
        if (!$conexion) {
            echo "<script>alert('Error de conexión a la base de datos');</script>";
            exit();
        }

        // Insertar nuevo cohorte
        $stmt = mysqli_prepare($conexion, "INSERT INTO cohortes (nombre, fecha_inicio, fecha_fin, id_programa) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sssi', $nombre, $fecha_inicio, $fecha_fin, $id_programa);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Cohorte creado correctamente');</script>";
        } else {
            echo "<script>alert('Error al crear el cohorte');</script>";
        }

        // Cerrar la declaración y la conexión
        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
    }
}

// Obtener la lista de programas
$conexion = conexion();
if (!$conexion) {
    die("Error de conexión a la base de datos");
}

$stmt = mysqli_prepare($conexion, "SELECT id_programa, descripcion FROM programas");
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $id_programa, $descripcion);

$programas = [];
while (mysqli_stmt_fetch($stmt)) {
    $programas[] = ['id_programa' => $id_programa, 'descripcion' => $descripcion];
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Cohortes</title>
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
        <h3 align="center">REGISTRO DE COHORTES</h3>
        <div class="row">
            <div class="col py-5">
                <div class="mx-5 bg-light" style="border-radius: 2%;">
                    <form action="" method="post">
                        <div class="row mb-3 needs-validation" novalidate>
                            <div class="col mx-5 px-5">
                                <!-- Campo para el nombre de la cohorte -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="nombre" class="form-label">Nombre de la Cohorte</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    </div>
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
                                </div>

                                <!-- Campo para la fecha de inicio -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                                    </div>
                                    <!-- Campo para la fecha de fin -->
                                    <div class="col">
                                        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                                    </div>
                                </div>


                                <!-- Botones para enviar o cancelar el formulario -->
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
