<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla CATEGORIA.
 */
class AsignarHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $codigo = null;
    protected $descripcion = null;


    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_comportamiento, codigo, descripcion
                FROM comportamiento
                WHERE codigo LIKE ? OR descripcion LIKE ?
                ORDER BY codigo';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }
    
    public function createRow()
    {
        $sql = 'INSERT INTO comportamiento(codigo, descripcion)
                VALUES(?, ?)';
        $params = array($this->codigo, $this->descripcion);
        return Database::executeRow($sql, $params);
    }
    
    public function readAll()
    {
        $sql = 'SELECT id_comportamiento, codigo, descripcion
                FROM comportamiento
                ORDER BY codigo';
        return Database::getRows($sql);
    }
    
    public function readOne()
    {
        $sql = 'SELECT id_comportamiento, codigo, descripcion
                FROM comportamiento
                WHERE id_comportamiento = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
    
    public function updateRow()
    {
        $sql = 'UPDATE comportamiento
                SET codigo = ?, descripcion = ?
                WHERE id_comportamiento = ?';
        $params = array($this->codigo, $this->descripcion, $this->id);
        return Database::executeRow($sql, $params);
    }
    
    public function deleteRow()
    {
        $sql = 'DELETE FROM comportamiento
                WHERE id_comportamiento = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
