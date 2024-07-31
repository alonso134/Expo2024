<?php
// Se incluye la clase del modelo.
require_once('../../models/data/asistencia_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $asistencia = new AsistenciaData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idProfesor'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $asistencia->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$asistencia->setEstudiante($_POST['nombreEstudiante']) or
                    !$asistencia->setfecha($_POST['fechaAsistencia']) or
                    !$asistencia->setProfesor($_POST['NombreProfesor']) or
                    !$asistencia->setEstado(isset($_POST['estadoAsistencia']) ? 1 : 0)
                ) {
                    $result['error'] = $asistencia->getDataError();
                } elseif ($asistencia->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Comportamiento creado correctamente';
                
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el Comportamiento';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $asistencia->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen comportamientos registrados';
                }
                break;
            case 'readOne':
                if (!$asistencia->setId($_POST['idAsistencia'])) {
                    $result['error'] = $asistencia->getDataError();
                } elseif ($result['dataset'] = $asistencia->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Comportamiento inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$asistencia->setId($_POST['idAsistencia']) or
                    !$asistencia->setEstudiante($_POST['nombreEstudiante']) or
                    !$asistencia->setfecha($_POST['fechaAsistencia']) or
                    !$asistencia->setProfesor($_POST['NombreProfesor']) or
                    !$asistencia->setEstado(isset($_POST['estadoAsistencia']) ? 1 : 0) 
                ) {
                    $result['error'] = $asistencia->getDataError();
                } elseif ($asistencia->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Comportamiento modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el Comportamiento';
                }
                break;
            case 'deleteRow':
                if (
                    !$asistencia->setId($_POST['idAsistencia']) 
                ) {
                    $result['error'] = $asistencia->getDataError();
                } elseif ($asistencia->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Comportamiento eliminado correctamente';

                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el Comportamiento';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }
        // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
        $result['exception'] = Database::getException();
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('Content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
