<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se verifica si existe un valor para la identificación del estudiante, de lo contrario se muestra un mensaje.
if (isset($_GET['id'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../../models/data/nota_data.php');
    // Se instancian las entidades correspondientes.
    $notas = new NotasData;
    $notas->setEstudiante($_GET['id']); // Suponiendo que 'setEstudiante' configura el ID del estudiante

    // Se inicia el reporte con el encabezado del documento.
    $pdf->startReport('Predicción de Notas Promedio');

    // Información general
    $pdf->setFont('Arial', '', 11);
    $pdf->MultiCell(190, 7, $pdf->encodeString('Este informe muestra la predicción de las notas promedio posibles del estudiante durante la siguiente semana, basadas en las notas recibidas en los últimos tres meses.'), 0, 1);
    $pdf->ln(5);
    $pdf->setFont('Arial', 'B', 14);

    try {
        // Obtener las predicciones
        $predictions = $notas->predictNotasPromedioPorMateriaSiguienteSemana();

        if (!empty($predictions)) {
            // Establecer color de texto a blanco
            $pdf->setTextColor(255, 255, 255);
            // Se establece un color de relleno para los encabezados.
            $pdf->setFillColor(2, 8, 135);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Arial', 'B', 11);
            // Se imprimen las celdas con los encabezados.
            $pdf->cell(60, 10, 'Materia', 1, 0, 'C', 1);
            $pdf->cell(60, 10, 'Fecha', 1, 0, 'C', 1);
            $pdf->cell(60, 10, $pdf->encodeString('Predicción Nota Promedio'), 1, 1, 'C', 1);

            // Establecer la fuente para los datos de los datos.
            $pdf->setFillColor(110, 151, 214);
            $pdf->setDrawColor(0, 0, 0);
            $pdf->setFont('Arial', '', 11);

            // Se establece un color de relleno para mostrar el nombre de la materia.
            $pdf->setFillColor(0, 106, 162);
            // Se establece la fuente para los datos de las notas.
            $pdf->setFont('Arial', '', 11);
            // Se imprime una celda con el nombre de la materia.
            $pdf->setTextColor(255, 255, 255); // Color de texto negro
            $pdf->cell(180, 10, $pdf->encodeString('Estudiante elegido para la predicción: ' . $predictions[0]['estudiante']), 1, 1, 'C', 1);

            // Establecer color de texto a negro
            $pdf->setTextColor(0, 0, 0);
            // Imprimir las predicciones
            foreach ($predictions as $prediction) {
                // Verifica si se ha creado una nueva página
                if ($pdf->getY() + 10 > 279 - 30) { // Ajusta este valor según el tamaño de tus celdas y la altura de la página
                    $pdf->addPage('P', 'Letter'); // Añade una nueva página y con letter se define de tamaño carta
                    // Vuelve a imprimir los encabezados en la nueva página
                    $pdf->setTextColor(255, 255, 255);
                    $pdf->setFillColor(2, 8, 135);
                    $pdf->setDrawColor(2, 8, 135);
                    $pdf->setFont('Arial', 'B', 11);
                    $pdf->cell(60, 10, 'Materia', 1, 0, 'C', 1);
                    $pdf->cell(60, 10, 'Fecha', 1, 0, 'C', 1);
                    $pdf->cell(60, 10, $pdf->encodeString('Predicción Nota Promedio'), 1, 1, 'C', 1);
                    // Establecer color de texto a negro
                    $pdf->setTextColor(0, 0, 0);
                }
                // Se establecen los colores de las celdas
                $pdf->setDrawColor(0, 0, 0);
                $pdf->cell(60, 10, $pdf->encodeString($prediction['materia']), 1, 0, 'C');
                $pdf->cell(60, 10, $pdf->encodeString($prediction['fecha']), 1, 0, 'C');
                $pdf->cell(60, 10, round($prediction['promedio_nota'], 2), 1, 1, 'C');
            }
        } else {
            $pdf->cell(0, 10, $pdf->encodeString('No hay predicción disponible para este estudiante'), 1, 1, 'C');
        }
    } catch (Exception $e) {
        // Mostrar un mensaje específico si ocurre una excepción
        $pdf->cell(0, 10, $pdf->encodeString('No hay datos suficientes para realizar la predicción'), 1, 1, 'C');
    }

    // Se llama implícitamente al método footer() y se envía el documento al navegador web.
    $pdf->output('I', 'prediccion_notas_estudiante.pdf');
} else {
    print ('Debe seleccionar un estudiante');
}
?>