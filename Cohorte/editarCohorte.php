<?php
include "../complementos/conexion.php";
session_start();

// Verificar que el usuario está logueado
if (!isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: ../index.html");
    exit();
}

// Obtener el ID del cohorte desde la URL
$id_cohorte = $_GET['id'] ?? null;
if (!$id_cohorte) {
    die("ID de cohorte no proporcionado");
}

// Manejo del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que todos los campos obligatorios están llenos
    $requiredFields = ['fecha_inicio', 'fecha_fin', 'id_programa'];
    $missingFields = array_filter($requiredFields, fn($field) => empty($_POST[$field]));

    if ($missingFields) {
        echo "<script>alert('Todos los campos obligatorios deben estar llenos');</script>";
    } else {
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $id_programa = $_POST['id_programa'];

        // Conexión a la base de datos
        $conexion = conexion();
        if (!$conexion) {
            echo "<script>alert('Error de conexión a la base de datos');</script>";
            exit();
        }

        // Actualizar cohorte
        $stmt = mysqli_prepare($conexion, "UPDATE cohortes SET fecha_inicio = ?, fecha_fin = ?, id_programa = ? WHERE id_cohorte = ?");
        mysqli_stmt_bind_param($stmt, 'ssii', $fecha_inicio, $fecha_fin, $id_programa, $id_cohorte);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Cohorte actualizado correctamente');</script>";
        } else {
            echo "<script>alert('Error al actualizar el cohorte');</script>";
        }

        // Cerrar la declaración y la conexión
        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
    }
}

// Obtener la información del cohorte a editar
$conexion = conexion();
if (!$conexion) {
    die("Error de conexión a la base de datos");
}

$stmt = mysqli_prepare($conexion, "SELECT fecha_inicio, fecha_fin, id_programa FROM cohortes WHERE id_cohorte = ?");
mysqli_stmt_bind_param($stmt, 'i', $id_cohorte);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $fecha_inicio, $fecha_fin, $id_programa);
mysqli_stmt_fetch($stmt);

mysqli_stmt_close($stmt);

// Obtener la lista de programas
$stmt = mysqli_prepare($conexion, "SELECT id_programa, descripcion FROM programas");
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $programa_id, $descripcion);

$programas = [];
while (mysqli_stmt_fetch($stmt)) {
    $programas[] = ['id_programa' => $programa_id, 'descripcion' => $descripcion];
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Cohorte</title>
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
        <h3 align="center">EDITAR COHORTE</h3>
        <div class="row">
            <div class="col py-5">
                <div class="mx-5 bg-light" style="border-radius: 2%;">
                    <form action="" method="post">
                        <div class="row mb-3 needs-validation" novalidate>
                            <div class="col mx-5 px-5">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>" required>
                                    </div>
                                    <div class="col">
                                        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="id_programa" class="form-label">Programa</label>
                                        <select id="id_programa" name="id_programa" class="form-select" required>
                                            <option value="">Seleccionar Programa</option>
                                            <?php foreach ($programas as $programa): ?>
                                                <option value="<?php echo htmlspecialchars($programa['id_programa']); ?>" <?php echo $programa['id_programa'] == $id_programa ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($programa['descripcion']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-primary" type="submit">Actualizar</button>
                                    <a href="listarCohorte.php" class="btn btn-secondary">Cancelar</a>
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
