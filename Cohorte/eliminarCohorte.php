<?php
include "../complementos/conexion.php";
session_start();

// Verificar que el usuario está logueado
if (!isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: ../index.html");
    exit();
}

// Obtener el ID del cohorte a eliminar
$id_cohorte = $_GET['id_cohorte'] ?? null;

// Mostrar el valor recibido del ID para depuración
echo "ID de cohorte recibido: " . htmlspecialchars($id_cohorte);

// Verificar que el ID sea válido
if (!$id_cohorte || !is_numeric($id_cohorte)) {
    die("ID de cohorte no proporcionado o no válido");
}

// Conexión a la base de datos
$conexion = conexion();
if (!$conexion) {
    die("Error de conexión a la base de datos");
}

// Iniciar transacción
mysqli_begin_transaction($conexion);

try {
    // Eliminar el cohorte
    $stmt = mysqli_prepare($conexion, "DELETE FROM cohortes WHERE id_cohorte = ?");
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta de eliminación de cohorte: " . mysqli_error($conexion));
    }
    mysqli_stmt_bind_param($stmt, 'i', $id_cohorte);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error al eliminar el cohorte: " . mysqli_stmt_error($stmt));
    }
    mysqli_stmt_close($stmt);

    // Confirmar transacción
    mysqli_commit($conexion);

    echo "<script>alert('Cohorte eliminado correctamente'); window.location.href = 'listarCohorte.php';</script>";
} catch (Exception $e) {
    // Revertir transacción en caso de error
    mysqli_rollback($conexion);
    echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href = 'listarCohorte.php';</script>";
}

// Cerrar la conexión
mysqli_close($conexion);
?>
