<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/inscripcion_handler.php');
/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla CATEGORIA.
 */
class InscripcionData extends InscripcionHandler
{
  
    private $data_error = null;
    /*
     *  Métodos para validar y establecer los datos.
     */
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            $this->data_error = 'El identificador de la inscripcion es incorrecto';
            return false;
        }
    }

    public function setEstudiante($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->estudiante = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del estudiante es incorrecto';
            return false;
        }
    }


    public function setMaterias($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->materias = $value;
            return true;
        } else {
            $this->data_error = 'El identificador de la materia es incorrecto';
            return false;
        }
    }

    /*
     *  Métodos para obtener los atributos adicionales.
     */
    public function getDataError()
    {
        return $this->data_error;
    }

}
