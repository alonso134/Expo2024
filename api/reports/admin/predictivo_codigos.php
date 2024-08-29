<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Predicción de códigos');

$pdf->setFont('Arial', '', 11);
$pdf->MultiCell(190, 7, $pdf->encodeString('Este informe muestra la predicción del número de códigos que se verán en los próximos 3 meses basándose en los datos de comportamiento del último año.'), 0, 1);
$pdf->ln(5);
$pdf->setFont('Arial', 'B', 14);

// Se incluye la clase para la transferencia y acceso a datos.
require_once('../../models/data/codigo_data.php');
$comportamiento = new CodigoData();

try {
    $predicciones = $comportamiento->predictCodigosEnTresMeses();

    if (!empty($predicciones)) {
        // Establecer color de texto a blanco
        $pdf->setTextColor(255, 255, 255);
        // Se establece un color de relleno para los encabezados.
        $pdf->setFillColor(2, 8, 135);
        // Se establece el color del borde.
        $pdf->setDrawColor(2, 8, 135);
        // Se establece la fuente para los encabezados.
        $pdf->setFont('Arial', 'B', 11);
        // Se imprimen las celdas con los encabezados.
        $pdf->cell(120, 10, 'Fecha', 1, 0, 'C', 1);
        $pdf->cell(60, 10, 'Cantidad Predicha', 1, 1, 'C', 1);

        // Establecer fuente para los datos de los datos.
        $pdf->setFillColor(110, 151, 214);
        $pdf->setDrawColor(0, 0, 0);
        $pdf->setFont('Arial', '', 11);

        foreach ($predicciones as $row) {
            // Verifica si se ha creado una nueva página
            if ($pdf->getY() + 10 > 279 - 30) { // Ajusta este valor según el tamaño de tus celdas y la altura de la página
                // Establecer color de texto a blanco
                $pdf->setTextColor(255, 255, 255);
                $pdf->addPage('P', 'Letter'); // Añade una nueva página y con letter se define de tamaño carta
                $pdf->setTextColor(255, 255, 255);
                $pdf->setFillColor(2, 8, 135);
                $pdf->setDrawColor(2, 8, 135);
                $pdf->setFont('Arial', 'B', 11);
                // Vuelve a imprimir los encabezados en la nueva página
                $pdf->cell(120, 10, 'Fecha', 1, 0, 'C', 1);
                $pdf->cell(60, 10, 'Cantidad Predicha', 1, 1, 'C', 1);
                // Establecer color de texto a blanco
                $pdf->setTextColor(0, 0, 0);
            }

            $pdf->setTextColor(0, 0, 0);
            $pdf->setDrawColor(0, 0, 0);
            $pdf->cell(120, 10, $pdf->encodeString($row['fecha']), 1, 0, 'C');
            $pdf->cell(60, 10, round($row['cantidad_predicha']), 1, 1, 'C');
        }
    } else {
        $pdf->cell(0, 10, $pdf->encodeString('No hay datos disponibles para la predicción'), 1, 1, 'C');
    }
} catch (Exception $e) {
    // Mostrar un mensaje específico si ocurre una excepción
    $pdf->cell(0, 10, $pdf->encodeString('Error al realizar la predicción: ' . $e->getMessage()), 1, 1, 'C');
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'prediccion_codigos.pdf');
?>