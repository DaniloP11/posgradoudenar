<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Ajusta la ruta según la ubicación de autoload.php
require '../complementos/conexion.php'; // Ajusta la ruta según la ubicación de tu archivo de conexión

// Crear una instancia de PHPMailer
$mail = new PHPMailer(true);

try {
    // Recuperar el correo electrónico ingresado por el usuario
    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        // Crear una conexión a la base de datos
        $conexion = conexion();

        // Consultar si el correo existe en la tabla de coordinadores
        $consulta = "SELECT correo, contraseña FROM coordinadores WHERE correo = ?";
        $stmt = $conexion->prepare($consulta);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Verificar si se encontró el correo en coordinadores
        if ($fila = $resultado->fetch_assoc()) {
            $correo = $fila['correo'];
            $contraseña = $fila['contraseña']; // Suponiendo que la contraseña está en texto claro
        } else {
            // Si no está en coordinadores, buscar en la tabla de asistentes
            $consulta = "SELECT correo, contraseña FROM asistentes WHERE correo = ?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($fila = $resultado->fetch_assoc()) {
                $correo = $fila['correo'];
                $contraseña = $fila['contraseña']; // Suponiendo que la contraseña está en texto claro
            } else {
                // Si no se encontró en ninguna tabla, mostrar un mensaje de error
                echo "<script>alert('El correo ingresado no está registrado.');</script>";
                exit;
            }
        }

        // Si se encuentra el correo en alguna tabla, proceder a enviar el correo
        // Generar un token único
        $token = bin2hex(random_bytes(16));

        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'stdanilo06@gmail.com'; // Cambia esto por tu correo de Gmail
        $mail->Password   = 'nmyh dmda ukwf zhvs'; // Cambia esto por tu contraseña de Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remitente
        $mail->setFrom('stdanilo06@gmail.com', 'Stiven Paguay');

        // Añadir destinatario
        $mail->addAddress($correo);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Restablecimiento de Clave';
        $mail->Body = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Recuperar Contraseña - UDENAR</title>
            <style>
                body {
                    font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
                    background-color: #f0f0f5;
                    margin: 0;
                    padding: 20px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }
                .container {
                    background-color: #ffffff;
                    border-radius: 10px;
                    padding: 30px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    max-width: 500px;
                    width: 100%;
                    text-align: center;
                }
                h2 {
                    font-size: 24px;
                    color: #2c3e50;
                    margin-bottom: 20px;
                    text-align: center;
                }
                h1 {
                    font-size: 32px;
                    color: #34495e;
                    margin-bottom: 10px;
                    text-align: center;
                }
                p {
                    font-size: 16px;
                    color: #7f8c8d;
                    line-height: 1.6;
                    margin-bottom: 20px;
                    text-align: justify; /* Justificar texto */
                }
                .highlight {
                    background-color: #ecf0f1;
                    color: #2980b9;
                    padding: 15px;
                    border-radius: 5px;
                    font-weight: bold;
                    display: inline-block;
                    margin-bottom: 30px;
                    font-size: 18px;
                }
                .footer {
                    margin-top: 30px;
                    font-size: 12px;
                    color: #bdc3c7;
                }
                .btn {
                    background-color: #3498db;
                    color: #ffffff;
                    padding: 12px 20px;
                    border-radius: 5px;
                    text-decoration: none;
                    font-size: 16px;
                    transition: background-color 0.3s ease;
                }
                .btn:hover {
                    background-color: #2980b9;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Universidad De Nariño</h1>
                <h3>Recuperación de Contraseña</h3>
                <p>Hola $nombre,</p>
                <p>Hiciste una solicitud para recuperar tu contraseña. A continuación, te mostramos tu contraseña:</p>
                <div class="highlight">' . htmlspecialchars($contraseña) . '</div>
                <p>Si no solicitaste este correo, por favor ignóralo.</p>
                <p>Si necesitas más ayuda, puedes contactar con nuestro equipo de soporte.</p>

                <div class="footer">
                    &copy; ' . date('Y') . ' Universidad de Nariño. Todos los derechos reservados.
                </div>
            </div>
        </body>
        </html>
        ';

        // Enviar el correo
        $mail->send();

        echo "<script>alert('Correo enviado con éxito a " . htmlspecialchars($correo) . "');</script>";

        // Aquí podrías guardar el token en la base de datos si es necesario para validación futura
        $stmt = $conexion->prepare("INSERT INTO recuperar_contraseña (email, token) VALUES (?, ?)");
        $stmt->bind_param("ss", $correo, $token);
        $stmt->execute();
        
    } else {
        echo "<script>alert('No se ha proporcionado ningún correo electrónico.');</script>";
    }

} catch (Exception $e) {
    echo "<script>alert('Hubo un problema al enviar el correo. Inténtalo de nuevo más tarde. Error: {$mail->ErrorInfo}');</script>";
}

// Cerrar conexión
mysqli_close($conexion);
?>
