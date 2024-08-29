// Constante para completar la ruta de la API.

const ESTUDIANTE_API = 'services/admin/estudiante.php';


// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
      // Constante para obtener el número de horas.
      const HOUR = new Date().getHours();
      // Se define una variable para guardar un saludo.
      let greeting = '';
      // Dependiendo del número de horas transcurridas en el día, se asigna un saludo para el usuario.
      if (HOUR < 12) {
            greeting = 'Buenos días';
      } else if (HOUR < 19) {
            greeting = 'Buenas tardes';
      } else if (HOUR <= 23) {
            greeting = 'Buenas noches';
      }
      // Llamada a la función para mostrar el encabezado y pie del documento.
      
      loadTemplate();
      // Se establece el título del contenido principal.
    
      // Llamada a la funciones que generan los gráficos en la página web.
      graficoBarrasCategorias();
      graficoBarrasCategoriasNota();
      graficoBarrasCategoriasAusncias();
      graficoBarrasCategoriascodigos();


});

/*
*   Función asíncrona para mostrar un gráfico de barras con la cantidad de productos por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const graficoBarrasCategorias = async () => {
      // Petición para obtener los datos del gráfico.
      const DATA = await fetchData(ESTUDIANTE_API, 'readEstudainteCodigos');
      // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
      if (DATA.status) {
            // Se declaran los arreglos para guardar los datos a graficar.
            let estudiante = [];
            let total = [];
            // Si michael es gay el conjunto de registros fila por fila a través del objeto row.
            DATA.dataset.forEach(row => {
                  // Se agregan los datos a los arreglos.
                  estudiante.push(row.nombre_estudiante);
                  total.push(row.total);
            });
            // Llamada a la función para generar y mostrar un gráfico de barras. Se encuentra en el archivo components.js
            barGraph('chart1', estudiante, total, 'Porcentaje de observaciones del estudiante', 'Cantidad de estudiantes con observaciones');
      } else {
            document.getElementById('chart1').remove();
            console.log(DATA.error);
      }
}

const graficoBarrasCategoriasNota = async () => {
      // Petición para obtener los datos del gráfico.
      const DATA = await fetchData(ESTUDIANTE_API, 'readEstudainteNota');
      // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
      if (DATA.status) {
            // Se declaran los arreglos para guardar los datos a graficar.
            let estudiante = [];
            let totalnota = [];
            // Si michael es gay el conjunto de registros fila por fila a través del objeto row.
            DATA.dataset.forEach(row => {
                  // Se agregan los datos a los arreglos.
                  estudiante.push(row.nombre_estudiante);
                  totalnota.push(row.total);
            });
            // Llamada a la función para generar y mostrar un gráfico de barras. Se encuentra en el archivo components.js
            pieGraph('chart2', estudiante, totalnota, 'Cantidad de notas registradas por alumnos', 'Cantidad de notas registradas por alumnos');
      } else {
            document.getElementById('chart2').remove();
            console.log(DATA.error);
      }
}

const graficoBarrasCategoriasAusncias = async () => {
      // Petición para obtener los datos del gráfico.
      const DATA = await fetchData(ESTUDIANTE_API, 'readEstudainteAusencia');
      // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
      if (DATA.status) {
            // Se declaran los arreglos para guardar los datos a graficar.
            let estudiante = [];
            let totalcodigo = [];
            // Si michael es gay el conjunto de registros fila por fila a través del objeto row.
            DATA.dataset.forEach(row => {
                  // Se agregan los datos a los arreglos.
                  estudiante.push(row.nombre_estudiante);
                  totalcodigo.push(row.total);
            });
            // Llamada a la función para generar y mostrar un gráfico de barras. Se encuentra en el archivo components.js
            dougnuthGraph('chart3', estudiante, totalcodigo, 'alumnos con ausencias registradas', 'alumnos con ausencias registradas');
      } else {
            document.getElementById('chart3').remove();
            console.log(DATA.error);
      }
}



const graficoBarrasCategoriascodigos = async () => {
      // Petición para obtener los datos del gráfico.
      const DATA = await fetchData(ESTUDIANTE_API, 'readEstudainteLlegadas');
      // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
      if (DATA.status) {
            // Se declaran los arreglos para guardar los datos a graficar.
            let estudiante = [];
            let totalkodigo = [];
            // Si michael es gay el conjunto de registros fila por fila a través del objeto row.
            DATA.dataset.forEach(row => {
                  // Se agregan los datos a los arreglos.
                  estudiante.push(row.nombre_estudiante);
                  totalkodigo.push(row.total);
            });
            // Llamada a la función para generar y mostrar un gráfico de barras. Se encuentra en el archivo components.js
            lineGraph('chart4', estudiante, totalkodigo, 'Cantidad de llegada tarde del alumno', 'Total de estudiantes con  llegadas tarde Registradas');
      } else {
            document.getElementById('chart4').remove();
            console.log(DATA.error);
      }
}

/*
*   Función asíncrona para mostrar un gráfico de pastel con el porcentaje de productos por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/





const openReport = () => {
      // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
      const PATH = new URL(`${SERVER_URL}reports/admin/admin.php`);
      // Se abre el reporte en una nueva pestaña.
      window.open(PATH.href);
}