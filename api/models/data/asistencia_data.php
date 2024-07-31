<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/asistencia_handler.php');
/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla ESTUDIANTES.
 */
class AsistenciaData extends AsistenciaHandler
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
            $this->data_error = 'El identificador de la asistencias es incorrecto';
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

    public function setprofesor($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->profesor = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del profesor es incorrecto';
            return false;
        }
    }


    public function setfecha($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha = $value;
            return true;
        } else {
            $this->data_error = 'La fecha de registro es incorrecta';
            return false;
        }
    }

    public function setEstado($value)
    {
        if (Validator::validateBoolean($value)) {
            $this->estado = $value;
            return true;
        } else {
            $this->data_error = 'Estado incorrecto';
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
