<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se verifica si existe un valor para la asistencia, de lo contrario se muestra un mensaje.
if (isset($_GET['id'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../../models/data/asistencia_data.php');
    
    // Se instancian las entidades correspondientes.
    $asistencias = new AsistenciaData;
    
    // Se inicia el reporte con el encabezado del documento.
    $pdf->startReport('Reporte de Asistencias');
    
    // Información general
    $pdf->setFont('Arial', 'B', 14);
    $pdf->MultiCell(190, 7, $pdf->encodeString('Este reporte muestra todas las asistencias registradas para un estudiante específicado.'), 0, 1);
    $pdf->ln(5);

    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(3, 8, 108);
    
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    $pdf->setTextColor(255, 255, 255); // Color de texto blanco para los encabezados

    // Se imprimen las celdas con los encabezados.
    $pdf->cell(55, 10, 'Estudiante', 1, 0, 'C', 1);
    $pdf->cell(70, 10, 'Fecha', 1, 0, 'C', 1);
    $pdf->cell(55, 10, 'Estado', 1, 1, 'C', 1);

    // Se establece un color de relleno para las filas alternadas.
    $pdf->setFillColor(230, 230, 230);
    // Se establece la fuente para los datos de las asistencias.
    $pdf->setFont('Arial', '', 11);

    // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
    if ($asistencias->setEstudiante($_GET['id'])) {
        $pdf->setTextColor(0, 0, 0); // Color de texto negro
        if ($dataAsistencias = $asistencias->asistenciasEstudiantes()) {
            $fill = false; // Alternancia de color de relleno
            foreach ($dataAsistencias as $rowAsistencia) {
                // Se imprimen las celdas con los datos de las asistencias.
                $pdf->cell(55, 10, $pdf->encodeString($rowAsistencia['nombre_estudiante']), 1, 0, '', $fill);
                $pdf->cell(70, 10, $rowAsistencia['fecha'], 1, 0, '', $fill);
                $pdf->cell(55, 10, $pdf->encodeString($rowAsistencia['estado']), 1, 1, '', $fill);
                // Alternar color de relleno
                $fill = !$fill;
            }
        } else {
            $pdf->cell(180, 10, $pdf->encodeString('No hay asistencias registradas para este estudiante'), 1, 1);
        }
    } else {
        $pdf->cell(180, 10, $pdf->encodeString('Clase incorrecta o inexistente'), 1, 1);
    }
    // Se llama implícitamente al método footer() y se envía el documento al navegador web.
    $pdf->output('I', 'reporte_asistencias.pdf');
} else {
    print ('Debe seleccionar una clase');
}
