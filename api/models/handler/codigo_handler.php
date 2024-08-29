<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
*/
class CodigoHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $estudiante = null;
    protected $codigo = null;
    protected $profesor = null;
    protected $fecha = null;
    protected $descripcion = null;
   


    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_comportamiento_estudiante, nombre_estudiante, codigo, nombre_profesor, fecha, descripcion_adicional
                FROM comportamiento_estudiante
                INNER JOIN estudiantes USING(id_estudiante)
                INNER JOIN comportamiento USING(id_comportamiento )
                INNER JOIN profesores USING(id_profesor)
                WHERE nombre_estudiante LIKE ? OR nombre_profesor LIKE ? OR codigo LIKE ?
                ORDER BY nombre_estudiante';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO comportamiento_estudiante(id_estudiante, id_comportamiento , id_profesor , fecha, descripcion_adicional)
                VALUES(?, ?, ?, ?, ?)';
        $params = array($this->estudiante, $this->codigo, $this->profesor, $this->fecha, $this->descripcion);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_comportamiento_estudiante, nombre_estudiante, codigo, nombre_profesor, fecha, descripcion_adicional
                FROM comportamiento_estudiante
                 INNER JOIN estudiantes USING(id_estudiante)
                INNER JOIN comportamiento USING(id_comportamiento )
                INNER JOIN profesores USING(id_profesor)
                ORDER BY nombre_estudiante';
        return Database::getRows($sql);
    }
    
    public function codigosPorEstudiantes()
    {
        $sql = 'SELECT id_comportamiento_estudiante, CONCAT(nombre_estudiante , " " , apellido_estudiante) AS estudiante, codigo, nombre_profesor, fecha, descripcion_adicional
                FROM comportamiento_estudiante
                 INNER JOIN estudiantes USING(id_estudiante)
                INNER JOIN comportamiento USING(id_comportamiento )
                INNER JOIN profesores USING(id_profesor)
                WHERE id_estudiante = ?
                ORDER BY nombre_estudiante';
        $params = array($this->estudiante);
        return Database::getRows($sql, $params);
    }
    
    public function codigosPorEstudiantesGrafica()
    {
        $sql = 'SELECT COUNT(id_comportamiento_estudiante) AS id, 
                CONCAT(nombre_estudiante , " " , apellido_estudiante) AS estudiante, fecha
                FROM comportamiento_estudiante
                INNER JOIN estudiantes USING(id_estudiante)
                INNER JOIN comportamiento USING(id_comportamiento )
                INNER JOIN profesores USING(id_profesor)
                WHERE id_estudiante = ? AND fecha >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
                GROUP BY estudiante, fecha
                ORDER BY fecha, estudiante;';
        $params = array($this->estudiante);
        return Database::getRows($sql, $params);
    }

    public function readOne()
    {
        $sql = 'SELECT id_comportamiento_estudiante, id_estudiante, id_comportamiento, id_profesor, fecha, descripcion_adicional
                FROM estudiantes
                WHERE id_comportamiento_estudiante = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE comportamiento_estudiante
                SET id_estudiante = ?, id_comportamiento = ?, id_profesor = ?, fecha = ?, descripcion_adicional = ?
                WHERE id_comportamiento_estudiante = ?';
        $params = array($this->estudiante, $this->codigo, $this->profesor, $this->fecha, $this->descripcion, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM comportamiento_estudiante
                WHERE id_comportamiento_estudiante = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }


}
