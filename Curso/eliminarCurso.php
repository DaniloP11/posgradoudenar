<?php
include "../complementos/conexion.php";
session_start();

// Verificar que el usuario está logueado
if (!isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: ../index.html");
    exit();
}

// Conectar a la base de datos
$conexion = conexion();
if (!$conexion) {
    echo "<script>alert('Error de conexión a la base de datos'); window.location.href='listarCurso.php';</script>";
    exit();
}

// Verificar si se ha enviado un ID de curso para eliminar
if (isset($_GET['id'])) {
    $id_curso = intval($_GET['id']);

    // Preparar la consulta para eliminar el curso
    $deleteQuery = "DELETE FROM cursos WHERE id_curso = ?";
    $stmt = mysqli_prepare($conexion, $deleteQuery);
    
    if (!$stmt) {
        echo "<script>alert('Error al preparar la consulta'); window.location.href='listarCurso.php';</script>";
        mysqli_close($conexion);
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $id_curso);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Curso eliminado correctamente'); window.location.href='listarCurso.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el curso'); window.location.href='listarCurso.php';</script>";
    }

    // Cerrar la declaración y la conexión
    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
} else {
    echo "<script>alert('ID de curso no especificado'); window.location.href='listarCurso.php';</script>";
    exit();
}
?>
