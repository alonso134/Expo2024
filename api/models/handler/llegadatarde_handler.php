<?php
use Phpml\Regression\LeastSquares;
require('C:/xampp/htdocs/Expo2024/vendor/autoload.php');
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
        $sql = 'SELECT id_llegada, CONCAT(nombre_estudiante , " " , apellido_estudiante) AS estudiante, 
                nombre, fecha, hora, nombre_profesor
                FROM llegadas_tarde
                INNER JOIN estudiantes USING(id_estudiante)
                INNER JOIN materias USING(id_materia)
                INNER JOIN profesores
                WHERE id_estudiante = ?
                ORDER BY nombre';
        $params = array($this->estudiante);
        return Database::getRows($sql, $params);
    }

    public function llegadasTardePorEstudianteGrafica()
    {
        $sql = 'SELECT COUNT(id_llegada) AS id, CONCAT(nombre_estudiante , " " , apellido_estudiante) AS estudiante, fecha
                FROM llegadas_tarde
                INNER JOIN estudiantes USING(id_estudiante)
                INNER JOIN materias USING(id_materia)
                INNER JOIN profesores
                WHERE id_estudiante = ? AND fecha >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
                GROUP BY estudiante, fecha
                ORDER BY fecha, estudiante';
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

    public function predictLlegadasTardeSiguienteMes()
    {
        // Configurar la localización en español
        setlocale(LC_TIME, 'es_ES.UTF-8');
    
        // Mapa de traducción de meses
        $monthNames = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre'
        ];
    
        // Consulta para obtener el número de llegadas tarde del estudiante en los últimos 3 meses
        $sql = 'SELECT COUNT(id_llegada) AS llegadas_tarde, 
                       fecha
                FROM llegadas_tarde
                INNER JOIN estudiantes USING(id_estudiante)
                WHERE id_estudiante = ? 
                AND fecha >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
                GROUP BY fecha
                ORDER BY fecha ASC;';
        
        $params = array($this->estudiante);
        $rows = Database::getRows($sql, $params);
    
        if (empty($rows)) {
            return [];
        }
    
        // Extraer fechas y conteo de llegadas tarde para la regresión
        $dates = [];
        $llegadasTarde = [];
    
        foreach ($rows as $row) {
            $date = new DateTime($row['fecha']);
            $timestamp = $date->getTimestamp();
            $dates[] = $timestamp;
            $llegadasTarde[] = $row['llegadas_tarde'];
        }
    
        $predictions = [];
        // Calcular la regresión para predecir las llegadas tarde en la proxima semana
        for ($i = 1; $i <= 7; $i++) {
            $X = array_slice($dates, 0, count($dates));
            $y = array_slice($llegadasTarde, 0, count($llegadasTarde));
    
            if (count($X) <= 1 || count(array_unique($X)) == 1) {
                throw new Exception("Datos insuficientes o colineales para la regresión.");
            }
    
            // Crear el modelo de regresión lineal
            $regression = new LeastSquares();
            $regression->train(array_map(function ($timestamp) {
                return [$timestamp];
            }, $X), $y);
    
            // Predecir el número de llegadas tarde para el próximo día
            $timestamp = end($dates) + $i * 24 * 60 * 60;
            $predictedLlegadasTarde = $regression->predict([$timestamp]);
    
            // Asegurarse de que el número de llegadas tarde no sea menor a 0
            $predictedLlegadasTarde = max(0, min(round($predictedLlegadasTarde), 6) );
    
            // Convertir timestamp a fecha en español
            $dateTime = new DateTime();
            $dateTime->setTimestamp($timestamp);
            $day = $dateTime->format('d');
            $month = (int) $dateTime->format('m');
            $year = $dateTime->format('Y');
            $monthName = $monthNames[$month];
    
            $date = "$day de $monthName de $year";
    
            $predictions[] = [
                'fecha' => $date,
                'llegadas_tarde' => $predictedLlegadasTarde
            ];
        }
    
        return $predictions;
    }
    
}
