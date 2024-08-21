<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
*/
class EstudianteHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $correo = null;
    protected $clave = null;
    protected $nacimiento = null;
    protected $grado = null;


    public function checkUser($correo, $password)
    {
        $sql = 'SELECT id_estudiante, correo_estudiante, clave_estudiante
                FROM estudiantes
                WHERE  correo_estudiante = ?';
        $params = array($correo);
        if (!($data = Database::getRow($sql, $params))) {
            return false;
        } elseif (password_verify($password, $data['clave_estudiante'])) {
            $_SESSION['idEstudiante'] = $data['id_estudiante'];
            $_SESSION['correoEstudiante'] = $data['correo_estudiante'];
            return true;
        } else {
            return false;
        }
    }

    public function checkPassword($password)
    {
        $sql = 'SELECT clave_estudiante
                FROM estudiantes
                WHERE id_estudiante = ?';
        $params = array($_SESSION['idEstudiante']);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['clave_estudiante'])) {
            return true;
        } else {
            return false;
        }
    }

    public function changePassword()
    {
        $sql = 'UPDATE estudiantes
                SET clave_estudiante = ?
                WHERE id_estudiante = ?';
        $params = array($this->clave, $_SESSION['idEstudiante']);
        return Database::executeRow($sql, $params);
    }

    public function readProfile()
    {
        $sql = 'SELECT id_estudiante, nombre_estudiante, apellido_estudiante, correo_estudiante
                FROM estudiantes
                WHERE id_estudiante = ?';
        $params = array($_SESSION['idEstudiante']);
        return Database::getRow($sql, $params);
    }

    public function editProfile()
    {
        $sql = 'UPDATE estudiantes
                SET nombre_estudiante = ?, apellido_estudiante = ?, correo_estudiante = ?
                WHERE id_estudiante = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $_SESSION['idEstudiante']);
        return Database::executeRow($sql, $params);
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_estudiante, nombre_estudiante, apellido_estudiante, correo_estudiante, fecha_de_nacimiento, nombre
                FROM estudiantes
                INNER JOIN grados USING(id_grado)
                WHERE nombre_estudiante LIKE ? OR apellido_estudiante LIKE ?
                ORDER BY nombre_estudiante';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO estudiantes(nombre_estudiante, apellido_estudiante, correo_estudiante, clave_estudiante, fecha_de_nacimiento, id_grado)
                VALUES(?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->clave, $this->nacimiento, $this->grado);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_estudiante, nombre_estudiante, apellido_estudiante, correo_estudiante, clave_estudiante, fecha_de_nacimiento, nombre
                FROM estudiantes
                INNER JOIN grados USING(id_grado)
                ORDER BY nombre_estudiante';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_estudiante, nombre_estudiante, apellido_estudiante, correo_estudiante, fecha_de_nacimiento, id_grado
                FROM estudiantes
                WHERE id_estudiante = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE estudiantes
                SET nombre_estudiante = ?, apellido_estudiante = ?,  fecha_de_nacimiento = ?, id_grado = ?
                WHERE id_estudiante = ?';
        $params = array($this->nombre, $this->apellido, $this->nacimiento, $this->grado, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM estudiantes
                WHERE id_estudiante = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function readEstudainteCodigos()
    {
        $sql = 'SELECT nombre_estudiante, COUNT(id_estudiante) total
                FROM observaciones
                INNER JOIN estudiantes USING(id_estudiante)
                GROUP BY nombre_estudiante ORDER BY total DESC LIMIT 5';
            return Database::getRows($sql);
    }

    public function readEstudainteNota()
    {
        $sql = 'SELECT nombre_estudiante,nota, COUNT(id_estudiante) total
                FROM notas
                INNER JOIN estudiantes USING(id_estudiante)
                GROUP BY nombre_estudiante ORDER BY total DESC LIMIT 5';
            return Database::getRows($sql);
    }

    public function readEstudainteAusencia()
    {
        $sql = 'SELECT nombre_estudiante, COUNT(id_estudiante) total
                FROM ausencias
                INNER JOIN estudiantes USING(id_estudiante)
                GROUP BY nombre_estudiante ORDER BY total DESC LIMIT 7';
            return Database::getRows($sql);
    }

    public function readEstudainteLlegadas()
    {
        $sql = 'SELECT nombre_estudiante,COUNT(id_estudiante) total
                FROM llegadas_tarde
                INNER JOIN estudiantes USING(id_estudiante)
                GROUP BY nombre_estudiante ORDER BY total DESC LIMIT 20';
            return Database::getRows($sql);
    }
    
 
}
