<?php
use Phpml\Regression\LeastSquares;
require('C:/xampp/htdocs/Expo2024/vendor/autoload.php');
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
        $params = array($this->estudiante, $this->materias, $this->notas, $this->trimestre, $this->fecha, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM notas
                WHERE id_nota = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function notasPorMateria()
    {
        $sql = 'SELECT id_nota, nombre_estudiante, nombre, nota, trimestre, fecha_calificacion
                FROM notas
                INNER JOIN estudiantes USING(id_estudiante)
                INNER JOIN materias USING(id_materia)
                WHERE id_materia = ?
                ORDER BY nombre_estudiante';
        $params = array($this->materias);
        return Database::getRows($sql, $params);
    }

    public function notasPorEstudiante()
    {
        $sql = 'SELECT CONCAT(nombre_estudiante, " ", apellido_estudiante) AS estudiante, 
        nombre AS materia, AVG(nota) AS promedio_nota
        FROM notas
        INNER JOIN estudiantes USING(id_estudiante)
        INNER JOIN materias USING(id_materia)
        WHERE id_estudiante = ? 
        AND fecha_calificacion >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
        GROUP BY estudiante, materia
        ORDER BY estudiante, materia;
        ';
        $params = array($this->estudiante);
        return Database::getRows($sql, $params);
    }

    // Función para predecir las notas de la siguiente semana
    public function predictNotasPromedioPorEstudianteSiguienteSemana()
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

        // Consulta para obtener el promedio de las notas del estudiante por materia en los últimos 3 meses
        $sql = 'SELECT CONCAT(nombre_estudiante, " ", apellido_estudiante) AS estudiante, 
               nombre AS materia, 
               AVG(nota) AS promedio_nota,
               fecha_calificacion
        FROM notas
        INNER JOIN estudiantes USING(id_estudiante)
        INNER JOIN materias USING(id_materia)
        WHERE id_estudiante = ? 
        AND fecha_calificacion >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
        GROUP BY estudiante, materia
        ORDER BY estudiante, materia, fecha_calificacion ASC;';

        $params = array($this->estudiante);
        $rows = Database::getRows($sql, $params);
        // Verifica si no retorna datos nulos
        if (empty($rows)) {
            return [];
        }

        // Extraer fechas y promedios para la regresión
        $dates = [];
        $averages = [];

        foreach ($rows as $row) {
            $date = new DateTime($row['fecha_calificacion']);
            $timestamp = $date->getTimestamp();
            $dates[] = $timestamp;
            $averages[] = $row['promedio_nota'];
        }

        $predictions = [];
        // Calcular la regresión para predecir el promedio en los próximos días de la semana siguiente
        for ($i = 1; $i <= 7; $i++) {
            $X = array_slice($dates, 0, count($dates));
            $y = array_slice($averages, 0, count($averages));

            // Datos insuficientes, se necesita mas de 1
            if (count($X) <= 1 || count(array_unique($X)) == 1) {
                throw new Exception("Datos insuficientes o colineales para la regresión.");
            }

            // Crear el modelo de regresión lineal
            $regression = new LeastSquares();
            $regression->train(array_map(function ($timestamp) {
                return [$timestamp];
            }, $X), $y);

            // Predecir el promedio para el próximo día
            $timestamp = end($dates) + $i * 24 * 60 * 60;
            $predictedAverage = $regression->predict([$timestamp]);

            // Asegurarse de que la nota no supere 10 ni sea menor a 0
            $predictedAverage = max(0, min($predictedAverage, 10));

            // Convertir timestamp a fecha en español
            $dateTime = new DateTime();
            $dateTime->setTimestamp($timestamp);
            $day = $dateTime->format('d');
            $month = (int) $dateTime->format('m');
            $year = $dateTime->format('Y');
            $monthName = $monthNames[$month];
            // Fecha
            $date = "$day de $monthName de $year";
            // Predicción
            $predictions[] = [
                'fecha' => $date,
                'promedio' => $predictedAverage
            ];
        }
        
        return $predictions;
    }

}
