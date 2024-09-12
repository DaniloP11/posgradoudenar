<?php
function conexion() {
    $host = 'localhost';
    $usuario = 'root'; // Cambia esto si usas un usuario diferente
    $contraseña = ''; // Cambia esto si usas una contraseña diferente
    $basedatos = 'maestriaudenar';

    $conexion = mysqli_connect($host, $usuario, $contraseña, $basedatos);

    if (mysqli_connect_errno()) {
        echo "Error de conexión: " . mysqli_connect_error();
        exit();
    }

    return $conexion;
}
?>
