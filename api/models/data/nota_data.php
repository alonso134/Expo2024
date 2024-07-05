<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/nota_handler.php');
/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla CATEGORIA.
 */
class NotasData extends NotasHandler
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
            $this->data_error = 'El identificador de la nota es incorrecto';
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

    public function setNota($value)
    {
        if (Validator::validateMoney($value)) {
            $this->notas = $value;
            return true;
        } else {
            $this->data_error = 'la nota debe ser un valor numérico';
            return false;
        }
    }

    public function setTrimestre($value, $min = 2, $max = 250)
    {
        if (!Validator::validateString($value)) {
            $this->data_error = 'el trimestre contiene caracteres prohibidos';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->trimestre = $value;
            return true;
        } else {
            $this->data_error = 'el nombre del trimestre debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    
    public function setFecha($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha = $value;
            return true;
        } else {
            $this->data_error = 'La fecha es incorrecta';
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
