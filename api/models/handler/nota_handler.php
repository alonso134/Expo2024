<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
*/
class NotasHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $estudiante = null;
    protected $materias = null;
    protected $notas = null;
    protected $trimestre = null;
    protected $fecha = null;
    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_nota, nombre_estudiante, nombre, nota, trimestre, fecha_calificacion
                FROM `notas` 
                IINNER JOIN estudiantes USING(id_estudiante)
                IINNER JOIN materias USING(id_materia)
                WHERE nombre_estudiante LIKE ? OR nombre LIKE ?
                ORDER BY nombre_estudiante';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO notas(id_estudiante, id_materia, nota, trimestre, fecha_calificacion)
                VALUES(?, ?, ?, ?, ?)';
        $params = array($this->estudiante, $this->materias, $this->notas, $this->trimestre, $this->fecha);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_nota, nombre_estudiante, nombre, nota, trimestre, fecha_calificacion
                FROM notas
                INNER JOIN estudiantes USING(id_estudiante)
                INNER JOIN materias USING(id_materia)
                ORDER BY nombre_estudiante';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_nota, id_estudiante, id_materia, nota, trimestre, fecha_calificacion
                FROM notas
                WHERE id_nota = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE notas
                SET id_estudiante = ?, id_materia = ?,  nota = ?,  trimestre = ?,  fecha_calificacion = ?
                WHERE id_nota = ?';
        $params = array($this->estudiante, $this->materias, $this->notas, $this->trimestre, $this->fecha,  $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM notas
                WHERE id_nota = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

 
}
