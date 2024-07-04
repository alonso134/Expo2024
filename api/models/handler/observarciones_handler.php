<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
*/
class ObservacionHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $nombre = null;
    protected $profe = null;
    protected $observacion = null;
    protected $fecha = null;
   

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_observacion, nombre_estudiante, nombre_profesor, observacion, fecha
                FROM observaciones
                INNER JOIN estudiantes USING(id_estudiante)
                WHERE nombre_estudiante LIKE ? OR apellido_estudiante LIKE ?
                ORDER BY nombre_estudiante';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO observaciones(observacion, fecha, id_estudiante, id_profesor)
                VALUES(?, ?, ?, ?)';
        $params = array($this->observacion,  $this->fecha , $this->nombre, $this->profe);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_observacion, id_estudiante, id_profesor, observacion, fecha
                FROM observaciones
                INNER JOIN estudiantes USING(id_estudiante)
                ORDER BY nombre_estudiante';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_observacion,  id_estudiante, id_profesor, observacion, fecha
                FROM observaciones
                WHERE id_observacion = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE observaciones
                SET id_estudiante = ?, id_profesor = ?, observacion = ?, fecha = ?
                WHERE id_observacion = ?';
        $params = array($this->nombre, $this->profe, $this->observacion, $this->fecha, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM observaciones
                WHERE id_observacion = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function readEstudianteObservacion()
    {
        $sql = 'SELECT id_observacion,  id_estudiante, id_profesor, observacion, fecha
                FROM observaciones
                INNER JOIN estudiantes USING(id_estudiante)
                WHERE is_estudiante = ?
                ORDER BY nombre_estudainte';
        $params = array($this->nombre);
        return Database::getRows($sql, $params);
    }


 
}
