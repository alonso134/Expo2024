<?php
// Se incluye la clase del modelo.
require_once('../../models/data/inscripcion_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $inscripcion = new InscripcionData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idProfesor'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $inscripcion->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$inscripcion->setEstudiante($_POST['nombreEstudiante']) or
                    !$inscripcion->setMaterias($_POST['NombreMaterias']) 
                ) {
                    $result['error'] = $inscripcion->getDataError();
                } elseif ($inscripcion->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Inscripcion creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el Inscripcion';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $inscripcion->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen inscripciones registradas';
                }
                break;
            case 'readOne':
                if (!$inscripcion->setId($_POST['idInscpricion'])) {
                    $result['error'] = $inscripcion->getDataError();
                } elseif ($result['dataset'] = $inscripcion->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'inscripciones inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$inscripcion->setId($_POST['idInscpricion']) or
                    !$inscripcion->setEstudiante($_POST['nombreEstudiante']) or
                    !$inscripcion->setMaterias($_POST['NombreMaterias']) 
                ) {
                    $result['error'] = $inscripcion->getDataError();
                } elseif ($inscripcion->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Inscripcion modificada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un error al modificar la Inscripcion';
                }
                break;
            case 'deleteRow':
                if (
                    !$inscripcion->setId($_POST['idInscpricion'])
                ) {
                    $result['error'] = $inscripcion->getDataError();
                } elseif ($inscripcion->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Inscripcion eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar la inscripcion';
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
