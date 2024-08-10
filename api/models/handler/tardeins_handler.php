<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
*/
class TardeinsHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $fecha = null;
    protected $hora = null;
    protected $profesor = null;
    protected $estado = null;



    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_llegada_tarde_institucion, fecha, hora, nombre_profesor, estado
                FROM llegadas_tarde_institucion
                INNER JOIN profesores USING(id_profesor)
                WHERE nombre_profesor LIKE ? OR fecha LIKE ? 
                ORDER BY nombre_profesor';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO llegadas_tarde_institucion(fecha, hora, id_profesor, estado)
                VALUES(?, ?, ?, ?)';
        $params = array($this->fecha,$this->hora,$this->profesor, $this->estado);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_llegada_tarde_institucion, fecha, hora, nombre_profesor, estado
                FROM llegadas_tarde_institucion
                INNER JOIN profesores USING(id_profesor)
                ORDER BY nombre_profesor';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_llegada_tarde_institucion, fecha, hora, id_profesor, estado
                FROM llegadas_tarde_institucion
                WHERE id_llegada_tarde_institucion = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE llegadas_tarde_institucion
                SET  id_llegada_tarde_institucion = ?, fecha = ?, hora = ?, id_profesor = ?, estado = ?
                WHERE id_llegada_tarde_institucion = ?';
        $params = array( $this->fecha, $this->hora, $this->profesor, $this->estado);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM llegadas_tarde_institucion
                WHERE id_llegada_tarde_institucion = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }


}
