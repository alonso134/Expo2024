<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/nota_data.php');
require_once('../../models/data/materia_data.php');
require_once('../../models/data/estudiante_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Notas por Materia');

// Se instancia el modelo Materia para obtener los datos.
$materia = new MateriaData;

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataMaterias = $materia->readAll()) {
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


    $fill = false; // Alternancia de color de relleno
    // Se recorren los registros fila por fila.
    foreach ($dataMaterias as $rowMateria) {
        // Se establece un color de relleno para mostrar el nombre de la materia.
        $pdf->setFillColor(0, 106, 162);
        // Se establece la fuente para los datos de las notas.
        $pdf->setFont('Arial', '', 11);
        // Se imprime una celda con el nombre de la materia.
        $pdf->setTextColor(255, 255, 255); // Color de texto negro
        // Explicación de funcionamiento de los valores de las celdas: 
        // (Ancho, Alto, Texto, Borde, Salto de linea, Alineación (Centrado = C, Izquierda = L, Derecha = R), Fondo, Link)
        $pdf->cell(180, 10, $pdf->encodeString('Materia: ' . $rowMateria['nombre']), 1, 1, 'C', 1);
        // Se instancia el modelo Notas para procesar los datos.
        $notas = new NotasData;
        $pdf->setTextColor(0, 0, 0); // Color de texto negro
        // Se establece la materia para obtener sus notas, de lo contrario se imprime un mensaje de error.
        if ($notas->setMaterias($rowMateria['id_materia'])) {
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataNotas = $notas->notasPorMateria()) {
                // Se recorren los registros fila por fila.
                foreach ($dataNotas as $rowNota) {
                    // Se imprimen las celdas con los datos de las notas.
                    $pdf->setFillColor($fill ? 230 : 255); // Color de relleno gris más claro y blanco alternante
                    // Se imprimen las celdas con los datos de las notas.
                    $pdf->cell(70, 10, $pdf->encodeString($rowNota['nombre_estudiante']), 1, 0, '', $fill);
                    $pdf->cell(30, 10, $rowNota['nota'], 1, 0, '', $fill);
                    $pdf->cell(50, 10, $pdf->encodeString($rowNota['trimestre']), 1, 0, '', $fill);
                    $pdf->cell(30, 10, $rowNota['fecha_calificacion'], 1, 1, '', $fill);
                    // Alternar color de relleno
                    $fill = !$fill;
                }
            } else {
                $pdf->cell(180, 10, $pdf->encodeString('No hay notas para la materia'), 1, 1);
            }
        } else {
            $pdf->cell(180, 10, $pdf->encodeString('Materia incorrecta o inexistente'), 1, 1);
        }
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay materias para mostrar'), 1, 1);
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'notas_materia.pdf');
