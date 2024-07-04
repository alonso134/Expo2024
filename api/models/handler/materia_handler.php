<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
*/
class MateriaHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $nombre = null;
    protected $descripcion = null;
    protected $profesor = null;

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_materia, nombre, descripcion, nombre_profesor
                FROM `materias` 
                IINNER JOIN profesores USING(id_profesor)
                WHERE nombre LIKE ? OR descripcion LIKE ?
                ORDER BY nombre';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO materias(nombre, descripcion, id_profesor)
                VALUES(?, ?, ?)';
        $params = array($this->nombre, $this->descripcion, $this->profesor);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_materia, nombre, descripcion, nombre_profesor
                FROM materias
                INNER JOIN profesores USING(id_profesor)
                ORDER BY nombre';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_materia, nombre, descripcion, id_profesor
                FROM materias
                WHERE id_materia = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE materias
                SET nombre = ?, descripcion = ?,  id_profesor = ?
                WHERE id_materia = ?';
        $params = array($this->nombre, $this->descripcion, $this->profesor,  $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM materias
                WHERE id_materia = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

 
}
