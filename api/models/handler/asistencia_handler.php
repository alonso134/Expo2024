<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
*/
class AsistenciaHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $estudiante = null;
    protected $fecha = null;
    protected $profesor = null;
    protected $estado = null;



    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_asistencia, nombre_estudiante, fecha, nombre_profesor, estado
                FROM asistencia
                INNER JOIN estudiantes USING(id_estudiante)
                INNER JOIN profesores USING(id_profesor)
                WHERE nombre_estudiante LIKE ? OR nombre_profesor LIKE ? OR fecha LIKE ?
                ORDER BY nombre_estudiante';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO asistencia(id_estudiante , fecha , id_profesor, estado)
                VALUES( ?, ?, ?, ?)';
        $params = array($this->estudiante, $this->fecha, $this->profesor, $this->estado,);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_asistencia, nombre_estudiante, fecha, nombre_profesor, estado
                FROM asistencia
                 INNER JOIN estudiantes USING(id_estudiante)
                INNER JOIN profesores USING(id_profesor)
                ORDER BY nombre_estudiante';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_asistencia, id_estudiante, fecha, id_profesor, estado
                FROM asistencia
                WHERE id_asistencia = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE asistencia
                SET  id_estudiante = ?, fecha = ?, id_profesor = ?, estado = ?
                WHERE id_asistencia = ?';
        $params = array($this->estudiante, $this->fecha, $this->profesor, $this->estado, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM asistencia
                WHERE id_asistencia = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }


}
