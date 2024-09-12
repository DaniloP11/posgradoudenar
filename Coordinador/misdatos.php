<?php
// Incluir la conexión a la base de datos
include "../complementos/conexion.php";
session_start();

// Verificar si 'email' está definido en la sesión
if (!isset($_SESSION['email'])) {
    echo "Email no definido en la sesión.";
    exit();
}

// Obtener el email del coordinador desde la sesión
$email = $_SESSION['email'];
$con = conexion();

// Realizar la consulta a la tabla `coordinadores` utilizando el campo de email
$query_coordinador = mysqli_query($con, "SELECT * FROM coordinadores WHERE correo = '$email'");

// Verificar si el coordinador existe
if ($mostrar = mysqli_fetch_array($query_coordinador)) {
    // Almacenar los datos del coordinador en variables
    $nombre = $mostrar['nombre'];
    $apellido = $mostrar['apellido'];
    $celular = $mostrar['telefono']; // Reemplaza por el dato que almacenes como número de teléfono
    $correo = $mostrar['correo'];
} else {
    // Si no se encuentra el coordinador, redirigir o mostrar un mensaje de error
    echo "Coordinador no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis datos Admin</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" type="image/x-icon" href="img/">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" 
            integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" 
            integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" 
            integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>

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
                Sesión Coordinador
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="../index.html">Cerrar sesión</a></li>
            </ul>
            </div>
            <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Coordinador</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-start flex-grow-1 pe-3">
                <li class="nav-item">
                    <a class="nav-link active" href="InicioCoord.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="UsuariosCoord.html">Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="misdatos.php">Mis datos</a>
                </li>
                </ul>
            </div>
            </div>
        </div>
        </nav>
    </div>


    <div class="inicio_adm">

        <div class="card">
        <h3 class="card-header text-center">MIS DATOS PERSONALES</h3>
        <div class="card-body">
            <div class="container text-right">
            <div class="row align-items-start">
                <div class="col">

                <form method="POST">
                    <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Nombre</span>
                    <input type="text" class="form-control" name="txtnombre" value="<?php echo $nombre; ?>" required disabled>
                    </div>

                </div>
                <div class="col">

                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Apellido</span>
                    <input type="text" class="form-control" name="txtapellido" value="<?php echo $apellido; ?>" required disabled>
                </div>

                </div>
            </div>

            <div class="row align-items-start">

                <div class="col">
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Celular</span>
                    <input type="text" class="form-control" name="txtcel" value="<?php echo $celular; ?>" required disabled>
                </div>

                </div>
                <div class="col">
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Correo electrónico</span>
                    <input type="text" class="form-control" name="txtcorreo" value="<?php echo $correo; ?>" required disabled>
                </div>

                </div>
            </div>
            </div>
            </form>

        </div>
        </div>

    </div>
    </div>

    <br>

</body>

</html>
