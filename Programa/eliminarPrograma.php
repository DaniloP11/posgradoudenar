<?php
include "../complementos/conexion.php";

// Verificar si se ha enviado el ID del programa
if (!isset($_GET['id_programa'])) {
    die("ID del programa no especificado.");
}

$id_programa = intval($_GET['id_programa']); // Convertir a entero para mayor seguridad
$conexion = conexion();
if (!$conexion) {
    die("Error de conexión a la base de datos");
}

// Iniciar transacción
mysqli_begin_transaction($conexion);

try {
    // Consultar para obtener el nombre del logo del programa para eliminarlo
    $query = mysqli_query($conexion, "SELECT logo FROM programas WHERE id_programa = $id_programa");
    if (!$query) {
        throw new Exception("Error al ejecutar la consulta: " . mysqli_error($conexion));
    }
    $programa = mysqli_fetch_assoc($query);

    if (!$programa) {
        throw new Exception("Programa no encontrado.");
    }

    // Eliminar el archivo de logo si existe
    if ($programa['logo'] && file_exists("../uploads/" . $programa['logo'])) {
        if (!unlink("../uploads/" . $programa['logo'])) {
            throw new Exception("Error al eliminar el archivo de logo.");
        }
    }

    // Eliminar el registro del programa
    $query_delete_programa = mysqli_query($conexion, "DELETE FROM programas WHERE id_programa = $id_programa");
    if (!$query_delete_programa) {
        throw new Exception("Error al eliminar el programa: " . mysqli_error($conexion));
    }

    // Confirmar transacción
    mysqli_commit($conexion);

    echo "<script>
        alert('Programa eliminado correctamente');
        window.location.href = 'listarPrograma.php'; // Redirigir al listado después de la eliminación
    </script>";
} catch (Exception $e) {
    // Revertir transacción en caso de error
    mysqli_rollback($conexion);
    echo "<script>
        alert('Error: " . $e->getMessage() . "');
    </script>";
}

// Cerrar la conexión
mysqli_close($conexion);
?>
