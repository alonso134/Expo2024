<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
*/
class LlegadaHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $estudiante = null;
    protected $materia = null;
    protected $hora = null;
    protected $fecha = null;
    protected $profesor = null;
   


    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_llegada, nombre_estudiante, nombre, fecha, hora, nombre_profesor
                FROM llegadas_tarde
                INNER JOIN estudiantes USING(id_estudiante)
                INNER JOIN materias USING(id_materia)
                INNER JOIN profesores USING(id_profesor)
                WHERE nombre_estudiante LIKE ? OR nombre_profesor LIKE ? 
                ORDER BY nombre_estudiante';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO llegadas_tarde(id_estudiante, id_materia , fecha, hora, id_profesor)
                VALUES(?, ?, ?, ?, ?)';
        $params = array($this->estudiante, $this->materia, $this->fecha, $this->hora, $this->profesor);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_llegada, nombre_estudiante, nombre, fecha, hora, nombre_profesor
                FROM llegadas_tarde
                INNER JOIN estudiantes USING(id_estudiante)
                INNER JOIN materias USING(id_materia)
                INNER JOIN profesores
                ORDER BY nombre';
        return Database::getRows($sql);
    }
    
    public function llegadasTardePorEstudiante()
    {
        $sql = 'SELECT id_llegada, CONCAT(nombre_estudiante , " " , apellido_estudiante) AS estudiante, nombre, fecha, hora, nombre_profesor
                FROM llegadas_tarde
                INNER JOIN estudiantes USING(id_estudiante)
                INNER JOIN materias USING(id_materia)
                INNER JOIN profesores
                WHERE id_estudiante = ?
                ORDER BY nombre';
        $params = array($this->estudiante);
        return Database::getRows($sql, $params);
    }

    public function readOne()
    {
        $sql = 'SELECT id_llegada, id_estudiante, id_materia, fecha, hora, id_profesor
                FROM estudiantes
                WHERE id_llegada = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE llegadas_tarde
                SET id_estudiante = ?, id_materia = ?, fecha = ?, hora = ?, id_profesor = ?
                WHERE id_llegada = ?';
        $params = array($this->estudiante, $this->materia, $this->fecha, $this->hora, $this->profesor, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM llegadas_tarde
                WHERE id_llegada = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }


}
