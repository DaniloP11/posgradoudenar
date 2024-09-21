<?php
include "../complementos/conexion.php";
session_start();

// Verificar que el usuario está logueado
if (!isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: ../index.html");
    exit();
}

$id_cohorte = isset($_GET['id_cohorte']) ? intval($_GET['id_cohorte']) : 0;

$conexion = conexion();
if (!$conexion) {
    die("Error de conexión a la base de datos");
}

// Consulta para obtener estudiantes de la cohorte
$sql = "SELECT nombre, codigo_estudiantil, semestre FROM estudiantes WHERE id_cohorte = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id_cohorte);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);

if (!$query) {
    die("Error en la consulta: " . mysqli_error($conexion));
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Estudiantes en Cohorte</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.png">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #495057;
        }
        .container {
            margin-top: 50px;
        }
        h3 {
            color: #28a745; /* Verde */
            margin-bottom: 20px;
        }
        .table {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table th {
            background-color: #28a745; /* Verde */
            color: white;
            font-weight: bold;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-primary {
            background-color: #28a745; /* Verde */
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838; /* Verde más oscuro */
            border-color: #1e7e34;
        }
    </style>
</head>

<body>

<div class="container">
    <h3 class="text-center">Estudiantes en la Cohorte</h3>
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Código Estudiantil</th>
                    <th scope="col">Semestre</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_array($query)) {
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['codigo_estudiantil']); ?></td>
                    <td><?php echo htmlspecialchars($row['semestre']); ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <a href="listarCohorte.php" class="btn btn-primary">Volver</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
