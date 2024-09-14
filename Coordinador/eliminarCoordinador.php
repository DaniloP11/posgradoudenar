<?php
include "../complementos/conexion.php";

// Verificar si se ha proporcionado un ID de coordinador
if (isset($_GET['id_coordinador'])) {
    $id_coordinador = intval($_GET['id_coordinador']);

    // Conectar a la base de datos
    $conexion = conexion();
    if (!$conexion) {
        die("Error de conexión a la base de datos");
    }

    // Obtener la información del coordinador para eliminar el archivo asociado si existe
    $query = mysqli_query($conexion, "SELECT acuerdo_nombramiento FROM coordinadores WHERE id_coordinador = $id_coordinador");
    $coordinador = mysqli_fetch_assoc($query);

    if ($coordinador) {
        // Eliminar el archivo PDF del acuerdo de nombramiento si existe
        $upload_dir = '../Coordinador/uploads/';
        if ($coordinador['acuerdo_nombramiento'] && file_exists($upload_dir . $coordinador['acuerdo_nombramiento'])) {
            unlink($upload_dir . $coordinador['acuerdo_nombramiento']);
        }

        // Iniciar transacción
        mysqli_begin_transaction($conexion);

        try {
            // Eliminar el registro del coordinador
            $query_delete = mysqli_query($conexion, "DELETE FROM coordinadores WHERE id_coordinador = $id_coordinador");

            if (!$query_delete) {
                throw new Exception("Error al eliminar el coordinador: " . mysqli_error($conexion));
            }

            // Confirmar transacción
            mysqli_commit($conexion);

            echo "<script>alert('Coordinador eliminado correctamente'); window.location.href='listarCoordinador.php';</script>";
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            mysqli_rollback($conexion);
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='listarCoordinador.php';</script>";
        }

        // Cerrar la conexión
        mysqli_close($conexion);
    } else {
        echo "<script>alert('Coordinador no encontrado'); window.location.href='listarCoordinador.php';</script>";
    }
} else {
    echo "<script>alert('ID de coordinador no proporcionado'); window.location.href='listarCoordinador.php';</script>";
}
?>
