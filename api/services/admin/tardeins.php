<?php
// Se incluye la clase del modelo.
require_once('../../models/data/tardeins_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $tardeins = new TardeinsData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idProfesor'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $tardeins->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$tardeins->setfecha($_POST['fechaAsistencia']) or
                    !$tardeins->setHora($_POST['notasEstudiante']) or
                    !$tardeins->setProfesor($_POST['NombreProfesor']) or
                    !$tardeins->setEstado(isset($_POST['estadoAusencia']) ? 1 : 0)
                ) {
                    $result['error'] = $tardeins->getDataError();
                } elseif ($tardeins->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'la llegada asignada correctamente';
                
                } else {
                    $result['error'] = 'Ocurrió un problema al crear la tardeins';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $tardeins->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen ausencia registrados';
                }
                break;
            case 'readOne':
                if (!$tardeins->setId($_POST['idAusencia'])) {
                    $result['error'] = $tardeins->getDataError();
                } elseif ($result['dataset'] = $tardeins->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Ausencia inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$tardeins->setId($_POST['idAusencia']) or
                    !$tardeins->setfecha($_POST['fechaAsistencia']) or
                    !$tardeins->setHora($_POST['notasEstudiante']) or
                    !$tardeins->setProfesor($_POST['NombreProfesor']) or
                    !$tardeins->setEstado(isset($_POST['estadoAusencia']) ? 1 : 0)
                ) {
                    $result['error'] = $tardeins->getDataError();
                } elseif ($tardeins->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'llegada modificada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar la ausencia';
                }
                break;
            case 'deleteRow':
                if (
                    !$tardeins->setId($_POST['idAusencia']) 
                ) {
                    $result['error'] = $tardeins->getDataError();
                } elseif ($tardeins->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'ausencia eliminada correctamente';

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
