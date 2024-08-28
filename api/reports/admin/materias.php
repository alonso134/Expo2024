<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/materia_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Materias');

// Se instancia el modelo Materia para obtener los datos.
$materia = new MateriaData;

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataMaterias = $materia->readAll()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(3, 8, 108);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    $pdf->setTextColor(255, 255, 255); // Color de texto blanco para los encabezados

    // Se imprimen las celdas con los encabezados, ajustando el tamaño.
    $pdf->cell(55, 10, 'Nombre', 1, 0, 'C', 1);
    $pdf->cell(70, 10, 'Descripcion', 1, 0, 'C', 1);
    $pdf->cell(55, 10, 'Profesor', 1, 1, 'C', 1);

    // Se establece la fuente para los datos de las materias.
    $pdf->setFont('Arial', '', 11);
    $pdf->setTextColor(0, 0, 0); // Color de texto negro

    $fill = false; // Alternancia de color de relleno
    // Se recorren los registros fila por fila.
    foreach ($dataMaterias as $row) {
        // Se imprimen las celdas con los datos de las materias, ajustando el tamaño.
        $pdf->setFillColor($fill ? 230 : 255); // Color de relleno gris más claro y blanco alternante
        $pdf->cell(55, 10, $pdf->encodeString($row['nombre']), 1, 0, 'C', $fill);
        $pdf->cell(70, 10, $pdf->encodeString($row['descripcion']), 1, 0, 'C', $fill);
        $pdf->cell(55, 10, $pdf->encodeString($row['nombre_profesor']), 1, 1, 'C', $fill);
        // Alternar color de relleno
        $fill = !$fill;
    }
} else {
    $pdf->cell(180, 10, $pdf->encodeString('No hay materias para mostrar'), 1, 1);
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'materias.pdf');
