<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
*/
class InscripcionHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $estudiantes  = null;
    protected $materias = null;

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_inscripcion, nombre_estudiante, nombre
                IINNER JOIN estudiantes USING(id_estudiante)
                IINNER JOIN materias USING(id_materia)
                WHERE nombre LIKE ? OR descripcion LIKE ?
                ORDER BY nombre';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO inscripcion(id_estudiante, id_materia)
                VALUES(?, ?)';
        $params = array($this->estudiantes, $this->materias);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_inscripcion, id_estudiante, id_materia
                FROM inscripcion
                INNER JOIN estudiantes USING(id_estudiante)
                INNER JOIN materias USING(id_materia)
                ORDER BY id_estudiante';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_inscripcion, id_estudiante, id_materia
                FROM inscripcion
                WHERE id_inscripcion = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE inscripcion
                SET id_estudiante = ?, id_materia = ?
                WHERE id_inscripcion = ?';
        $params = array($this->estudiantes, $this->materias, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM inscripcion
                WHERE id_inscripcion = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

 
}
