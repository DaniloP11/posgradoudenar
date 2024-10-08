<?php
// Incluir la conexión a la base de datos
include "../complementos/conexion.php";
session_start();

// Obtener el email del coordinador desde la sesión
$email = $_SESSION['email'];
$con = conexion();

// Realizar la consulta a la tabla `coordinadores` utilizando el campo de email
$query_coordinador = mysqli_query($con, "SELECT * FROM coordinadores WHERE correo = '$email'");

// Verificar si el coordinador existe
if ($mostrar = mysqli_fetch_array($query_coordinador)) {
    // Almacenar el nombre y apellido del coordinador en variables
    $nombre = $mostrar['nombre'];
    $apellido = $mostrar['apellido'];
} else {
    // Si no se encuentra el coordinador, mostrar un mensaje de error
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
    <title>Inicio Coordinador</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" type="image/x-icon" href="../img/icon.png">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"
        integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

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
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar"
            aria-controls="offcanvasDarkNavbar">
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

            <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar"
            aria-labelledby="offcanvasDarkNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Cordinador</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-start flex-grow-1 pe-3">
                <li class="nav-item">
                    <a class="nav-link active text-white" href="InicioCoord.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="UsuariosCoord.html">Usuarios</a>
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

        <div class="text-center">
        <img src="../img/usuario.png" class="img_usr" alt="img perfil administrador">
        </div>
        <br>

        <div class="card">
        <h3 class="card-header text-center">Bienvenid@ <?php echo "$nombre"; ?> <?php echo "$apellido"; ?></h3>
        <div class="card-body">
            <h5 class="card-title">Información</h5>
            <p class="card-text">Usted ha ingresado exitosamente al apartado de coordinador,
            en este módulo posee control total para la gestión de usuarios tales como docentes, estudiantes, 
            así como cohortes y cursos.</p>
        </div>
        </div>
    </div>
    <br>

    </body>

</html>