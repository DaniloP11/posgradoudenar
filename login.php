<?php
include "complementos/conexion.php";

session_start();
$email = $_POST['email'];
$PASSWORD = $_POST['password'];
$ROL = $_POST['rol'];

// Mapear los roles a las tablas y columnas
$tables = [
    '1' => ['table' => 'administrador', 'email' => 'admin_email', 'password' => 'admin_clave'],
    '2' => ['table' => 'asistentes', 'email' => 'correo', 'password' => 'contraseña'],
    '3' => ['table' => 'coordinadores', 'email' => 'correo', 'password' => 'contraseña']
];

$tableData = $tables[$ROL] ?? null;

if ($tableData) {
    $table = $tableData['table'];
    $emailColumn = $tableData['email'];
    $passwordColumn = $tableData['password'];

    // Preparar y ejecutar la consulta SQL
    $conexion = conexion();
    if (!$conexion) {
        die("Error de conexión a la base de datos.");
    }

    // Consulta SQL
    if ($ROL == "1") {
        $consulta = "SELECT * FROM $table WHERE $emailColumn = ? AND $passwordColumn = ?";
    } else {
        $consulta = "SELECT *, id_programa FROM $table WHERE $emailColumn = ? AND $passwordColumn = ?";
    }

    $stmt = mysqli_prepare($conexion, $consulta);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . mysqli_error($conexion));
    }

    mysqli_stmt_bind_param($stmt, 'ss', $email, $PASSWORD);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $filas = mysqli_num_rows($resultado);

    if ($filas > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        $_SESSION["email"] = $email;
        $_SESSION["rol"] = $ROL;

        // Solo almacenar id_programa si no es administrador
        if ($ROL != "1") {
            $_SESSION["id_programa"] = $usuario['id_programa']; 
        }

        // Redirigir según el rol
        if ($ROL == "1") {
            header("Location: ./Admin/InicioAdmi.php");
        } elseif ($ROL == "2") {
            header("Location: Asistente/InicioAsiste.php");
        } elseif ($ROL == "3") {
            header("Location: Coordinador/InicioCoord.php");
        }
    } else {
        echo "<script> 
            alert('El rol del usuario no es el correspondiente.');
            window.location.href = 'index.html'; 
        </script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}
?>
