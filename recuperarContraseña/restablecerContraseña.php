<?php
include "../complementos/conexion.php";
session_start();

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}

// Recuperar el token del enlace
$token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);

if (!$token) {
    die('Token inválido.');
}

// Verificar el token en la base de datos
$stmt = $pdo->prepare("SELECT email FROM recuperar_contraseña WHERE token = ?");
$stmt->execute([$token]);
$reset = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reset) {
    die('Token de recuperación inválido o expirado.');
}

// Procesar el nuevo password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    
    if (!$new_password) {
        die('Contraseña inválida.');
    }
    
    // Actualizar la contraseña
    $email = $reset['email'];
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    
    // Actualizar la contraseña en la base de datos para ambos tipos de usuario
    $stmt = $pdo->prepare("UPDATE coordinadores SET contraseña = ? WHERE correo = ?");
    $stmt->execute([$hashed_password, $email]);

    $stmt = $pdo->prepare("UPDATE asistentes SET contraseña = ? WHERE correo = ?");
    $stmt->execute([$hashed_password, $email]);

    // Eliminar el token de recuperación
    $stmt = $pdo->prepare("DELETE FROM recuperar_contraseña WHERE token = ?");
    $stmt->execute([$token]);

    echo 'Contraseña actualizada exitosamente.';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
</head>
<body>
    <h1>Restablecer Contraseña</h1>
    <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="post">
        <label for="password">Nueva Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Actualizar Contraseña</button>
    </form>
</body>
</html>
