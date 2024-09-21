<?php
include "../complementos/conexion.php";
session_start();

// Verificar que el usuario está logueado
if (!isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: ../index.html");
    exit();
}

require_once('../fpdf/fpdf.php');

$conexion = conexion();
if (!$conexion) {
    die("Error de conexión a la base de datos");
}

$id_programa = $_SESSION['id_programa'];
$rol = $_SESSION['rol'];

// Consulta para obtener los datos
$sql = "SELECT cohortes.id_cohorte, cohortes.nombre, cohortes.fecha_inicio, cohortes.fecha_fin, programas.descripcion, GROUP_CONCAT(docentes.nombre SEPARATOR ', ') AS nombres_docentes
        FROM cohortes 
        JOIN programas ON cohortes.id_programa = programas.id_programa
        LEFT JOIN docentes ON cohortes.id_cohorte = docentes.id_cohorte
        WHERE cohortes.id_programa = ?
        GROUP BY cohortes.id_cohorte";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id_programa);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Cohortes', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'ID', 1);
$pdf->Cell(50, 10, 'Cohorte', 1);
$pdf->Cell(40, 10, 'Fecha de Inicio', 1);
$pdf->Cell(40, 10, 'Fecha de Fin', 1);
$pdf->Cell(40, 10, 'Programa', 1);
$pdf->Cell(50, 10, 'Docente', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
while ($row = mysqli_fetch_array($query)) {
    $pdf->Cell(30, 10, $row['id_cohorte'], 1);
    $pdf->Cell(50, 10, $row['nombre'], 1);
    $pdf->Cell(40, 10, $row['fecha_inicio'], 1);
    $pdf->Cell(40, 10, $row['fecha_fin'], 1);
    $pdf->Cell(40, 10, $row['descripcion'], 1);
    $pdf->Cell(50, 10, $row['nombres_docentes'], 1);
    $pdf->Ln();
}

// Salida del PDF
$pdf->Output('D', 'reporte_cohortes.pdf');
?>
