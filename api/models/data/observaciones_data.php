<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/observaciones_handler.php');
/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla ESTUDIANTES.
 */
class ObservacionData extends ObservacionHandler
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
            $this->data_error = 'El identificador de la categoría es incorrecto';
            return false;
        }
    }

    public function setEstudiante($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->nombre = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del estudiante es incorrecto';
            return false;
        }
    }

    public function setprofesor($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->profe = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del profesor es incorrecto';
            return false;
        }
    }

    public function setObservacion($value, $min = 2, $max = 50)
    {
        if (!Validator::validateAlphabetic($value)) {
            $this->data_error = 'la observacion debe ser un valor alfabético';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->observacion = $value;
            return true;
        } else {
            $this->data_error = 'la observacion debe tener una longitud entre ' . $min . ' y ' . $max;
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



       /*
     *  Métodos para obtener los atributos adicionales.
     */
    public function getDataError()
    {
        return $this->data_error;
    }

}
