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

    public function readEstudianteGrado()
    {
        $sql = 'SELECT id_estudiante, nombre_estudiante, apellido_estudiante, correo_estudiante, fecha_de_nacimiento
                FROM estudiantes
                INNER JOIN grados USING(id_grado)
                WHERE id_grado = ?
                ORDER BY nombre_estudiante';
        $params = array($this->grado);
        return Database::getRows($sql, $params);
    }


 
}
