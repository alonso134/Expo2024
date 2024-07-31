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

    /*
     *  Métodos para gestionar la cuenta del Profesor.
     */
    public function checkUser($username, $password)
    {
        $sql = 'SELECT id_profesor, alias_profesor, clave_profesor
                FROM profesores
                WHERE  alias_profesor = ?';
        $params = array($username);
        if (!($data = Database::getRow($sql, $params))) {
            return false;
        } elseif (password_verify($password, $data['clave_profesor'])) {
            $_SESSION['idProfesor'] = $data['id_profesor'];
            $_SESSION['aliasProfesor'] = $data['alias_profesor'];
            return true;
        } else {
            return false;
        }
    }

    public function checkPassword($password)
    {
        $sql = 'SELECT clave_profesor
                FROM profesores
                WHERE id_profesor = ?';
        $params = array($_SESSION['idProfesor']);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['clave_profesor'])) {
            return true;
        } else {
            return false;
        }
    }

    public function changePassword()
    {
        $sql = 'UPDATE profesores
                SET clave_profesor = ?
                WHERE id_profesor = ?';
        $params = array($this->clave, $_SESSION['idProfesor']);
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
