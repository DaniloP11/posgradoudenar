<?php
include "../complementos/conexion.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; // Asegúrate de que PHPMailer esté en el lugar correcto

function generarContraseña($longitud = 8) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $cantidad_caracteres = strlen($caracteres);
    $contraseña = '';
    for ($i = 0; $i < $longitud; $i++) {
        $contraseña .= $caracteres[rand(0, $cantidad_caracteres - 1)];
    }
    return $contraseña;
}

if (!empty($_POST)) {
    if (empty($_POST['nombre']) || empty($_POST['identificacion']) || empty($_POST['correo']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['genero']) || empty($_POST['fecha_nacimiento']) || empty($_POST['programa']) || empty($_FILES['acuerdo_nombramiento']['name']) || empty($_FILES['foto']['name'])) {
        ?>
        <script>
        alert("Todos los campos obligatorios deben estar llenos, incluyendo la fotografía.");
        </script>
        <?php
    } else {
        // Recolección de datos del formulario
        $nombre = $_POST['nombre'];
        $identificacion = $_POST['identificacion'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $genero = $_POST['genero'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $fecha_vinculacion = $_POST['fecha_vinculacion'];
        $programa = $_POST['programa'];
        $contraseña = generarContraseña(); // Generar la contraseña automática

        // Verificar si el correo ya existe
        $con = conexion();
        $query = mysqli_query($con, "SELECT correo FROM coordinadores WHERE correo = '$correo'");
        $result = mysqli_fetch_array($query);
        if ($result) {
            ?>
            <script>
            alert("El correo ya existe");
            </script>
            <?php
        } else {
            // Manejo del archivo PDF para el acuerdo de nombramiento
            $acuerdo_nombramiento = NULL;
            if (isset($_FILES['acuerdo_nombramiento']) && $_FILES['acuerdo_nombramiento']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = '../Coordinador/uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $upload_file = $upload_dir . basename($_FILES['acuerdo_nombramiento']['name']);
                if (move_uploaded_file($_FILES['acuerdo_nombramiento']['tmp_name'], $upload_file)) {
                    $acuerdo_nombramiento = basename($_FILES['acuerdo_nombramiento']['name']);
                } else {
                    ?>
                    <script>
                    alert("Error al cargar el acuerdo de nombramiento");
                    </script>
                    <?php
                }
            }

            // Manejo de la fotografía
            $foto = NULL;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
                $foto_dir = '../Coordinador/fotos/';
                if (!is_dir($foto_dir)) {
                    mkdir($foto_dir, 0777, true);
                }
                $foto_file = $foto_dir . basename($_FILES['foto']['name']);
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $foto_file)) {
                    $foto = basename($_FILES['foto']['name']);
                } else {
                    ?>
                    <script>
                    alert("Error al cargar la fotografía");
                    </script>
                    <?php
                }
            }

            // Consulta para obtener la descripción del programa
            $query_programa = mysqli_query($con, "SELECT descripcion FROM programas WHERE id_programa = '$programa'");
            if ($query_programa && mysqli_num_rows($query_programa) > 0) {
                $row_programa = mysqli_fetch_assoc($query_programa);
                $descripcion = $row_programa['descripcion'];
            } else {
                $descripcion = 'Descripción no disponible';
            }

            // Insertar el nuevo coordinador, incluyendo la foto
            $query_insert_coordinador = mysqli_query($con, "INSERT INTO coordinadores (nombre, identificacion, direccion, telefono, correo, genero, fecha_nacimiento, fecha_vinculacion, foto, acuerdo_nombramiento, contraseña, id_programa) 
                            VALUES ('$nombre', '$identificacion', '$direccion', '$telefono', '$correo', '$genero', '$fecha_nacimiento', '$fecha_vinculacion', '$foto', '$acuerdo_nombramiento', '$contraseña', '$programa')");

            if ($query_insert_coordinador) {
                // Enviar el correo con PHPMailer
                $mail = new PHPMailer(true);
                try {
                    // Configuración del servidor SMTP
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'stdanilo06@gmail.com';  // Cambia esto por tu correo de Gmail
                    $mail->Password   = 'nmyh dmda ukwf zhvs';  // Cambia esto por tu contraseña de Gmail
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    // Configuración del correo
                    $mail->setFrom('stdanilo06@gmail.com', 'Stiven Paguay');
                    $mail->addAddress($correo);  // Correo del usuario registrado

                    // Contenido del correo
                    $mail->isHTML(true);
                    $mail->Subject = 'Tu cuenta ha sido creada';
                    $mail->Body = '
                    <!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Recuperar Contraseña - UDENAR</title>
                        <style>
                            body {
                                font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
                                background-color: #f0f0f5;
                                margin: 0;
                                padding: 20px;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                height: 100vh;
                            }
                            .container {
                                background-color: #ffffff;
                                border-radius: 10px;
                                padding: 30px;
                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                                max-width: 500px;
                                width: 100%;
                                text-align: center;
                            }
                            h2 {
                                font-size: 24px;
                                color: #2c3e50;
                                margin-bottom: 20px;
                                text-align: center;
                            }
                            h1 {
                                font-size: 32px;
                                color: #34495e;
                                margin-bottom: 10px;
                                text-align: center;
                            }
                            p {
                                font-size: 16px;
                                color: #7f8c8d;
                                line-height: 1.6;
                                margin-bottom: 20px;
                                text-align: justify;
                            }
                            .highlight {
                                background-color: #ecf0f1;
                                color: #2980b9;
                                padding: 15px;
                                border-radius: 5px;
                                font-weight: bold;
                                display: inline-block;
                                margin-bottom: 30px;
                                font-size: 18px;
                            }
                            .footer {
                                margin-top: 30px;
                                font-size: 12px;
                                color: #bdc3c7;
                            }
                            .btn {
                                background-color: #3498db;
                                color: #ffffff;
                                padding: 12px 20px;
                                border-radius: 5px;
                                text-decoration: none;
                                font-size: 16px;
                                transition: background-color 0.3s ease;
                            }
                            .btn:hover {
                                background-color: #2980b9;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <h1>Universidad De Nariño</h1>
                            <h3>Tu cuenta ha sido creada</h3>
                            <p>Hola '.$nombre.',</p>
                            <p>Se te ha asignado como coordinador del programa: <br><strong>'.$descripcion.'</strong> <br><br>A continuación, te mostramos tu usuario y contraseña.<br>
                            <p><strong>Usuario:</strong> '.$correo.'<br>
                            <p><strong>Contraseña:</strong><br>
                            <div class="highlight">' . htmlspecialchars($contraseña) . '</div>
                            <p>Si necesitas ayuda, puedes contactar con nuestro equipo de soporte.</p>

                            <div class="footer">
                                &copy; ' . date('Y') . ' Universidad de Nariño. Todos los derechos reservados.
                            </div>
                        </div>
                    </body>
                    </html>
                    ';
                    $mail->send();
                    ?>
                    <script>
                    alert("Coordinador creado correctamente y correo enviado");
                    </script>
                    <?php
                } catch (Exception $e) {
                    ?>
                    <script>
                    alert("Coordinador creado pero no se pudo enviar el correo. Error: <?= $mail->ErrorInfo; ?>");
                    </script>
                    <?php
                }
            } else {
                ?>
                <script>
                alert("Error al crear el coordinador");
                </script>
                <?php
            }

        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Coordinadores</title>
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
                            <a class="nav-link text-white" href="../Admin/perfiladmin.php">Mi perfil</a>
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
        <h3 align="center">REGISTRO DE COORDINADORES</h3>
            <div class="row">
                <div class="col py-5">
                    <div class="mx-5 bg-light" style="border-radius: 2%;">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="row mb-3 needs-validation" novalidate>
                                <div class="col mx-5 px-5">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="nombre" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre">
                                        </div>
                                        <div class="col">
                                            <label for="identificacion" class="form-label">Identificación</label>
                                            <input type="text" class="form-control" id="identificacion" name="identificacion">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="correo" class="form-label">Correo</label>
                                            <input type="email" class="form-control" id="correo" name="correo">
                                        </div>
                                        <div class="col">
                                            <label for="telefono" class="form-label">Teléfono</label>
                                            <input type="text" class="form-control" id="telefono" name="telefono">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="direccion" class="form-label">Dirección</label>
                                            <input type="text" class="form-control" id="direccion" name="direccion">
                                        </div>
                                        <div class="col">
                                            <label for="genero" class="form-label">Género</label>
                                            <select class="form-select" id="genero" name="genero">
                                                <option value="" selected>Seleccionar género</option>
                                                <option value="Masculino">Masculino</option>
                                                <option value="Femenino">Femenino</option>
                                                <option value="Otro">Otro</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                                        </div>
                                        <div class="col">
                                            <label for="fecha_vinculacion" class="form-label">Fecha de Vinculación</label>
                                            <input type="date" class="form-control" id="fecha_vinculacion" name="fecha_vinculacion">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                                <label for="programa" class="form-label">Asignar Programa</label>
                                                <select class="form-select" id="programa" name="programa">
                                                    <option value="" selected>Seleccionar programa</option>
                                                    <?php
                                                    $con = conexion();
                                                    $query_programas = mysqli_query($con, "SELECT id_programa, descripcion FROM programas");
                                                    while ($programa = mysqli_fetch_array($query_programas)) {
                                                        echo '<option value="' . $programa['id_programa'] . '">' . $programa['descripcion'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="acuerdo_nombramiento" class="form-label">Acuerdo de Nombramiento (PDF)</label>
                                            <input type="file" class="form-control" id="acuerdo_nombramiento" name="acuerdo_nombramiento" accept="application/pdf">
                                        </div>
                                        <div class="col">
                                            <label for="foto" class="form-label">Fotografía</label>
                                            <input type="file" class="form-control" id="foto" name="foto" accept="image/jpeg, image/png">
                                        </div>                      
                                    </div>  
                                    <br>              
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button class="btn btn-primary" type="submit">Registrar</button>
                                        <button class="btn btn-secondary" type="button" onclick="window.location.href='../Admin/UsuariosAdmin.html'">Cancelar</button>
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



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zyyP8U5puWl29z6F9Q4L0MlupZyOeIepwDZP7B4Y" crossorigin="anonymous"></script>
</body>
</html>
