<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/asignar_data.php');

// Se instancian las entidades correspondientes.
$codigo = new AsignarHandler;

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataCodigos = $codigo->readAll()) {
    // Se inicia el reporte con el encabezado del documento.
    $pdf->startReport('Listado de codigos');

    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(3, 8, 108);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 12);
    $pdf->setTextColor(255, 255, 255); // Color de texto blanco para los encabezados

    // Se imprimen las celdas con los encabezados.
    $pdf->cell(80, 10, 'Codigo', 1, 0, 'C', 1);
    $pdf->cell(100, 10, 'Descripcion', 1, 1, 'C', 1);

    // Se establece la fuente para los datos.
    $pdf->setFont('Arial', '', 11);
    $pdf->setTextColor(0, 0, 0); // Color de texto negro

    // Se recorren los registros fila por fila.
    $fill = false; // Alternancia de color de relleno
    foreach ($dataCodigos as $rowCodigos) {
        // Se imprimen las celdas con los datos.
        $pdf->setFillColor($fill ? 230 : 255); // Color de relleno gris claro y blanco alternante
        $pdf->cell(80, 10, $pdf->encodeString($rowCodigos['codigo']), 1, 0, 'C', $fill);
        $pdf->cell(100, 10, $pdf->encodeString($rowCodigos['descripcion']), 1, 1, 'L', $fill);
        // Alternar color de relleno
        $fill = !$fill;
    }

    // Se llama implícitamente al método footer() y se envía el documento al navegador web.
    $pdf->output('I', 'codigos.pdf');
} else {
    print('No hay codigos para mostrar');
}