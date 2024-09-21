<?php
include "../complementos/conexion.php";

if (!empty($_POST)) {
    if (empty($_POST['descripcion']) || empty($_POST['codigo_SNIES']) || empty($_POST['correo_contacto']) || empty($_POST['telefono_contacto']) || empty($_POST['lineas_trabajo']) || empty($_POST['resolucion']) || empty($_POST['fecha_generacion'])) {
        ?>
        <script>
            alert("Todos los campos obligatorios deben estar llenos");
        </script>
        <?php
    } else {
        $descripcion = $_POST['descripcion']; // Se usa como nombre del programa
        $codigo_SNIES = $_POST['codigo_SNIES'];
        $correo_contacto = $_POST['correo_contacto'];
        $telefono_contacto = $_POST['telefono_contacto'];
        $lineas_trabajo = $_POST['lineas_trabajo'];
        $resolucion = $_POST['resolucion'];
        $fecha_generacion = $_POST['fecha_generacion'];

        // Manejar la carga del archivo de logo
        $logo = '';
        $upload_dir = __DIR__ . '/uploads/'; // Ruta absoluta desde el directorio actual del script

        // Verificar si el directorio 'uploads' existe, si no, crearlo
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $logo_path = $upload_dir . basename($_FILES['logo']['name']);
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $logo_path)) {
                $logo = 'uploads/' . basename($_FILES['logo']['name']); // Ruta relativa a la base de datos
            } else {
                ?>
                <script>
                    alert("Error al subir el archivo.");
                </script>
                <?php
            }
        }

        // Insertar en la base de datos
        $query_insert_programa = mysqli_query(conexion(), "INSERT INTO programas(descripcion, codigo_SNIES, correo_contacto, telefono_contacto, lineas_trabajo, resolucion, fecha_generacion, logo)
        VALUES ('$descripcion', '$codigo_SNIES', '$correo_contacto', '$telefono_contacto', '$lineas_trabajo', '$resolucion', '$fecha_generacion', '$logo')");

        if ($query_insert_programa) {
            ?>
            <script>
                alert("Programa creado correctamente");
            </script>
            <?php
        } else {
            ?>
            <script>
                alert("Error al crear el programa");
            </script>
            <?php
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Programas</title>
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
        .form_registro {
            margin-top: 100px; /* Ajusta el espacio superior para que el formulario no quede pegado al borde superior */
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
    <div class="container px-4">
        <h3 align="center">REGISTRO DE PROGRAMAS</h3>
        <div class="row">
            <div class="col py-5">
                <div class="mx-5 bg-light" style="border-radius: 2%; ">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row mb-3 needs-validation" novalidate>
                            <div class="col mx-5 px-5">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="descripcion" class="form-label">Nombre del Programa</label>
                                        <input type="text" class="form-control" id="descripcion" name="descripcion">
                                    </div>
                                    <div class="col">
                                        <label for="codigo_SNIES" class="form-label">Código SNIES</label>
                                        <input type="text" class="form-control" id="codigo_SNIES" name="codigo_SNIES">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="correo_contacto" class="form-label">Correo de Contacto</label>
                                        <input type="email" class="form-control" id="correo_contacto" name="correo_contacto">
                                    </div>
                                    <div class="col">
                                        <label for="telefono_contacto" class="form-label">Teléfono de Contacto</label>
                                        <input type="text" class="form-control" id="telefono_contacto" name="telefono_contacto">
                                    </div>
                                </div>

                                <div class="row mb-3">
                            
                                    <div class="col">
                                        <label for="resolucion" class="form-label">Resolución</label>
                                        <input type="text" class="form-control" id="resolucion" name="resolucion">
                                    </div>
                                    <div class="col">
                                        <label for="fecha_generacion" class="form-label">Fecha de Generación</label>
                                        <input type="date" class="form-control" id="fecha_generacion" name="fecha_generacion">
                                    </div>
                                    
                                </div>
                                <div class="row mb-3">
                                    
                                    <div class="col">
                                        <label for="logo" class="form-label">Logo</label>
                                        <input type="file" class="form-control" id="logo" name="logo">
                                    </div>
                                    <div class="col">
                                        <label for="lineas_trabajo" class="form-label">Líneas de Trabajo</label>
                                        <textarea class="form-control" id="lineas_trabajo" name="lineas_trabajo" rows="3"></textarea>
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
