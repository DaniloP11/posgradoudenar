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

    $consulta = "SELECT * FROM $table WHERE $emailColumn = ? AND $passwordColumn = ?";
    $stmt = mysqli_prepare($conexion, $consulta);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . mysqli_error($conexion));
    }

    mysqli_stmt_bind_param($stmt, 'ss', $email, $PASSWORD);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $filas = mysqli_num_rows($resultado);

    if ($filas > 0) {
        // Guardar el rol y redirigir según el rol
        $_SESSION["email"] = $email;
        $_SESSION["rol"] = $ROL;
        // Redirigir según el rol
        if ($ROL == "1") {
            $_SESSION["email"] = $email;
            header("Location: ./Admin/InicioAdmi.php");
        } else if ($ROL == "2") {
            $_SESSION["email"] = $email;
            header("Location: Asistente/InicioAsiste.php");
        } else if ($ROL == "3") {
            $_SESSION["email"] = $email;
            header("Location: Coordinador/InicioCoord.php");
        }
    } else {
        // Mostrar el alert y redirigir a index.html
        echo "<script> 
            alert('El rol del usuario no es el correspondiente.');
            window.location.href = 'index.html'; 
        </script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}

?>


