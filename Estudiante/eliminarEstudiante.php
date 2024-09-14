<?php
include "../complementos/conexion.php";
session_start();

// Verificar que el usuario está logueado
if (!isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: ../index.html");
    exit();
}

// Verificar si se ha enviado el ID del estudiante para eliminar
if (isset($_GET['id_estudiante'])) {
    $id_estudiante = intval($_GET['id_estudiante']); // Obtener el ID del estudiante de la URL

    // Conectar a la base de datos
    $conexion = conexion();
    if (!$conexion) {
        die("Error de conexión a la base de datos");
    }

    // Iniciar transacción
    mysqli_begin_transaction($conexion);

    try {
        // Obtener la información del estudiante para eliminar la fotografía si existe
        $query = mysqli_query($conexion, "SELECT fotografia FROM estudiantes WHERE id_estudiante = $id_estudiante");
        if (!$query) {
            throw new Exception("Error al ejecutar la consulta: " . mysqli_error($conexion));
        }
        
        $row = mysqli_fetch_assoc($query);
        if ($row) {
            // Eliminar la fotografía del servidor si existe
            $fotografia = $row['fotografia'];
            if ($fotografia && file_exists("../uploads/$fotografia")) {
                unlink("../uploads/$fotografia");
            }

            // Preparar la consulta de eliminación del estudiante
            $stmt = mysqli_prepare($conexion, "DELETE FROM estudiantes WHERE id_estudiante = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta de eliminación: " . mysqli_error($conexion));
            }
            mysqli_stmt_bind_param($stmt, 'i', $id_estudiante);

            // Ejecutar la consulta
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error al eliminar el estudiante: " . mysqli_stmt_error($stmt));
            }

            // Confirmar transacción
            mysqli_commit($conexion);

            echo "<script>alert('Estudiante eliminado correctamente'); window.location.href='listarEstudiante.php';</script>";
        } else {
            throw new Exception("El estudiante no existe");
        }

        // Cerrar la declaración
        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        mysqli_rollback($conexion);
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='listarEstudiante.php';</script>";
    }

    // Cerrar la conexión
    mysqli_close($conexion);
} else {
    echo "<script>alert('ID de estudiante no proporcionado'); window.location.href='listarEstudiante.php';</script>";
}
?>
