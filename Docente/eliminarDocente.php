<?php
include "../complementos/conexion.php";

// Verificar si se ha proporcionado el ID del docente
if (isset($_GET['id_docente'])) {
    $id_docente = intval($_GET['id_docente']); // Obtener el ID del docente de la URL

    // Conectar a la base de datos
    $conexion = conexion();
    if (!$conexion) {
        echo "<script>alert('Error de conexión a la base de datos'); window.location.href='listarDocente.php';</script>";
        exit();
    }

    // Obtener la información del docente para eliminar la fotografía si existe
    $query = mysqli_query($conexion, "SELECT foto FROM docentes WHERE id_docente = $id_docente");
    $row = mysqli_fetch_assoc($query);

    if ($row) {
        // Eliminar la fotografía del servidor si existe
        $foto = $row['foto'];
        if ($foto && file_exists("../uploads/$foto")) {
            unlink("../uploads/$foto");
        }

        // Iniciar transacción
        mysqli_begin_transaction($conexion);

        try {
            // Eliminar el registro del docente
            $query_delete = mysqli_query($conexion, "DELETE FROM docentes WHERE id_docente = $id_docente");
            if (!$query_delete) {
                throw new Exception("Error al eliminar el docente: " . mysqli_error($conexion));
            }

            // Confirmar transacción
            mysqli_commit($conexion);

            echo "<script>alert('Docente eliminado correctamente'); window.location.href='listarDocente.php';</script>";
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            mysqli_rollback($conexion);
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='listarDocente.php';</script>";
        }

        // Cerrar la conexión
        mysqli_close($conexion);
    } else {
        echo "<script>alert('El docente no existe'); window.location.href='listarDocente.php';</script>";
    }
} else {
    echo "<script>alert('ID de docente no proporcionado'); window.location.href='listarDocente.php';</script>";
}
?>
