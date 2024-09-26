<?php
// Se incluye la clase del modelo.
require_once('../../models/data/profesores_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $profesor = new ProfesorData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como profesor, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idProfesor'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un profesor ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $profesor->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$profesor->setNombre($_POST['nombreProfesor']) or
                    !$profesor->setApellido($_POST['apellidoProfesor']) or
                    !$profesor->setCorreo($_POST['correoProfesor']) or
                    !$profesor->setAlias($_POST['aliasProfesor']) or
                    !$profesor->setClave($_POST['claveProfesor'], $_POST['aliasProfesor'])
                ) {
                    $result['error'] = $profesor->getDataError();
                } elseif ($_POST['claveProfesor'] != $_POST['confirmarClave']) {
                    $result['error'] = 'Contraseñas diferentes';
                } elseif ($profesor->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Profesor creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el Profesor';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $profesor->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen profesores registrados';
                }
                break;
            case 'readOne':
                if (!$profesor->setId($_POST['idProfesor'])) {
                    $result['error'] = 'profesor incorrecto';
                } elseif ($result['dataset'] = $profesor->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Profesor inexistente';
                }
                break;
            
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$profesor->setId($_POST['idProfesor']) or
                    !$profesor->setNombre($_POST['nombreProfesor']) or
                    !$profesor->setApellido($_POST['apellidoProfesor']) or
                    !$profesor->setCorreo($_POST['correoProfesor'])
                ) {
                    $result['error'] = $profesor->getDataError();
                } elseif ($profesor->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'profesor modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el profesor';
                }
                break;
            case 'deleteRow':
                if ($_POST['idProfesor'] == $_SESSION['idProfesor']) {
                    $result['error'] = 'No se puede eliminar a sí mismo';
                } elseif (!$profesor->setId($_POST['idProfesor'])) {
                    $result['error'] = $profesor->getDataError();
                } elseif ($profesor->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Profesor eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el profesor';
                }
                break;
            case 'getUser':
                if (isset($_SESSION['aliasProfesor'])) {
                    $result['status'] = 1;
                    $result['username'] = $_SESSION['aliasProfesor'];
                } else {
                    $result['error'] = 'Alias de profesor indefinido';
                }
                break;
            case 'logOut':
                if (session_destroy()) {
                    $result['status'] = 1;
                    $result['message'] = 'Sesión eliminada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
            case 'readProfile':
                if ($result['dataset'] = $profesor->readProfile()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Ocurrió un problema al leer el perfil';
                }
                break;
            case 'editProfile':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$profesor->setNombre($_POST['nombreProfesor']) or
                    !$profesor->setApellido($_POST['apellidoProfesor']) or
                    !$profesor->setCorreo($_POST['correoProfesor']) or
                    !$profesor->setAlias($_POST['aliasProfesor'])
                ) {
                    $result['error'] = $profesor->getDataError();
                } elseif ($profesor->editProfile()) {
                    $result['status'] = 1;
                    $result['message'] = 'Perfil modificado correctamente';
                    $_SESSION['aliasProfesor'] = $_POST['aliasProfesor'];
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el perfil';
                }
                break;
            case 'changePassword':
                $_POST = Validator::validateForm($_POST);
                if (!$profesor->checkPassword($_POST['claveActual'])) {
                    $result['error'] = 'Contraseña actual incorrecta';
                } elseif ($_POST['claveNueva'] != $_POST['confirmarClave']) {
                    $result['profesor'] = 'Confirmación de contraseña diferente';
                } elseif (!$profesor->setClave($_POST['claveNueva'], $profesor->getAlias())) {
                    $result['error'] = $profesor->getDataError();
                } elseif ($profesor->changePassword()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al cambiar la contraseña';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el profesor no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readUsers':
                if ($profesor->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Debe autenticarse para ingresar';
                } else {
                    $result['error'] = 'Debe crear un profesor para comenzar';
                }
                break;
            case 'signUp':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$profesor->setNombre($_POST['nombreProfesor']) or
                    !$profesor->setApellido($_POST['apellidoProfesor']) or
                    !$profesor->setCorreo($_POST['correoProfesor']) or
                    !$profesor->setAlias($_POST['aliasProfesor']) or
                    !$profesor->setClave($_POST['claveProfesor'], $_POST['aliasProfesor'])
                ) {
                    $result['error'] = $profesor->getDataError();
                } elseif ($_POST['claveProfesor'] != $_POST['confirmarClave']) {
                    $result['error'] = 'Contraseñas diferentes';
                } elseif ($profesor->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'profesor registrado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al registrar el profesor';
                }
                break;
            case 'logIn':
                $_POST = Validator::validateForm($_POST);
                if ($profesor->checkUser($_POST['alias'], $_POST['clave'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';
                } else {
                    $result['error'] = 'Credenciales incorrectas';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible fuera de la sesión';
        }
    }
    // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
    $result['exception'] = Database::getException();
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('Content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
