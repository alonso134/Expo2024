<?php
// Se incluye la clase del modelo.
require_once('../../models/data/observaciones_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $observaciones = new ObservacionData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idProfesor']) and Validator::validateSessionTime()) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $observaciones->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$observaciones->setEstudiante($_POST['nombreEstudiante']) or
                    !$observaciones->setprofesor($_POST['nombreProfesor']) or
                    !$observaciones->setObservacion($_POST['ObservacionEstudiante']) or
                    !$observaciones->setfecha($_POST['fechaEstudiante'])

                ) {
                    $result['error'] = $observaciones->getDataError();
                } elseif ($observaciones->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Observacion creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear la Observacion';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $observaciones->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen observaciones registrados';
                }
                break;
            case 'observacionesPorEstudiante':
                if (!$observaciones->setEstudiante($_POST['estudiante'])) {
                    $result['error'] = $observaciones->getDataError();
                } elseif ($result['dataset'] = $observaciones->ObservacionesPorEstudiantesGrafica()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen observaciones registradas';
                }
                break;
            case 'readOne':
                if (!$observaciones->setId($_POST['idObservaciones'])) {
                    $result['error'] = $observaciones->getDataError();
                } elseif ($result['dataset'] = $observaciones->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Observacion inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$observaciones->setId($_POST['idObservaciones']) or
                    !$observaciones->setEstudiante($_POST['nombreEstudiante']) or
                    !$observaciones->setprofesor($_POST['nombreProfesor']) or
                    !$observaciones->setObservacion($_POST['ObservacionEstudiante']) or
                    !$observaciones->setfecha($_POST['fechaEstudiante'])

                ) {
                    $result['error'] = $observaciones->getDataError();
                } elseif ($observaciones->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Observacion modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar la observacion';
                }
                break;
            case 'deleteRow':
                if (
                    !$observaciones->setId($_POST['idObservaciones'])
                ) {
                    $result['error'] = $producto->getDataError();
                } elseif ($observaciones->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Observacion eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar la Observacion';
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
        print (json_encode($result));
    } else {
        print (json_encode('Acceso denegado'));
    }
} else {
    print (json_encode('Recurso no disponible'));
}
