<?php
// Se incluye la clase del modelo.
require_once('../../models/data/nota_data.php');


// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $notas = new NotasData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idProfesor'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $grado->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$notas->setEstudiante($_POST['nombreEstudiante']) or
                    !$notas->setMaterias($_POST['nombreMateria']) or
                    !$notas->setNota($_POST['notasEstudiante']) or
                    !$notas->setTrimestre($_POST['trimestreNota']) or
                    !$notas->setFecha($_POST['fechaNota'])
                ) {
                    $result['error'] = $notas->getDataError();
                } elseif ($notas->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Nota creada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear la nota';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $notas->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen notas registradas';
                }
                break;
            case 'notasPorEstudiante':
                if (!$notas->setEstudiante($_POST['estudiante'])) {
                    $result['error'] = $notas->getDataError();
                } elseif ($result['dataset'] = $notas->notasPorEstudiante()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen notas registradas';
                }
                break;
            case 'readOne':
                if (!$notas->setId($_POST['idNota'])) {
                    $result['error'] = $notas->getDataError();
                } elseif ($result['dataset'] = $notas->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Nota inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$notas->setId($_POST['idNota']) or
                    !$notas->setEstudiante($_POST['nombreEstudiante']) or
                    !$notas->setMaterias($_POST['nombreMateria']) or
                    !$notas->setNota($_POST['notasEstudiante']) or
                    !$notas->setTrimestre($_POST['trimestreNota']) or
                    !$notas->setFecha($_POST['fechaNota'])
                ) {
                    $result['error'] = $notas->getDataError();
                } elseif ($notas->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Nota modificada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar la nota';
                }
                break;
            case 'deleteRow':
                if (!$notas->setId($_POST['idNota'])) {
                    $result['error'] = $notas->getDataError();
                } elseif ($notas->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Nota eliminada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar la nota';
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

