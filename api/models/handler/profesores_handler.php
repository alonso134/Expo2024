<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla profesor.
 */
class ProfesorHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $correo = null;
    protected $alias = null;
    protected $clave = null;
    protected $condicion = null;

    /*
     *  Métodos para gestionar la cuenta del Profesor.
     */
    public function checkUser($username, $password)
    {
        $sql = 'SELECT id_profesor, alias_profesor, clave_profesor,
                correo_profesor, intentos_fallidos,fecha_cambio_clave, 
                bloqueado_hasta
                FROM profesores
                WHERE  correo_profesor = ?';
        $params = array($username);
        $data = Database::getRow($sql, $params);

        if (!$data) {
            return false;  
        }

        // Comprobar si la cuenta está bloqueada
        if ($data['bloqueado_hasta'] && strtotime($data['bloqueado_hasta']) > time()) {
            return 'bloqueado';  
        }

        // Verificar la contraseña
        if (password_verify($password, $data['clave_profesor'])) {
            // Restablecer intentos fallidos si el login es correcto
            $sql = 'UPDATE profesores SET intentos_fallidos = 0, bloqueado_hasta = NULL WHERE id_profesor = ?';
            $params = array($data['id_profesor']);
            Database::executeRow($sql, $params);

            // Comprobar si la contraseña ha expirado
            $fechaCambio = strtotime($data['fecha_cambio_clave']);
            $fechaLimite = $fechaCambio + (90 * 24 * 60 * 60); // 90 días en segundos

            if (time() > $fechaLimite) {
                return 'expirada';  
            }

            // Retornar información del usuario en vez de asignar sesión
            return [
                'id_usuario' => $data['id_profesor'],
                'correo_electronico' => $data['correo_profesor'],
                'alias' =>$data['alias_profesor']
            ];
        } else {
            // Incrementar intentos fallidos
            $intentos_fallidos = $data['intentos_fallidos'] + 1;
            $bloqueado_hasta = null;

            // Si alcanza el límite de 3 intentos fallidos, bloquear la cuenta
            if ($intentos_fallidos >= 4) {
                $bloqueado_hasta = date('Y-m-d H:i:s', strtotime('+24 hours'));  
            }

            // Actualizar intentos fallidos y posible bloqueo
            $sql = 'UPDATE profesores SET intentos_fallidos = ?, bloqueado_hasta = ? WHERE id_profesor = ?';
            $params = array($intentos_fallidos, $bloqueado_hasta, $data['id_profesor']);
            Database::executeRow($sql, $params);

            // Mostrar mensaje si la cuenta ha sido bloqueada
            if ($bloqueado_hasta) {
                return 'bloqueado';  
            }

            return false;  
        }
    }

    public function verifyExistingEmail()
    {
        $sql = 'SELECT COUNT(*) as count
                FROM profesores
                WHERE correo_profesor = ?';
        $params = array($this->correo);
        $result = Database::getRow($sql, $params);

        if ($result['count'] > 0) {
            return true; // Hay resultados
        } else {
            return false; // No hay resultados
        }
    }
    
    public function verifyCode($inputCode)
    {
        if (isset($_SESSION['verification_code'])) {
            $storedCode = $_SESSION['verification_code']['code'];
            $expirationTime = $_SESSION['verification_code']['expiration_time'];

            // Verificar si el código ha expirado
            if (time() > $expirationTime) {
                unset($_SESSION['verification_code']);
                return 'expired'; // El código ha expirado
            }

            // Verificar si el código proporcionado coincide con el almacenado
            if ($inputCode == $storedCode) {
                return true; // El código es correcto
            } else {
                return false; // El código es incorrecto
            }
        }

        return false; // No se encontró el código en la sesión
    }

    public function checkPassword($password)
    {
        $sql = 'SELECT clave_profesor, alias_profesor, correo_profesor
                FROM profesores
                WHERE id_profesor = ?';
        $params = array($_SESSION['idProfesor']);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['clave_profesor'])) {
            $this->alias = $data['correo_profesor'];
            return true;
        } else {
            return false;
        }
    }
    public function readUserByEmail($correo)
    {
        $sql = 'SELECT alias_profesor
        FROM profesores
        WHERE correo_profesor = ?';
        $params = array($correo);
        return Database::getRow($sql, $params);
    }
    public function readEmailByAlias($alias)
    {
        $sql = 'SELECT correo_profesor
        FROM profesores
        WHERE alias_profesor = ?';
        $params = array($alias);
        return Database::getRow($sql, $params);
    }
    public function changePassword()
    {
        $sql = 'UPDATE profesores
                SET clave_profesor = ?
                WHERE id_profesor = ?';
        $params = array($this->clave, $_SESSION['idProfesor']);
        return Database::executeRow($sql, $params);
    }

    public function getPasswordHash($correo)
    {
        $sql = 'SELECT clave_profesor FROM profesores WHERE correo_profesor = ?';
        $params = array($correo);
        $data = Database::getRow($sql, $params);
        print_r($correo);
        return $data['clave_profesor'];
    }

    
    public function changePasswordFromEmail()
    {
        $sql = 'UPDATE profesores SET clave_profesor = ? WHERE correo_profesor = ?';
        $params = array($this->clave, $_SESSION['usuario_correo_vcc']['correo']);
        return Database::executeRow($sql, $params);
    }

    public function readProfile()
    {
        $sql = 'SELECT id_profesor, nombre_profesor, apellido_profesor, correo_profesor, alias_profesor
                FROM profesores
                WHERE id_profesor = ?';
        $params = array($_SESSION['idProfesor']);
        return Database::getRow($sql, $params);
    }

    public function editProfile()
    {
        $sql = 'UPDATE profesores
                SET nombre_profesor = ?, apellido_profesor = ?, correo_profesor = ?, alias_profesor = ?
                WHERE id_profesor = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->alias, $_SESSION['idProfesor']);
        return Database::executeRow($sql, $params);
    }

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_profesor, nombre_profesor, apellido_profesor, correo_profesor, alias_profesor
                FROM profesores
                WHERE apellido_profesor LIKE ? OR nombre_profesor LIKE ?
                ORDER BY apellido_profesor';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO profesores(nombre_profesor, apellido_profesor, correo_profesor, alias_profesor, clave_profesor)
                VALUES(?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->alias, $this->clave);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_profesor, nombre_profesor, apellido_profesor, correo_profesor, alias_profesor
                FROM profesores
                ORDER BY apellido_profesor';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_profesor, nombre_profesor, apellido_profesor, correo_profesor, alias_profesor
                FROM profesores
                WHERE id_profesor = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE profesores
                SET nombre_profesor = ?, apellido_profesor = ?, correo_profesor = ?
                WHERE id_profesor = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM profesores
                WHERE id_profesor = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}