<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/estudiante_data.php');

// Se instancian las entidades correspondientes.
$estudiantes = new EstudianteHandler;

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataEstudiantes = $estudiantes->readAll()) {
    // Se inicia el reporte con el encabezado del documento.
    $pdf->startReport('Listado de Estudiantes');

    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(3, 8, 108);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    $pdf->setTextColor(255, 255, 255); // Color de texto blanco para los encabezados

    // Se imprimen las celdas con los encabezados.
    $pdf->cell(40, 10, 'Nombre', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Apellido', 1, 0, 'C', 1);
    $pdf->cell(60, 10, 'Correo', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Nacimiento', 1, 1, 'C', 1);

    // Se establece la fuente para los datos de los administradores.
    $pdf->setFont('Arial', '', 11);
    $pdf->setTextColor(0, 0, 0); // Color de texto negro

    // Se recorren los registros fila por fila.
    $fill = false; // Alternancia de color de relleno
    foreach ($dataEstudiantes as $rowEstudiantes) {
        // Se imprimen las celdas con los datos de los administradores.
        $pdf->setFillColor($fill ? 230 : 255); // Color de relleno gris más claro y blanco alternante
        $pdf->cell(40, 10, $pdf->encodeString($rowEstudiantes['nombre_estudiante']), 1, 0, '', $fill);
        $pdf->cell(40, 10, $pdf->encodeString($rowEstudiantes['apellido_estudiante']), 1, 0, '', $fill);
        $pdf->cell(60, 10, $pdf->encodeString($rowEstudiantes['correo_estudiante']), 1, 0, '', $fill);
        $pdf->cell(40, 10, $pdf->encodeString($rowEstudiantes['fecha_de_nacimiento']), 1, 1, '', $fill);
        // Alternar color de relleno
        $fill = !$fill;
    }

    // Se llama implícitamente al método footer() y se envía el documento al navegador web.
    $pdf->output('I', 'alumnos.pdf');
} else {
    print('No hay alumnos para mostrar');
}


