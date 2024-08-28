<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se verifica si existe un valor para la , de lo contrario se muestra un mensaje.
if (isset($_GET['id'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../../models/data/nota_data.php');
    // Se instancian las entidades correspondientes.
    $notas = new NotasData;
    // Se inicia el reporte con el encabezado del documento.
    $pdf->startReport('Notas de una materia');
    // Información general
    $pdf->setFont('Arial', 'B', 14);
    $pdf->MultiCell(190, 7, $pdf->encodeString('Este reporte muestra todas las notas ingresadas de una materia en especifico.'), 0, 1);
    $pdf->ln(5);

    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(3, 8, 108);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    $pdf->setTextColor(255, 255, 255); // Color de texto blanco para los encabezados

    // Se imprimen las celdas con los encabezados.
    $pdf->cell(70, 10, 'Estudiante', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Nota', 1, 0, 'C', 1);
    $pdf->cell(50, 10, 'Trimestre', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Fecha', 1, 1, 'C', 1);

    // Se establece un color de relleno para mostrar el nombre de la materia.
    $pdf->setFillColor(0, 106, 162);
    // Se establece la fuente para los datos de las notas.
    $pdf->setFont('Arial', '', 11);

    // Se recorren los registros fila por fila.
    $fill = false; // Alternancia de color de relleno
    // Se establece el valor de la categoría, de lo contrario se muestra un mensaje.
    if ($notas->setMaterias($_GET['id'])) {
        $pdf->setTextColor(0, 0, 0); // Color de texto negro
        // Se establece la materia para obtener sus notas, de lo contrario se imprime un mensaje de error.
        if ($notas->setMaterias($_GET['id'])) {
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataNotas = $notas->notasPorMateria()) {
                // Se recorren los registros fila por fila.
                foreach ($dataNotas as $rowNota) {
                    // Se imprimen las celdas con los datos de las notas.
        $pdf->setFillColor($fill ? 230 : 255); // Color de relleno gris más claro y blanco alternante
                    $pdf->cell(70, 10, $pdf->encodeString($rowNota['nombre_estudiante']), 1, 0,'', $fill);
                    $pdf->cell(30, 10, $rowNota['nota'], 1, 0,'', $fill);
                    $pdf->cell(50, 10, $pdf->encodeString($rowNota['trimestre']), 1, 0,'', $fill);
                    $pdf->cell(30, 10, $rowNota['fecha_calificacion'], 1, 1,'', $fill);
                    // Alternar color de relleno
                    $fill = !$fill;
                }
            } else {
                $pdf->cell(180, 10, $pdf->encodeString('No hay notas para la materia'), 1, 1);
            }
        } else {
            $pdf->cell(180, 10, $pdf->encodeString('Materia incorrecta o inexistente'), 1, 1);
        }
        // Se llama implícitamente al método footer() y se envía el documento al navegador web.
        $pdf->output('I', 'notas_materia.pdf');
    } else {
        print ('Materia incorrecta');
    }
} else {
    print ('Debe seleccionar una materia');
}