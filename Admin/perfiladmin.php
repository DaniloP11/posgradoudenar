<?php
include "../complementos/conexion.php";
session_start();
$id = $_GET['id']; // ID del administrador pasado por URL
$con = conexion();

// Consulta para obtener los datos del administrador
$querybuscar = mysqli_query($con, "SELECT * FROM administrador WHERE admin_id = $id");
while ($mostrar = mysqli_fetch_array($querybuscar)) {
    // Almacenar los datos en variables
    $nombre = $mostrar['admin_nombre'];
    $apellido = $mostrar['admin_apellido'];
    $celular = $mostrar['admin_usuario']; // Usamos admin_usuario si es usado para almacenar el celular
    $correo = $mostrar['admin_email'];
}

// Actualizar los datos cuando se presiona el botón de "Modificar"
if (isset($_POST['btnmodificar'])) {
    $nombre2 = $_POST['txtnombre'];
    $apellido2 = $_POST['txtapellido'];
    $celular2 = $_POST['txtcel'];
    $correo2 = $_POST['txtcorreo'];

    // Consulta para actualizar los datos del administrador
    $querymodificar = mysqli_query($con, "UPDATE administrador SET admin_nombre='$nombre2', admin_apellido='$apellido2', admin_usuario='$celular2', admin_email='$correo2' WHERE admin_id=$id");
    
    if ($querymodificar) {
        ?>
        <script>
        alert("Datos modificados exitosamente");
        </script>
        <?php
    } else {
        ?>
        <script>
        alert("Error al modificar los datos");
        </script>
        <?php
    }

    // Redirigir al usuario después de modificar los datos
    echo "<script>window.location= '../Admin/visualizar.php' </script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modificar Datos Administrador</title>
  <link rel="icon" type="image/x-icon" href="../img/icon.png">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"
    integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"
    integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous">
  </script>

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
            Sesión Administrador
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="../index.html">Cerrar sesión</a></li>
          </ul>
        </div>

        <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar"
          aria-labelledby="offcanvasDarkNavbarLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Administrador</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
              aria-label="Close"></button>
          </div>

          <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-start flex-grow-1 pe-3">
              <li class="nav-item">
                <a class="nav-link active" href="InicioAdmi.php">Inicio</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="UsuariosAdmin.html">Usuarios</a>
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
      <h3 class="card-header text-center">MODIFICAR DATOS PERSONALES</h3>
      <div class="card-body">
        <div class="container text-right">
          <div class="row align-items-start">
            <div class="col">

              <form method="POST">
                <div class="input-group input-group-sm mb-3">
                  <span class="input-group-text" id="inputGroup-sizing-sm">Nombre</span>
                  <input type="text" class="form-control" aria-label="Sizing example input"
                    aria-describedby="inputGroup-sizing-sm" name="txtnombre" value="<?php echo $nombre; ?>" required>
                </div>

            </div>
            <div class="col">

              <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" id="inputGroup-sizing-sm">Apellido</span>
                <input type="text" class="form-control" aria-label="Sizing example input"
                  aria-describedby="inputGroup-sizing-sm" name="txtapellido" value="<?php echo $apellido; ?>" required>
              </div>

            </div>
          </div>

          <div class="row align-items-start">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" id="inputGroup-sizing-sm">Celular</span>
                <input type="text" class="form-control" aria-label="Sizing example input"
                  aria-describedby="inputGroup-sizing-sm" name="txtcel" value="<?php echo $celular; ?>" required>
              </div>

            </div>
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" id="inputGroup-sizing-sm">Correo electrónico</span>
                <input type="text" class="form-control" aria-label="Sizing example input"
                  aria-describedby="inputGroup-sizing-sm" name="txtcorreo" value="<?php echo $correo; ?>" required>
              </div>

            </div>
          </div>
        </div>
        <div class="modificar">
          <input class="btn btn-success" type="submit" name="btnmodificar" value="Modificar"
            onClick="javascript: return confirm('¿Deseas modificar los datos?');">
          <a class="btn btn-success" href="visualizar.php" role="button">Atrás</a>
        </div>
        </form>

      </div>
    </div>
    <div class="d-flex justify-content-center py-5">
      <a href="modpass.php?id2=<?php echo $id ?>">
        <button type="button" class="btn btn-warning">Modificar contraseña</button>
      </a>
    </div>
  </div>
  </div>

</body>

</html>
