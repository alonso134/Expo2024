<?php
// Se incluye la clase del modelo.
require_once('../../models/data/estudiante_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $estudiantes = new EstudianteData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idProfesor'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $estudiantes->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$estudiantes->setNombre($_POST['nombreEstudiante']) or
                    !$estudiantes->setApellido($_POST['apellidoEstudiante']) or
                    !$estudiantes->setCorreo($_POST['correoEstudiante']) or
                    !$estudiantes->setClave($_POST['claveEstudiante']) or
                    !$estudiantes->setNacimiento($_POST['nacimientoEstudiante']) or
                    !$estudiantes->setGrado(($_POST['nombreGrado']))
                ) {
                    $result['error'] = $estudiantes->getDataError();
                } elseif ($estudiantes->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Estudiantes creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el Estudiantes';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $estudiantes->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen estudiantes registrados';
                }
                break;
            case 'readOne':
                if (!$estudiantes->setId($_POST['idEstudiante'])) {
                    $result['error'] = $estudiantes->getDataError();
                } elseif ($result['dataset'] = $estudiantes->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'estudiantes inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$estudiantes->setId($_POST['idEstudiante']) or
                    !$estudiantes->setNombre($_POST['nombreEstudiante']) or
                    !$estudiantes->setApellido($_POST['apellidoEstudiante']) or
                    !$estudiantes->setNacimiento($_POST['nacimientoEstudiante']) or
                    !$estudiantes->setGrado(($_POST['nombreGrado']))
                ) {
                    $result['error'] = $estudiantes->getDataError();
                } elseif ($estudiantes->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Estudiantes modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar al estudiante';
                }
                break;
            case 'deleteRow':
                if (
                    !$estudiantes->setId($_POST['idEstudiante']) 
                ) {
                    $result['error'] = $producto->getDataError();
                } elseif ($estudiantes->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Estudiante eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar al estudiante';
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
