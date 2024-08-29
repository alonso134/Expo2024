<?php
use Phpml\Regression\LeastSquares;
require('C:/xampp/htdocs/Expo2024/vendor/autoload.php');
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

    public function predictCodigosEnTresMeses()
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
    
        // Consulta para obtener los códigos de comportamiento en el último año
        $sql = 'SELECT fecha, COUNT(*) AS cantidad
                FROM comportamiento_estudiante
                WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
                GROUP BY DATE_FORMAT(fecha, "%Y-%m")';
    
        $rows = Database::getRows($sql);
    
        if (empty($rows)) {
            return [];
        }
    
        // Agrupar los datos por mes
        $monthlyData = [];
        foreach ($rows as $row) {
            $date = new DateTime($row['fecha']);
            $month = (int) $date->format('m');
            $year = $date->format('Y');
            $monthName = $monthNames[$month];
            $key = "$monthName de $year";
    
            if (!isset($monthlyData[$key])) {
                $monthlyData[$key] = 0;
            }
            $monthlyData[$key] += $row['cantidad'];
        }
    
        // Predecir el número de códigos para los próximos 3 meses
        $predictions = [];
        $dates = array_keys($monthlyData);
        $values = array_values($monthlyData);
    
        // Si hay suficientes datos, hacer una predicción
        if (count($dates) > 1) {
            $regression = new LeastSquares();
            $regression->train(array_map(function ($i) {
                return [$i];
            }, range(1, count($values))), $values);
    
            $currentMonth = count($values) + 1;
    
            for ($i = 1; $i <= 3; $i++) {
                $predictedCount = $regression->predict([$currentMonth]);
                $currentMonth++;
    
                // Convertir a formato de mes en español
                $dateTime = new DateTime();
                $dateTime->setDate((int) date('Y'), (int) date('m') + $i, 1);
                $month = (int) $dateTime->format('m');
                $year = $dateTime->format('Y');
                $monthName = $monthNames[$month];
                $date = "$monthName de $year";
    
                $predictions[] = [
                    'fecha' => $date,
                    'cantidad_predicha' => round($predictedCount)
                ];
            }
        } else {
            throw new Exception("Datos insuficientes para la predicción.");
        }
    
        return $predictions;
    }
    
}
