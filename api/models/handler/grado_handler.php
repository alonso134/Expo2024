<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla CATEGORIA.
 */
class GradoHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $nombre_seccion = null;


    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_grado, nombre, nombre_seccion
                FROM grados
                WHERE nombre LIKE ? OR nombre_seccion LIKE ?
                ORDER BY nombre';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }
    
    public function createRow()
    {
        $sql = 'INSERT INTO grados(nombre, nombre_seccion)
                VALUES(?, ?)';
        $params = array($this->nombre, $this->nombre_seccion);
        return Database::executeRow($sql, $params);
    }
    
    public function readAll()
    {
        $sql = 'SELECT id_grado, nombre, nombre_seccion
                FROM grados
                ORDER BY nombre';
        return Database::getRows($sql);
    }
    
    public function readOne()
    {
        $sql = 'SELECT id_grado, nombre, nombre_seccion
                FROM grados
                WHERE id_grado = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
    
    public function updateRow()
    {
        $sql = 'UPDATE grados
                SET nombre = ?, nombre_seccion = ?
                WHERE id_grado = ?';
        $params = array($this->nombre, $this->nombre_seccion, $this->id);
        return Database::executeRow($sql, $params);
    }
    
    public function deleteRow()
    {
        $sql = 'DELETE FROM grados
                WHERE id_grado = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
