<?php
include "../complementos/conexion.php";

// Verificar si se ha enviado el ID del programa
if (!isset($_GET['id_programa'])) {
    die("ID del programa no especificado.");
}

$id_programa = $_GET['id_programa'];
$conexion = conexion();
if (!$conexion) {
    die("Error de conexión a la base de datos");
}

// Obtener los datos del programa
$query = mysqli_query($conexion, "SELECT * FROM programas WHERE id_programa = '$id_programa'");
$programa = mysqli_fetch_assoc($query);

if (!$programa) {
    die("Programa no encontrado.");
}

if (!empty($_POST)) {
    if (empty($_POST['codigo_SNIES']) || empty($_POST['descripcion']) || empty($_POST['lineas_trabajo']) || empty($_POST['id_coordinador']) || empty($_POST['resolucion']) || empty($_POST['fecha_generacion'])) {
        ?>
        <script>
            alert("Todos los campos obligatorios deben estar llenos");
        </script>
        <?php
    } else {
        $codigo_SNIES = $_POST['codigo_SNIES'];
        $descripcion = $_POST['descripcion'];
        $lineas_trabajo = $_POST['lineas_trabajo'];
        $id_coordinador = $_POST['id_coordinador'];
        $resolucion = $_POST['resolucion'];
        $fecha_generacion = $_POST['fecha_generacion'];

        // Manejar la carga del archivo de logo
        $logo = $programa['logo']; // Mantener el logo actual si no se sube uno nuevo
        $upload_dir = '../Programa/uploads/'; // Ruta relativa a la carpeta 'Programa'

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            // Asegúrate de que el directorio de carga existe
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $logo_path = $upload_dir . basename($_FILES['logo']['name']);
            move_uploaded_file($_FILES['logo']['tmp_name'], $logo_path);
            $logo = 'uploads/' . basename($_FILES['logo']['name']); // La ruta relativa para la base de datos
        }

        $query_update_programa = mysqli_query($conexion, "UPDATE programas SET codigo_SNIES = '$codigo_SNIES', descripcion = '$descripcion', logo = '$logo', lineas_trabajo = '$lineas_trabajo', id_coordinador = '$id_coordinador', resolucion = '$resolucion', fecha_generacion = '$fecha_generacion' WHERE id_programa = '$id_programa'");

        if ($query_update_programa) {
            ?>
            <script>
                alert("Programa actualizado correctamente");
                window.location.href = "listarPrograma.php"; // Redirigir al listado después de la actualización
            </script>
            <?php
        } else {
            ?>
            <script>
                alert("Error al actualizar el programa");
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
    <title>Editar Programa</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.png">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
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
        <h3 align="center">EDITAR PROGRAMA</h3>
        <div class="row">
            <div class="col py-5">
                <div class="mx-5 bg-light" style="border-radius: 2%; ">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row mb-3 needs-validation" novalidate>
                            <div class="col mx-5 px-5">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="codigo_SNIES" class="form-label">Código SNIES</label>
                                        <input type="text" class="form-control" id="codigo_SNIES" name="codigo_SNIES" value="<?php echo htmlspecialchars($programa['codigo_SNIES']); ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($programa['descripcion']); ?></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="lineas_trabajo" class="form-label">Líneas de Trabajo</label>
                                        <textarea class="form-control" id="lineas_trabajo" name="lineas_trabajo" rows="3"><?php echo htmlspecialchars($programa['lineas_trabajo']); ?></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="id_coordinador" class="form-label">Coordinador</label>
                                        <select class="form-select" id="id_coordinador" name="id_coordinador">
                                            <?php
                                            // Obtener la lista de coordinadores de la base de datos
                                            $query_coordinadores = mysqli_query($conexion, "SELECT id_coordinador, nombre FROM coordinadores");

                                            // Verificar si la consulta fue exitosa
                                            if ($query_coordinadores) {
                                                // Recorrer la lista de coordinadores y crear las opciones del select
                                                while ($coordinador = mysqli_fetch_assoc($query_coordinadores)) {
                                                    // Determinar si el coordinador actual es el seleccionado
                                                    $selected = ($coordinador['id_coordinador'] == $programa['id_coordinador']) ? 'selected' : '';
                                                    
                                                    // Imprimir la opción del select
                                                    echo "<option value=\"{$coordinador['id_coordinador']}\" $selected>";
                                                    echo htmlspecialchars($coordinador['nombre']);
                                                    echo "</option>";
                                                }
                                            } else {
                                                // Mensaje de error si la consulta falla
                                                echo "<option value=\"\">Error al cargar coordinadores</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="resolucion" class="form-label">Resolución</label>
                                        <input type="text" class="form-control" id="resolucion" name="resolucion" value="<?php echo htmlspecialchars($programa['resolucion']); ?>">
                                    </div>
                                    <div class="col">
                                        <label for="fecha_generacion" class="form-label">Fecha de Generación</label>
                                        <input type="date" class="form-control" id="fecha_generacion" name="fecha_generacion" value="<?php echo htmlspecialchars($programa['fecha_generacion']); ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="logo" class="form-label">Logo</label>
                                        <input type="file" class="form-control" id="logo" name="logo">
                                        <?php if ($programa['logo']) { ?>
                                            <img src="<?php echo htmlspecialchars($programa['logo']); ?>" alt="Logo Actual" style="width:100px; height:auto;">
                                        <?php } ?>
                                    </div>
                                </div>
                                <br>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-primary" type="submit">Actualizar</button>
                                    <a href="listarPrograma.php" class="btn btn-secondary">Cancelar</a>
                                </div>
                                <br><br> 
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
