<?php
include "../complementos/conexion.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; // Asegúrate de que la ruta es correcta


// Función para generar una contraseña aleatoria
function generarContraseña($longitud = 8) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $longitudCaracteres = strlen($caracteres);
    $contraseña = '';
    for ($i = 0; $i < $longitud; $i++) {
        $contraseña .= $caracteres[rand(0, $longitudCaracteres - 1)];
    }
    return $contraseña;
}

if (!empty($_POST)) {
    // Validación de campos obligatorios
    if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['genero']) || empty($_POST['fecha_nacimiento']) || empty($_POST['programa']) || empty($_POST['coordinador'])) {
        ?>
        <script>
        alert("Todos los campos obligatorios deben estar llenos");
        </script>
        <?php
    } else {
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $genero = $_POST['genero'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $id_programa = $_POST['programa'];
        $id_coordinador = $_POST['coordinador'];

        // Generar una contraseña automática
        $contraseña = generarContraseña();

        // Verificar si el correo ya existe
        $query = mysqli_query(conexion(), "SELECT correo FROM asistentes WHERE correo = '$correo'");
        $result = mysqli_fetch_array($query);
        if ($result) {
            ?>
            <script>
            alert("El correo ya existe");
            </script>
            <?php
        } else {
            // Manejo de archivo de fotografía
            $fotografia = NULL;
            if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] == 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $max_size = 2 * 1024 * 1024; // 2MB
                $upload_dir = '../Asistente/uploads/';
                $upload_file = $upload_dir . basename($_FILES['fotografia']['name']);

                // Verifica el tipo de archivo
                if (!in_array($_FILES['fotografia']['type'], $allowed_types)) {
                    $error = 'Tipo de archivo no permitido.';
                }
                // Verifica el tamaño del archivo
                elseif ($_FILES['fotografia']['size'] > $max_size) {
                    $error = 'El archivo es demasiado grande.';
                } else {
                    // Verifica que el directorio exista
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    // Intenta mover el archivo
                    if (move_uploaded_file($_FILES['fotografia']['tmp_name'], $upload_file)) {
                        $fotografia = $_FILES['fotografia']['name'];
                    } else {
                        $error = 'Error al mover el archivo. Verifica los permisos y la ruta.';
                    }
                }
            }

            if (!isset($error)) {
                // Obtener la descripción del programa
                $query_programa = mysqli_query(conexion(), "SELECT descripcion FROM programas WHERE id_programa = '$id_programa'");
                if ($query_programa && mysqli_num_rows($query_programa) > 0) {
                    $row_programa = mysqli_fetch_assoc($query_programa);
                    $descripcion = $row_programa['descripcion'];  // Guardamos la descripción del programa
                } else {
                    $descripcion = 'Descripción no disponible';  // En caso de que no se encuentre
                }
                // Obtener el nombre del coordinador
                $query_coordinador = mysqli_query(conexion(), "SELECT nombre FROM coordinadores WHERE id_coordinador = '$id_coordinador'");
                if ($query_coordinador && mysqli_num_rows($query_coordinador) > 0) {
                    $row_coordinador = mysqli_fetch_assoc($query_coordinador);
                    $nombre_coordinador = $row_coordinador['nombre'];  // Guardamos el nombre del coordinador
                } else {
                    $nombre_coordinador = 'Coordinador no disponible';  // En caso de que no se encuentre
                }
                // Insertar el asistente en la base de datos
                $query_insert_asistente = mysqli_query(conexion(), "INSERT INTO asistentes (nombre, correo, telefono, direccion, genero, fecha_nacimiento, contraseña, fotografia, id_programa, id_coordinador)
                    VALUES ('$nombre', '$correo', '$telefono', '$direccion', '$genero', '$fecha_nacimiento', '$contraseña', '$fotografia', '$id_programa', '$id_coordinador')");
                if ($query_insert_asistente) {
                    // Instancia PHPMailer
                    $mail = new PHPMailer(true);
                    try {
                        // Configuración del servidor SMTP
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'stdanilo06@gmail.com';  // Cambia esto por tu correo de Gmail
                        $mail->Password   = 'nmyh dmda ukwf zhvs';   // Cambia esto por tu contraseña de Gmail
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
                            <title>Registro de Asistente</title>
                            <style>
                                body {font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f0f5;}
                                .container {background-color: #ffffff; border-radius: 10px; padding: 30px; max-width: 500px; width: 100%;}
                                h1, h3 {text-align: center; color: #2c3e50;}
                                p {font-size: 16px; color: #7f8c8d; line-height: 1.6;}
                                .highlight {background-color: #ecf0f1; color: #2980b9; padding: 15px; border-radius: 5px;}
                                .footer {margin-top: 30px; font-size: 12px; color: #bdc3c7; text-align: center;}
                            </style>
                        </head>
                        <body>
                            <div class="container">
                                <h1>Universidad De Nariño</h1>
                                <h3>Tu cuenta ha sido creada</h3>
                                <p>Hola '.$nombre.',</p>
                                <p>Se te ha asignado como asistente del programa: <br><strong>'.$descripcion.'</strong><br>
                                <p>Coordinador asignado: <br><strong>'.$nombre_coordinador.'</strong><br><br>

                                <p>A continuación, te mostramos tu contraseña y usuario</p>
                                <p><strong>Usuario:</strong> '.$correo.'<br>
                                <strong>Contraseña:</strong> </p>
                                <div class="highlight">' . htmlspecialchars($contraseña) . '</div>
                                <p>Si necesitas ayuda, puedes contactar con nuestro equipo de soporte.</p>
                                <div class="footer">&copy; ' . date('Y') . ' Universidad de Nariño. Todos los derechos reservados.</div>
                            </div>
                        </body>
                        </html>
                        ';
                        // Enviar correo
                        $mail->send();
                        echo "<script>alert('Asistente creado correctamente y se envió la contraseña al correo');</script>";
                    } catch (Exception $e) {
                        echo "<script>alert('Error al enviar el correo: {$mail->ErrorInfo}');</script>";
                    }
                } else {
                    echo "<script>alert('Error al crear el asistente');</script>";
                }               
            } else {
                echo "<script>alert('{$error}');</script>";
            }
        }
    }
}

// Cargar coordinadores y programas desde la base de datos
$coordinadores = mysqli_query(conexion(), "SELECT id_coordinador, nombre FROM coordinadores");
$programas = mysqli_query(conexion(), "SELECT id_programa, descripcion FROM programas");

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registro de Asistentes</title>
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
        <h3 align="center">REGISTRO DE ASISTENTES</h3>
            <div class="row">
                <div class="col py-5">
                    <div class="mx-5 bg-light " style="border-radius: 2%; ">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="row mb-3 needs-validation" novalidate>
                                <div class="col mx-5 px-5">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="nombre" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                                        </div>
                                        <div class="col">
                                            <label for="correo" class="form-label">Correo</label>
                                            <input type="email" class="form-control" id="correo" name="correo" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="telefono" class="form-label">Teléfono</label>
                                            <input type="tel" class="form-control" id="telefono" name="telefono">
                                        </div>
                                        <div class="col">
                                            <label for="direccion" class="form-label">Dirección</label>
                                            <input type="text" class="form-control" id="direccion" name="direccion">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
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
                                            <label for="programa" class="form-label">Programa</label>
                                                <select class="form-select" id="programa" name="programa" required>
                                                    <option value="">Seleccionar programa</option>
                                                        <?php while ($row = mysqli_fetch_assoc($programas)) { ?>
                                                    <option value="<?php echo $row['id_programa']; ?>"><?php echo $row['descripcion']; ?></option>
                                                        <?php } ?>
                                                </select>
                                        </div>
                                        <div class="col">
                                            <label for="coordinador" class="form-label">Coordinador</label>
                                                <select class="form-select" id="coordinador" name="coordinador" required>
                                                    <option value="">Seleccionar coordinador</option>
                                                        <?php while ($row = mysqli_fetch_assoc($coordinadores)) { ?>
                                                    <option value="<?php echo $row['id_coordinador']; ?>"><?php echo $row['nombre']; ?></option>
                                                        <?php } ?>
                                                </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="fotografia" class="form-label">Fotografía</label>
                                            <input type="file" class="form-control" id="fotografia" name="fotografia">
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

    </body>
</html>
