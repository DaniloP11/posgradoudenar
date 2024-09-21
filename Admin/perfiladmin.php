<?php
include "../complementos/conexion.php"; // Asegúrate de que esta ruta sea correcta
session_start();

$con = conexion(); // Llama a la función para establecer la conexión

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1; // Asigna un ID válido aquí

// Consulta para obtener los datos del administrador
$stmt = $con->prepare("SELECT * FROM administrador WHERE admin_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $mostrar = $result->fetch_assoc();

    // Verificar si las claves existen
    $nombre = htmlspecialchars($mostrar['admin_nombre'] ?? '');
    $apellido = htmlspecialchars($mostrar['admin_apellido'] ?? '');
    $correo = htmlspecialchars($mostrar['admin_email'] ?? '');
    $clave = htmlspecialchars($mostrar['admin_clave'] ?? '');
} else {
    echo "<script>alert('No se encontraron datos.');</script>";
    exit;
}

// Procesar la modificación de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnmodificar'])) {
    $nombre2 = htmlspecialchars(trim($_POST['txtnombre']));
    $apellido2 = htmlspecialchars(trim($_POST['txtapellido']));
    $correo2 = htmlspecialchars(trim($_POST['txtcorreo']));
    $clave2 = htmlspecialchars(trim($_POST['txtclave']));
    $claveConfirm = htmlspecialchars(trim($_POST['txtclave_confirm']));

    if ($clave2 !== $claveConfirm) {
        echo "<script>alert('Las contraseñas no coinciden.');</script>";
    } else {
        // Consulta preparada para modificar los datos
        $stmt = $con->prepare("UPDATE administrador SET admin_nombre=?, admin_apellido=?, admin_email=?, admin_clave=? WHERE admin_id=?");
        $stmt->bind_param("ssssi", $nombre2, $apellido2, $correo2, $clave2, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Datos modificados exitosamente');</script>";
        } else {
            echo "<script>alert('Error al modificar los datos');</script>";
        }
    }
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url(../img/font.png);
            background-size: cover;
        }
        .foto-preview {
            width: 100px; /* Ajusta el tamaño de la imagen aquí */
            height: auto;
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
                            <a class="nav-link text-white" href="perfiladmin.php">Mi perfil</a>
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
        <h3 align="center">MODIFICAR DATOS PERSONALES</h3>
        <div class="row">
            <div class="col py-5">
                <div class="mx-5 bg-light" style="border-radius: 2%;">
                    <form method="POST">
                        <div class="row mb-3 needs-validation" novalidate>
                            <div class="col mx-5 px-5">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" name="txtnombre" value="<?php echo $nombre; ?>" required>
                                    </div>
                                    <div class="col">
                                        <label for="apellido" class="form-label">Apellido</label>
                                        <input type="text" class="form-control" name="txtapellido" value="<?php echo $apellido; ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="correo" class="form-label">Correo electrónico</label>
                                        <input type="email" class="form-control" name="txtcorreo" value="<?php echo $correo; ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="contraseña" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control" name="txtclave" required>
                                    </div>
                                    <div class="col">
                                        <label for="confirmar_contraseña" class="form-label">Confirmar Contraseña</label>
                                        <input type="password" class="form-control" name="txtclave_confirm" required>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-success" type="submit" name="btnmodificar" onclick="return confirm('¿Deseas modificar los datos?');">Modificar</button>
                                    <a class="btn btn-secondary" href="misdatos.php" role="button">Atrás</a>
                                </div>
                                <br>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
