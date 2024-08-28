<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se verifica si existe un valor para la , de lo contrario se muestra un mensaje.
if (isset($_GET['id'])) {
    require_once('../../models/data/codigo_data.php');
    require_once('../../models/data/llegadatarde_data.php');
    // Se instancian las entidades correspondientes.
    $codigo = new CodigoData;
    $tarde = new LlegadaData;
    // Se inicia el reporte con el encabezado del documento.
    $pdf->startReport('Reporte de conducta');
    // Se establece el valor de la categoría, de lo contrario se muestra un mensaje.
    if ($codigo->setEstudiante($_GET['id'])) {
        // Información general
        $pdf->setFont('Arial', 'B', 14);
        $pdf->MultiCell(190, 7, $pdf->encodeString('Este reporte muestra el reporte de conducta de los estudiantes, mostrando tanto los códigos como las llegadas tarde.'), 0, 1);
        $pdf->ln(5);

        $pdf->setFont('Arial', 'B', 14);
        $pdf->MultiCell(190, 7, $pdf->encodeString('Códigos'), 0, 1);

        // Se establece un color de relleno para los encabezados.
        $pdf->setFillColor(3, 8, 108);
        // Se establece la fuente para los encabezados.
        $pdf->setFont('Arial', 'B', 11);
        $pdf->setTextColor(255, 255, 255); // Color de texto blanco para los encabezados

        // Se imprimen las celdas con los encabezados.
        $pdf->cell(60, 10, 'Estudiante', 1, 0, 'C', 1);
        $pdf->cell(30, 10, 'Codigo', 1, 0, 'C', 1);
        $pdf->cell(60, 10, $pdf->encodeString('Descripción'), 1, 0, 'C', 1);
        $pdf->cell(30, 10, 'Fecha', 1, 1, 'C', 1);

        // Se establece un color de relleno para mostrar el nombre de la materia.
        $pdf->setFillColor(0, 106, 162);
        // Se establece la fuente para los datos de las estudiante.
        $pdf->setFont('Arial', '', 11);

        // Se recorren los registros fila por fila.
        $fill = false; // Alternancia de color de relleno
        $pdf->setTextColor(0, 0, 0); // Color de texto negro
        // Se establece la materia para obtener sus estudiante, de lo contrario se imprime un mensaje de error.
        if ($codigo->setEstudiante($_GET['id'])) {
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataestudiante = $codigo->codigosPorEstudiantes()) {
                // Se recorren los registros fila por fila.
                foreach ($dataestudiante as $rowEstudiante) {
                    // Se imprimen las celdas con los datos de las estudiante.
                    $pdf->setFillColor($fill ? 230 : 255); // Color de relleno gris más claro y blanco alternante
                    $pdf->cell(60, 10, $pdf->encodeString($rowEstudiante['estudiante']), 1, 0, 'C', $fill);
                    $pdf->cell(30, 10, $pdf->encodeString($rowEstudiante['codigo']), 1, 0, 'C', $fill);
                    $pdf->cell(60, 10, $pdf->encodeString($rowEstudiante['descripcion_adicional']), 1,0, 'C', $fill);
                    $pdf->cell(30, 10, $pdf->encodeString($rowEstudiante['fecha']), 1, 1, 'C', $fill);
                    // Alternar color de relleno
                    $fill = !$fill;
                }
            } else {
                $pdf->cell(180, 10, $pdf->encodeString('No hay códigos para este estudiante'), 1, 1);
            }
        } else {
            $pdf->cell(180, 10, $pdf->encodeString('Estudiante incorrecto o inexistente'), 1, 1);
        }
        $pdf->ln(15);

        // Información general
        $pdf->setFont('Arial', 'B', 14);
        $pdf->MultiCell(190, 7, $pdf->encodeString('Llegadas tarde.'), 0, 1);

        // Se establece un color de relleno para los encabezados.
        $pdf->setFillColor(3, 8, 108);
        // Se establece la fuente para los encabezados.
        $pdf->setFont('Arial', 'B', 11);
        $pdf->setTextColor(255, 255, 255); // Color de texto blanco para los encabezados

        // Se imprimen las celdas con los encabezados.
        $pdf->cell(60, 10, 'Estudiante', 1, 0, 'C', 1);
        $pdf->cell(60, 10, 'Materia', 1, 0, 'C', 1);
        $pdf->cell(30, 10, 'Fecha', 1, 0, 'C', 1);
        $pdf->cell(30, 10, 'Hora', 1, 1, 'C', 1);

        // Se establece un color de relleno para mostrar el nombre de la materia.
        $pdf->setFillColor(0, 106, 162);
        // Se establece la fuente para los datos de las estudiante.
        $pdf->setFont('Arial', '', 11);

        // Se recorren los registros fila por fila.
        $fill = false; // Alternancia de color de relleno
        $pdf->setTextColor(0, 0, 0); // Color de texto negro
        // Se establece la materia para obtener sus estudiante, de lo contrario se imprime un mensaje de error.
        if ($tarde->setEstudiante($_GET['id'])) {
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataestudiante2 = $tarde->llegadasTardePorEstudiante()) {
                // Se recorren los registros fila por fila.
                foreach ($dataestudiante2 as $rowEstudiante2) {
                    // Se imprimen las celdas con los datos de las estudiante.
                    $pdf->setFillColor($fill ? 230 : 255); // Color de relleno gris más claro y blanco alternante
                    $pdf->cell(60, 10, $pdf->encodeString($rowEstudiante2['estudiante']), 1, 0, 'C', $fill);
                    $pdf->cell(60, 10, $pdf->encodeString($rowEstudiante2['nombre']), 1, 0, 'C', $fill);
                    $pdf->cell(30, 10, $pdf->encodeString($rowEstudiante2['fecha']), 1, 0, 'C', $fill);
                    $pdf->cell(30, 10, $pdf->encodeString($rowEstudiante2['hora']), 1, 1, 'C', $fill);
                    // Alternar color de relleno
                    $fill = !$fill;
                }
            } else {
                $pdf->cell(180, 10, $pdf->encodeString('Este estudiante no tiene llegadas tardes'), 1, 1);
            }
        } else {
            $pdf->cell(180, 10, $pdf->encodeString('Estudiante incorrecto o inexistente'), 1, 1);
        }

        // Se llama implícitamente al método footer() y se envía el documento al navegador web.
        $pdf->output('I', 'estudiante_grado.pdf');
    } else {
        print ('Estudiante incorrecto');
    }
} else {
    print ('Debe seleccionar un estudiante');
}