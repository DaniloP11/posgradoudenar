<?php
include "../complementos/conexion.php";
session_start();
$email = $_SESSION['email'];
$con = conexion();
$ideper = $_GET['id2']; // Este es el ID del administrador pasado por URL o de alguna otra fuente

if (!empty($_POST)) {
    // Obtener los valores del formulario
    $pass_actual = $_POST['passact'];
    $pass_nueva = $_POST['passn'];
    $pass_repetida = $_POST['passr'];

    // Verificar que los campos no estén vacíos
    if (empty($pass_actual) || empty($pass_nueva) || empty($pass_repetida)) {
        ?>
        <script>
        alert("Todos los campos son obligatorios.");
        </script>
        <?php
    } else {
        // Verificar que la nueva contraseña y la repetida coincidan
        if ($pass_nueva != $pass_repetida) {
            ?>
            <script>
            alert("Las contraseñas nuevas no coinciden, vuelve a intentarlo.");
            </script>
            <?php
        } else {
            // Obtener la contraseña actual del administrador desde la base de datos
            $query_contra = mysqli_query($con, "SELECT admin_clave FROM administrador WHERE admin_id = '$ideper'");
            $admin_data = mysqli_fetch_array($query_contra);

            if ($admin_data) {
                // Verificar que la contraseña actual ingresada sea correcta
                if (password_verify($pass_actual, $admin_data['admin_clave'])) {
                    // Verificar si se presionó el botón de modificar
                    if (isset($_POST['btnmodificar2'])) {
                        // Hashear la nueva contraseña
                        $hashed_nueva_contrasena = password_hash($pass_nueva, PASSWORD_DEFAULT);

                        // Actualizar la contraseña en la base de datos
                        $query_modificar = mysqli_query($con, "UPDATE administrador SET admin_clave='$hashed_nueva_contrasena' WHERE admin_id='$ideper'");
                        if ($query_modificar) {
                            ?>
                            <script>
                            alert("Contraseña cambiada exitosamente.");
                            </script>
                            <?php
                        } else {
                            ?>
                            <script>
                            alert("Error al actualizar la contraseña.");
                            </script>
                            <?php
                        }
                        // Redirigir a la página de inicio de sesión
                        echo "<script>window.location= '../Login/login.html' </script>";
                    }
                } else {
                    ?>
                    <script>
                    alert("La contraseña actual es incorrecta, vuelve a intentarlo.");
                    </script>
                    <?php
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cambiar Contraseña</title>
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
      <h4 class="card-header text-center">CAMBIAR CONTRASEÑA</h4>
      <form method="POST">
        <div class="card-body orientacion_tarjeta">
          <div class="container text-right">
            <div class="row align-items-start" class="col">
              <div class="sm mb-2 text-center">
                <label for="passact" class="py-1">Contraseña actual</label>
                <input type="password" class="form-control" name="passact" required>
              </div>
            </div>

            <div class="row align-items-start">
              <div class="col">
                <div class="sm mb-3 text-center">
                  <label for="passn" class="py-1">Nueva contraseña</label>
                  <input type="password" class="form-control" name="passn" required>
                </div>
              </div>
            </div>

            <div class="row align-items-center">
              <div class="col text-center">
                <div class="sm mb-3">
                  <label for="passnr" class="py-1">Repetir nueva contraseña</label>
                  <input type="password" class="form-control" name="passr" required>
                </div>
              </div>
            </div>
          </div>
          <div class="modificar">
            <input class="btn btn-success" type="submit" name="btnmodificar2" value="Modificar"
              onClick="javascript: return confirm('¿Deseas modificar tu contraseña?');">
          </div>
      </form>
    </div>
  </div>

</body>

</html>
