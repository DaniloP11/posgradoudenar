<?php
include "../complementos/conexion.php";
session_start();

// Obtener la conexión
$conn = conexion();

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el término de búsqueda
$search_query = isset($_GET['query']) ? $_GET['query'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda</title>
    <link rel="icon" href="../img/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('../img/font.png');
            background-size: cover;
            color: #333;
        }
        .container {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch; /* Para desplazamiento suave en dispositivos táctiles */
        }
        .table thead th {
            background-color: #28a745; /* Color verde */
            color: white;
            text-align: center;
            font-weight: bold;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .table tbody tr:hover {
            background-color: #e9ecef;
        }
        .table td, .table th {
            vertical-align: middle;
            white-space: nowrap; /* Previene el ajuste de texto en las celdas */
        }
        .alert {
            margin-bottom: 20px;
        }
        h1 {
            color: #28a745; /* Color verde */
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container-fluid d-grid gap-3">
        <div class="p-2 row align-items-center">

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
                                        <a class="nav-link active" aria-current="page" href="InicioAdmi.php">Inicio</a>
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
        </div>
    </div>
</div>

<div class="container mt-5">
    <h1>Resultados de la Búsqueda</h1>

    <?php
    // Preparar la consulta
    $sql = "SELECT * FROM estudiantes WHERE nombre LIKE ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $search_term = "%" . $search_query . "%";
    $stmt->bind_param("s", $search_term);

    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die("Error en la ejecución de la consulta: " . $stmt->error);
    }

    // Mostrar resultados
    if ($result->num_rows > 0): ?>
        <div class="table-container">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID Estudiante</th>
                        <th>Nombre</th>
                        <th>Identificación</th>
                        <th>Código Estudiantil</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Género</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Semestre</th>
                        <th>Estado Civil</th>
                        <th>Fecha de Ingreso</th>
                        <th>Fecha de Egreso</th>
                        <th>ID Cohorte</th>
                        <th>Fotografía</th>
                        <th>ID Programa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id_estudiante']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['identificacion']); ?></td>
                            <td><?php echo htmlspecialchars($row['codigo_estudiantil']); ?></td>
                            <td><?php echo htmlspecialchars($row['correo']); ?></td>
                            <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                            <td><?php echo htmlspecialchars($row['genero']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_nacimiento']); ?></td>
                            <td><?php echo htmlspecialchars($row['semestre']); ?></td>
                            <td><?php echo htmlspecialchars($row['estado_civil']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_ingreso']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_egreso']); ?></td>
                            <td><?php echo htmlspecialchars($row['id_cohorte']); ?></td>
                            <td><?php echo htmlspecialchars($row['fotografia']); ?></td>
                            <td><?php echo htmlspecialchars($row['id_programa']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            <p>No se encontraron resultados para "<?php echo htmlspecialchars($search_query); ?>"</p>
        </div>
    <?php endif; ?>

    <?php
    // Cerrar la conexión
    $stmt->close();
    $conn->close();
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
