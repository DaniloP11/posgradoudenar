<?php
include "../complementos/conexion.php";

if (isset($_GET['id_asistente'])) {
    $id_asistente = intval($_GET['id_asistente']); // Obtener el ID del asistente de la URL

    // Primero obtenemos la información del asistente para eliminar la fotografía si existe
    $query = mysqli_query(conexion(), "SELECT fotografia FROM asistentes WHERE id_asistente = $id_asistente");
    $row = mysqli_fetch_assoc($query);

    if ($row) {
        // Eliminar la fotografía del servidor si existe
        $fotografia = $row['fotografia'];
        if ($fotografia && file_exists("../Asistente/uploads/$fotografia")) {
            unlink("../Asistente/uploads/$fotografia");
        }

        // Ahora eliminamos el registro completo de la base de datos
        $query_delete = mysqli_query(conexion(), "DELETE FROM asistentes WHERE id_asistente = $id_asistente");

        if ($query_delete) {
            echo "<script>alert('Asistente eliminado correctamente'); window.location.href='listarAsistente.php';</script>";
        } else {
            echo "<script>alert('Error al eliminar el asistente'); window.location.href='listarAsistente.php';</script>";
        }
    } else {
        echo "<script>alert('El asistente no existe'); window.location.href='listarAsistente.php';</script>";
    }
} else {
    echo "<script>alert('ID de asistente no proporcionado'); window.location.href='listarAsistente.php';</script>";
}
?>
