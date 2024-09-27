<?php
// Se incluye la clase del modelo.
require_once('../../models/data/profesores_data.php');
require_once('../../helpers/email.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $profesor = new ProfesorData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como profesor, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idProfesor']) and Validator::validateSessionTime()) {
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
                    $result['error'] = 'Confirmación de contraseña diferente';
                } elseif (!$profesor->setClave($_POST['claveNueva'])) {
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
                    !$profesor->setClave($_POST['claveProfesor'])
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
                case 'sendVerificationCode':
                    $_POST = Validator::validateForm($_POST);
    
                    if (!$profesor->setCorreo($_POST['correo'])) {
                        $result['error'] = $profesor->getDataError();
                    } elseif ($profesor->verifyExistingEmail()) {
                        $loginResult = $profesor->checkUser($_POST['correo'], $_POST['clave']);
    
                        if ($loginResult === 'expirada') {
                            $result['error'] = 'La contraseña ha expirado. Debe cambiarla.';
                        } elseif ($loginResult === 'bloqueado') {
                            $result['error'] = 'Demasiados intentos fallidos. Tu cuenta ha sido bloqueada. Vuelve a intentarlo en 24 horas.';
                        } elseif ($loginResult) {
                            $secret_verification_code = mt_rand(100000, 999999);
                            $token = Validator::generateRandomString(64);
    
                            $_SESSION['verification_code'] = [
                                'code' => $secret_verification_code,
                                'token' => $token,
                                'expiration_time' => time() + (60 * 10)
                            ];
                            sendVerificationEmail($_POST['correo'], $secret_verification_code, 'login_verification');
                            //  sendVerificationEmail($_POST['correo'], $secret_verification_code);
                            $result['status'] = 1;
                            $result['message'] = 'Código de verificación enviado al correo';
                            $result['dataset'] = $token;
                        } else {
                            $result['error'] = 'Credenciales incorrectas';
                        }
                    } else {
                        $result['error'] = 'El correo indicado no existe';
                    }
                    break;
            case 'logIn':
                $_POST = Validator::validateForm($_POST);
                $loginResult = $profesor->checkUser($_POST['correo'], $_POST['clave']);

                if ($loginResult === 'expirada') {
                    $result['error'] = 'La contraseña ha expirado. Debe cambiarla.';
                } elseif ($loginResult === 'bloqueado') {
                    $result['error'] = 'Demasiados intentos fallidos. Tu cuenta ha sido bloqueada. Vuelve a intentarlo en 24 horas.';
                } elseif ($loginResult) {
                    if (isset($_POST['verificacion'])) {
                        $verificationResult = $profesor->verifyCode($_POST['verificacion']);

                        if ($verificationResult === 'expired') {
                            $result['error'] = 'El código ha expirado. Solicita uno nuevo.';
                        } elseif ($verificationResult === true) {
                            // Autenticación correcta, asignar sesión
                            $_SESSION['idProfesor'] = $loginResult['id_usuario'];
                            $_SESSION['aliasProfesor'] = $loginResult['alias'];
                            $result['status'] = 1;
                            $result['message'] = 'Autenticación correcta';
                            $_SESSION['tiempo'] = time();
                            // Redirigir al dashboard o preparar la sesión
                        } else {
                            $result['error'] = 'Código de verificación incorrecto. No se pudo autenticar.';
                        }
                    } else {
                        $result['error'] = 'No se proporcionó un código de verificación.';
                    }
                } else {
                    $result['error'] = 'Credenciales incorrectas';
                }
                break;

                

                /* Cambio de contraseña */
                case 'emailPasswordSender':
                    $_POST = Validator::validateForm($_POST);
    
                    if (!$profesor->setCorreo($_POST['correo'])) {
                        $result['error'] = $profesor->getDataError();
                    } elseif ($profesor->verifyExistingEmail()) {
                        $correoUsuario = $profesor->readEmailByAlias($_POST['correo']);
                        $secret_change_password_code = mt_rand(10000000, 99999999);
                        $token = Validator::generateRandomString(64);
    
                        $_SESSION['secret_change_password_code'] = [
                            'code' => $secret_change_password_code,
                            'token' => $token,
                            'expiration_time' => time() + (60 * 15) # (x*y) y=minutos de vida 
                        ];
    
                        $_SESSION['usuario_correo_vcc'] = [
                            'correo' => $correoUsuario,
                            'expiration_time' => time() + (60 * 25) # (x*y) y=minutos de vida 
                        ];
                        sendVerificationEmail($_POST['correo'], $secret_change_password_code, 'password_reset');
                        //sendVerificationEmail($_POST['correo'], $secret_change_password_code);
                        $result['status'] = 1;
                        $result['message'] = 'Correo enviado';
                        $result['dataset'] = $token;
                    } else {
                        $result['error'] = 'El correo indicado no existe';
                    }
                    break;

                case 'emailPasswordValidator':
                    $_POST = Validator::validateForm($_POST);
    
                    if (!isset($_POST['codigo_secret'])) {
                        $result['error'] = "El código no fue proporcionado";
                    } elseif (!isset($_POST["token"])) {
                        $result['error'] = 'El token no fue proporcionado';
                    } elseif (!(ctype_digit($_POST['codigo_secret']) && strlen($_POST['codigo_secret']) === 8)) {
                        $result['error'] = "El código es inválido";
                    } elseif (!isset($_SESSION['secret_change_password_code'])) {
                        $result['error'] = "El código ha expirado";
                    } elseif (!isset($_SESSION['secret_change_password_code']['token'])) {
                        $result['error'] = "El token ha expirado o no fue proporcionado";
                    } elseif ($_SESSION['secret_change_password_code']['token'] != $_POST["token"]) {
                        $result['error'] = 'El token es inválido';
                    } elseif (!isset($_SESSION['secret_change_password_code']['expiration_time']) || $_SESSION['secret_change_password_code']['expiration_time'] <= time()) {
                        $result['message'] = "El código ha expirado.";
                        unset($_SESSION['secret_change_password_code']);
                    } elseif (!isset($_SESSION['secret_change_password_code']['code']) || $_SESSION['secret_change_password_code']['code'] != $_POST['codigo_secret']) {
                        $result['error'] = "El código es incorrecto";
                    } else {
                        // Código correcto, generar el token
                        $token = Validator::generateRandomString(64);
                        $_SESSION['secret_change_password_code_validated'] = [
                            'token' => $token,
                            'expiration_time' => time() + (100 * 60) // Expiración de 10 minutos 
                        ];
                        $result['status'] = 1;
                        $result['message'] = "Verificación Correcta";
                        $result['dataset'] = $token;
                        unset($_SESSION['secret_change_password_code']);
                    }
                    break;
    
                    case 'changePasswordByEmail':
                        $_POST = Validator::validateForm($_POST);    
                        $correoUsuario = $_SESSION['usuario_correo_vcc']['correo'];
                        $nombreUsuario = $profesor->readUserByEmail($_SESSION['usuario_correo_vcc']['correo']);
        
                        // Verificar si el token fue proporcionado.
                        if (!isset($_POST["token"])) {
                            $result['error'] = 'El token no fue proporcionado';
        
                            // Verificar si la sesión para el cambio de contraseña ha expirado.
                        } elseif (!isset($_SESSION['secret_change_password_code_validated'])) {
                            $result['error'] = 'El tiempo para cambiar su contraseña ha expirado';
                        } elseif ($_SESSION['secret_change_password_code_validated']['expiration_time'] <= time()) {
                            $result['error'] = 'El tiempo para cambiar su contraseña ha expirado';
                            unset($_SESSION['secret_change_password_code_validated']);
        
                            // Verificar si el token proporcionado es válido.
                        } elseif ($_SESSION['secret_change_password_code_validated']['token'] != $_POST["token"]) {
                            $result['error'] = 'El token es inválido';
        
                            // Verificar si las contraseñas nuevas coinciden.
                        } elseif ($_POST['nuevaClave'] != $_POST['confirmarClave']) {
                            $result['error'] = 'Confirmación de contraseña diferente';
        
                            // Verificar si la nueva contraseña no es igual a la actual.
                        } elseif (!Validator::validatePassword($_POST['nuevaClave'], $nombreUsuario)) {
                            $result['error'] = Validator::getPasswordError();
        //'Indu12345!
                            // Verificar si hubo un error al establecer la nueva contraseña.
                        } elseif (!$profesor->setClave($_POST['nuevaClave']) ) {
                            //  (!Validator::validatePassword($_POST['nuevaClave'], [$nombreUsuario, $_SESSION['usuario_correo_vcc']['correo']], $correoUsuario)) {
                            $result['error'] = $profesor->getDataError();
        
                            // Verificar si la sesión del usuario ha expirado.
                        } elseif (!isset($_SESSION['usuario_correo_vcc']) || $_SESSION['usuario_correo_vcc']['expiration_time'] <= time()) {
                            $result['error'] = 'El tiempo para cambiar su contraseña ha expirado';
                            unset($_SESSION['usuario_correo_vcc']);
        
                            // Si todo es correcto, cambiar la contraseña en la base de datos.
                        } elseif ($profesor->changePasswordFromEmail()) {
                            $result['status'] = 1;
                            $result['message'] = 'Contraseña cambiada correctamente';
                            unset($_SESSION['secret_change_password_code_validated']);
                            unset($_SESSION['usuario_correo_vcc']);
                        } else {
                            $result['error'] = 'Ocurrió un problema al cambiar la contraseña';
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
    print (json_encode($result));
} else {
    print (json_encode('Recurso no disponible'));
}
