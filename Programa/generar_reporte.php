<?php
require('../fpdf/fpdf.php');
include "../complementos/conexion.php";

// Iniciar la sesión
session_start();
if (!isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: ../index.html");
    exit();
}

// Conectar a la base de datos
$conexion = conexion();
if (!$conexion) {
    die("Error de conexión a la base de datos");
}

// Consulta a la base de datos
$sql = "SELECT id_programa, codigo_SNIES, descripcion, correo_contacto, telefono_contacto, resolucion, fecha_generacion FROM programas";
$query = mysqli_query($conexion, $sql);

// Crear el PDF
$pdf = new FPDF();
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage('L'); // Cambiamos a orientación horizontal (Landscape)
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Listado de Programas', 0, 1, 'C');
$pdf->Ln(10);

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 10); // Tamaño de letra para encabezados
$pdf->SetFillColor(200, 220, 255);
$pdf->Cell(20, 7, 'ID', 1, 0, 'C', true);
$pdf->Cell(30, 7, 'Código SNIES', 1, 0, 'C', true);
$pdf->Cell(50, 7, 'Descripción', 1, 0, 'C', true);
$pdf->Cell(40, 7, 'Correo', 1, 0, 'C', true);
$pdf->Cell(30, 7, 'Teléfono', 1, 0, 'C', true);
$pdf->Cell(40, 7, 'Resolución', 1, 0, 'C', true);
$pdf->Cell(40, 7, 'Fecha', 1, 1, 'C', true);

// Datos de la tabla
$pdf->SetFont('Arial', '', 8); // Tamaño de letra para el contenido

// Función para obtener la altura máxima de cada fila
function getMaxHeight($row, $pdf) {
    // Calculamos las alturas de las celdas que varían en contenido
    $descripcionHeight = $pdf->GetStringWidth(utf8_decode($row['descripcion'])) > 50 ? 20 : 10;
    $correoHeight = $pdf->GetStringWidth(utf8_decode($row['correo_contacto'])) > 40 ? 20 : 10;
    $telefonoHeight = $pdf->GetStringWidth(utf8_decode($row['telefono_contacto'])) > 30 ? 20 : 10;
    $resolucionHeight = $pdf->GetStringWidth(utf8_decode($row['resolucion'])) > 40 ? 20 : 10;

    // Retornamos la altura máxima
    return max($descripcionHeight, $correoHeight, $telefonoHeight, $resolucionHeight, 10);
}

while ($row = mysqli_fetch_array($query)) {
    // Obtenemos la altura máxima de la fila
    $maxHeight = getMaxHeight($row, $pdf);

    // ID y Código SNIES
    $pdf->Cell(20, $maxHeight, $row['id_programa'], 1, 0, 'C');
    $pdf->Cell(30, $maxHeight, $row['codigo_SNIES'], 1, 0, 'C');
    
    // MultiCell para la descripción, manteniendo el orden en las otras celdas
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(50, 10, utf8_decode($row['descripcion']), 1);

    // Volvemos a la posición original para continuar con las demás celdas
    $pdf->SetXY($x + 50, $y);
    $pdf->Cell(40, $maxHeight, utf8_decode($row['correo_contacto']), 1, 0, 'C');
    $pdf->Cell(30, $maxHeight, utf8_decode($row['telefono_contacto']), 1, 0, 'C');
    $pdf->Cell(40, $maxHeight, utf8_decode($row['resolucion']), 1, 0, 'C');
    $pdf->Cell(40, $maxHeight, utf8_decode($row['fecha_generacion']), 1, 1, 'C');
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);

// Salida del PDF
$pdf->Output('D', 'reporte_programas.pdf');
?>
