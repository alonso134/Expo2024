<?php
// Se incluye la clase del modelo.
require_once('../../models/data/codigo_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $codigo = new CodigoData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idProfesor']) and Validator::validateSessionTime()) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $codigo->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$codigo->setEstudiante($_POST['nombreEstudiante']) or
                    !$codigo->setCodigo($_POST['TipoCodigo']) or
                    !$codigo->setProfesor($_POST['NombreProfesor']) or
                    !$codigo->setFecha($_POST['FechaCodigo']) or
                    !$codigo->setDescripcion($_POST['descripcionCodigo'])
                ) {
                    $result['error'] = $codigo->getDataError();
                } elseif ($codigo->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Comportamiento creado correctamente';

                } else {
                    $result['error'] = 'Ocurrió un problema al crear el Comportamiento';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $codigo->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen comportamientos registrados';
                }
                break;
            case 'codigosPorEstudiantes':
                if (!$codigo->setEstudiante($_POST['estudiante'])) {
                    $result['error'] = $codigo->getDataError();
                } elseif ($result['dataset'] = $codigo->codigosPorEstudiantesGrafica()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen comportamientos registrados';
                }
                break;
            case 'readOne':
                if (!$codigo->setId($_POST['idCodigo'])) {
                    $result['error'] = $codigo->getDataError();
                } elseif ($result['dataset'] = $codigo->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Comportamiento inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$codigo->setId($_POST['idCodigo']) or
                    !$codigo->setEstudiante($_POST['nombreEstudiante']) or
                    !$codigo->setCodigo($_POST['TipoCodigo']) or
                    !$codigo->setProfesor($_POST['NombreProfesor']) or
                    !$codigo->setFecha($_POST['FechaCodigo']) or
                    !$codigo->setDescripcion($_POST['descripcionCodigo'])
                ) {
                    $result['error'] = $codigo->getDataError();
                } elseif ($codigo->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Comportamiento modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el Comportamiento';
                }
                break;
            case 'deleteRow':
                if (
                    !$codigo->setId($_POST['idCodigo'])
                ) {
                    $result['error'] = $codigo->getDataError();
                } elseif ($codigo->deleteRow()) {
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
        print (json_encode($result));
    } else {
        print (json_encode('Acceso denegado'));
    }
} else {
    print (json_encode('Recurso no disponible'));
}
