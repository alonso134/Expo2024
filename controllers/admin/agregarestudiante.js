// Constantes para completar las rutas de la API.
const ESTUDIANTE_API = 'services/admin/estudiante.php';
const GRADO_API = 'services/admin/grado.php';
const CODIGO_API = 'services/admin/codigo.php';
const NOTA_API = 'services/admin/notas.php';
const TARDE_API = 'services/admin/llegadatarde.php';
const OBSERVACION_API = 'services/admin/observaciones.php';
// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');
// Constantes para establecer el contenido de la tabla.
const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');
// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#saveModal'),
    MODAL_TITLE = document.getElementById('modalTitle');
// Constantes para establecer los elementos del componente Modal.
const GRAPHIC_MODAL = new bootstrap.Modal('#graphicModal'),
    MODAL_TITLE2 = document.getElementById('modalTitle1');
// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM = document.getElementById('saveForm'),
    ID_ESTUDIANTE = document.getElementById('idEstudiante'),
    NOMBRE_ESTUDIANTE = document.getElementById('nombreEstudiante'),
    APELLIDO_ESTUDIANTE = document.getElementById('apellidoEstudiante'),
    CORREO_ESTUDIANTE = document.getElementById('correoEstudiante'),
    CLAVE_ESTUDIANTE = document.getElementById('claveEstudiante'),
    CONFIRMAR_CLAVE = document.getElementById('confirmarClave'),
    NACIMIENTO_ESTUDIANTE = document.getElementById('nacimientoEstudiante');


// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Gestionar Estudiantes';
    // Llamada a la función para llenar la tabla con los registros existentes.
    fillTable();
});

// Método del evento para cuando se envía el formulario de buscar.
SEARCH_FORM.addEventListener('submit', (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SEARCH_FORM);
    // Llamada a la función para llenar la tabla con los resultados de la búsqueda.
    fillTable(FORM);
});

// Método del evento para cuando se envía el formulario de guardar.
SAVE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (ID_ESTUDIANTE.value) ? action = 'updateRow' : action = 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(ESTUDIANTE_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se cierra la caja de diálogo.
        SAVE_MODAL.hide();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, DATA.message, true);
        // Se carga nuevamente la tabla para visualizar los cambios.
        fillTable();
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

/*
*   Función asíncrona para llenar la tabla con los registros disponibles.
*   Parámetros: form (objeto opcional con los datos de búsqueda).
*   Retorno: ninguno.
*/
const fillTable = async (form = null) => {
    // Se inicializa el contenido de la tabla.
    ROWS_FOUND.textContent = '';
    TABLE_BODY.innerHTML = '';
    // Se verifica la acción a realizar.
    (form) ? action = 'searchRows' : action = 'readAll';
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(ESTUDIANTE_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.nombre_estudiante}</td>
                    <td>${row.apellido_estudiante}</td>
                    <td>${row.correo_estudiante}</td>
                    <td>${row.fecha_de_nacimiento}</td>
                    <td>${row.nombre}</td>
                    <td>
                        <button type="button" class="btn btn-info" onclick="openUpdate(${row.id_estudiante})">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="openDelete(${row.id_estudiante})">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                        <button type="button" class="btn btn-warning" onclick="openReportP(${row.id_estudiante})">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </button>
                        <button type="button" class="btn btn-warning" onclick="openGraphic(${row.id_estudiante})">
                            <i class="bi bi-bar-chart"></i>
                        </button>
                        <button type="button" class="btn btn-warning" onclick="openReportPredictivo(${row.id_estudiante})">
                             <i class="bi bi-file-earmark-text-fill"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        // Se muestra un mensaje de acuerdo con el resultado.
        ROWS_FOUND.textContent = DATA.message;
    } else {
        sweetAlert(4, DATA.error, true);
    }
}

/*
*   Función para preparar el formulario al momento de insertar un registro.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const openCreate = () => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL.show();
    MODAL_TITLE.textContent = 'Crear Estudiante';
    // Se prepara el formulario.
    SAVE_FORM.reset();
    CLAVE_ESTUDIANTE.disabled = false;
    CONFIRMAR_CLAVE.disabled = false;
    fillSelect(GRADO_API, 'readAll', 'nombreGrado');
}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openUpdate = async (id) => {
    // Se define un objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('idEstudiante', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(ESTUDIANTE_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Actualizar Estudiante';
        // Se prepara el formulario.
        SAVE_FORM.reset();
        CORREO_ESTUDIANTE.disabled = true;
        CLAVE_ESTUDIANTE.disabled = true;
        CONFIRMAR_CLAVE.disabled = true;
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_ESTUDIANTE.value = ROW.id_estudiante;
        NOMBRE_ESTUDIANTE.value = ROW.nombre_estudiante;
        APELLIDO_ESTUDIANTE.value = ROW.apellido_estudiante;
        NACIMIENTO_ESTUDIANTE.value = ROW.fecha_de_nacimiento;
        fillSelect(GRADO_API, 'readAll', 'nombreGrado', ROW.id_grado);
    } else {
        sweetAlert(2, DATA.error, false);
    }
}

/*
*   Función asíncrona para eliminar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openDelete = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar al estudiante de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('idEstudiante', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(ESTUDIANTE_API, 'deleteRow', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se muestra un mensaje de éxito.
            await sweetAlert(1, DATA.message, true);
            // Se carga nuevamente la tabla para visualizar los cambios.
            fillTable();
        } else {
            sweetAlert(2, DATA.error, false);
        }
    }
}

/*
*   Función para abrir un reporte automático de productos por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/


const openReport = () => {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/admin/alumnos.php`);
    // Se abre el reporte en una nueva pestaña.
    window.open(PATH.href);
}

/*
*   Función para abrir un reporte parametrizado.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openReportP = (id) => {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/admin/estudiante_conducta.php`);
    // Se agrega un parámetro a la ruta con el valor del registro seleccionado.
    PATH.searchParams.append('id', id);
    // Se abre el reporte en una nueva pestaña.
    window.open(PATH.href);
}

/*
*   Función para abrir un reporte parametrizado.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openReportPredictivo = (id) => {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/admin/predictivo_estudiante_nota.php`);
    // Se agrega un parámetro a la ruta con el valor del registro seleccionado.
    PATH.searchParams.append('id', id);
    // Se abre el reporte en una nueva pestaña.
    window.open(PATH.href);
}

/*
*   Función para abrir la gráfica al momento.
*   Parámetros: id.
*   Retorno: ninguno.
*/
const openGraphic = (id) => {
    // Se muestra la caja de diálogo con su título.
    GRAPHIC_MODAL.show();
    MODAL_TITLE2.textContent = 'Gráficos de datos de estudiantes, de los ultimos tres meses';
    const FORM = new FormData();
    FORM.append('estudiante', id);
    cargarGraficaLlegadaTarde(FORM);
    cargarGraficaConducta(FORM);
    cargarGraficaNotas(FORM);
    cargarGraficaObservacion(FORM);
    cargarGraficaPredictiva(FORM);
    cargarGraficaPredictiva2(FORM);
}

let chartInstance1 = null;
let chartInstance2 = null;
let chartInstance3 = null;
let chartInstance4 = null;

// Función para cargar la gráfica lineal
const cargarGraficaLlegadaTarde = async (FORM) => {
    try {
        // Mandamos la peticion a la API para traernos la informacion correspondiente.
        const DATA = await fetchData(TARDE_API, 'llegadasTardePorEstudiante', FORM);
        if (DATA.status) {
            let fecha = [];
            let numero = [];
            DATA.dataset.forEach(row => {
                fecha.push(row.fecha);
                numero.push(row.id);
            });

            // Destruir la instancia existente del gráfico si existe
            if (chartInstance1) {
                chartInstance1.destroy();
                chartInstance1 = null; // Asegúrate de restablecer la referencia
            }

            // Restablecer el canvas en caso de que sea necesario
            const canvasContainer = document.getElementById('llegadasTarde').parentElement;
            canvasContainer.innerHTML = '<canvas id="llegadasTarde"></canvas> <div id="error_tarde"></div>';

            // Llamada a la función para generar y mostrar un gráfico lineal.
            chartInstance1 = lineGraphWithFill('llegadasTarde', fecha, numero, 'Llegadas tarde por estudiante', 'Gráfica de llegadas tarde por estudiante ');
        } else {
            console.log(DATA.error);
            // Destruir la instancia existente del gráfico si existe
            if (chartInstance1) {
                chartInstance1.destroy();
                chartInstance1 = null; // Asegúrate de restablecer la referencia
            }
            // Restablecer el canvas en caso de que sea necesario
            const canvasContainer = document.getElementById('llegadasTarde').parentElement;
            canvasContainer.innerHTML = ' <div id="error_tarde"></div> <canvas id="llegadasTarde"></canvas>';

            // Restablecer o crear el contenedor
            errorContainer = document.getElementById('error_tarde');
            errorContainer.innerHTML += '';
            const tablaHtml = `
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                           <p class="text-primary">${DATA.error} </p>
                        </div>
                    </div>
                </div>
            </div>
            `;
            errorContainer.innerHTML += tablaHtml;
            chartInstance1 = null;
        }
    } catch (error) {
        console.log('Error:', error);
    }
}
// Función para cargar la gráfica lineal
const cargarGraficaConducta = async (FORM) => {
    try {
        // Mandamos la peticion a la API para traernos la informacion correspondiente.
        const DATA = await fetchData(CODIGO_API, 'codigosPorEstudiantes', FORM);
        if (DATA.status) {
            let fecha = [];
            let numero = [];
            DATA.dataset.forEach(row => {
                fecha.push(row.fecha);
                numero.push(row.id);
            });

            // Destruir la instancia existente del gráfico si existe
            if (chartInstance2) {
                chartInstance2.destroy();
                chartInstance2 = null; // Asegúrate de restablecer la referencia
            }

            // Restablecer el canvas en caso de que sea necesario
            const canvasContainer = document.getElementById('codigos').parentElement;
            canvasContainer.innerHTML = '<canvas id="codigos"></canvas> <div id="error_codigos"></div>';

            // Llamada a la función para generar y mostrar un gráfico lineal.
            chartInstance2 = lineGraphWithFill('codigos', fecha, numero, 'Códigos por estudiante', 'Gráfica de codigos por estudiante ');
        } else {
            console.log(DATA.error);
            // Destruir la instancia existente del gráfico si existe
            if (chartInstance2) {
                chartInstance2.destroy();
                chartInstance2 = null; // Asegúrate de restablecer la referencia
            }
            // Restablecer el canvas en caso de que sea necesario
            const canvasContainer = document.getElementById('codigos').parentElement;
            canvasContainer.innerHTML = ' <div id="error_codigos"></div> <canvas id="codigos"></canvas>';

            // Restablecer o crear el contenedor
            errorContainer = document.getElementById('error_codigos');
            errorContainer.innerHTML += '';
            const tablaHtml = `
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                           <p class="text-primary">${DATA.error} </p>
                        </div>
                    </div>
                </div>
            </div>
            `;
            errorContainer.innerHTML += tablaHtml;
            chartInstance2 = null;
        }
    } catch (error) {
        console.log('Error:', error);
    }
}

// Función para cargar la gráfica lineal
const cargarGraficaNotas = async (FORM) => {
    try {
        // Mandamos la peticion a la API para traernos la informacion correspondiente.
        const DATA = await fetchData(NOTA_API, 'notasPorEstudiante', FORM);
        if (DATA.status) {
            let materia = [];
            let nota = [];
            DATA.dataset.forEach(row => {
                materia.push(row.materia);
                nota.push(row.promedio_nota);
            });

            // Destruir la instancia existente del gráfico si existe
            if (chartInstance2) {
                chartInstance2.destroy();
                chartInstance2 = null; // Asegúrate de restablecer la referencia
            }

            // Restablecer el canvas en caso de que sea necesario
            const canvasContainer = document.getElementById('notas').parentElement;
            canvasContainer.innerHTML = '<canvas id="notas"></canvas> <div id="error_notas"></div>';

            // Llamada a la función para generar y mostrar un gráfico barras.
            chartInstance2 = barGraph1('notas', materia, nota, 'Promedios por estudiante', 'Gráfica de promedio de notas por estudiante ');
        } else {
            console.log(DATA.error);
            // Destruir la instancia existente del gráfico si existe
            if (chartInstance2) {
                chartInstance2.destroy();
                chartInstance2 = null; // Asegúrate de restablecer la referencia
            }
            // Restablecer el canvas en caso de que sea necesario
            const canvasContainer = document.getElementById('notas').parentElement;
            canvasContainer.innerHTML = ' <div id="error_notas"></div> <canvas id="notas"></canvas>';

            // Restablecer o crear el contenedor
            errorContainer = document.getElementById('error_notas');
            errorContainer.innerHTML += '';
            const tablaHtml = `
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                           <p class="text-primary">${DATA.error} </p>
                        </div>
                    </div>
                </div>
            </div>
            `;
            errorContainer.innerHTML += tablaHtml;
            chartInstance2 = null;
        }
    } catch (error) {
        console.log('Error:', error);
    }
}

// Función para cargar la gráfica lineal
const cargarGraficaObservacion = async (FORM) => {
    try {
        // Mandamos la peticion a la API para traernos la informacion correspondiente.
        const DATA = await fetchData(OBSERVACION_API, 'observacionesPorEstudiante', FORM);
        if (DATA.status) {
            let fecha = [];
            let numero = [];
            let observaciones = [];
            DATA.dataset.forEach(row => {
                fecha.push(row.fecha);
                numero.push(row.id);
                observaciones.push(row.fecha);
            });

            // Destruir la instancia existente del gráfico si existe
            if (chartInstance2) {
                chartInstance2.destroy();
                chartInstance2 = null; // Asegúrate de restablecer la referencia
            }

            // Restablecer el canvas en caso de que sea necesario
            const canvasContainer = document.getElementById('observaciones').parentElement;
            canvasContainer.innerHTML = '<canvas id="observaciones"></canvas> <div id="error_observaciones"></div>';

            // Llamada a la función para generar y mostrar un gráfico lineal.
            chartInstance2 = lineGraphWithFill('observaciones', fecha, numero, observaciones, 'Gráfica de observaciones por estudiante', 'Gráfica de observaciones por estudiante ');
        } else {
            console.log(DATA.error);
            // Destruir la instancia existente del gráfico si existe
            if (chartInstance2) {
                chartInstance2.destroy();
                chartInstance2 = null; // Asegúrate de restablecer la referencia
            }
            // Restablecer el canvas en caso de que sea necesario
            const canvasContainer = document.getElementById('observaciones').parentElement;
            canvasContainer.innerHTML = ' <div id="error_observaciones"></div> <canvas id="observaciones"></canvas>';

            // Restablecer o crear el contenedor
            errorContainer = document.getElementById('error_observaciones');
            errorContainer.innerHTML += '';
            const tablaHtml = `
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                           <p class="text-primary">${DATA.error} </p>
                        </div>
                    </div>
                </div>
            </div>
            `;
            errorContainer.innerHTML += tablaHtml;
            chartInstance2 = null;
        }
    } catch (error) {
        console.log('Error:', error);
    }
}


// Función para cargar la gráfica lineal
const cargarGraficaPredictiva = async (FORM) => {
    try {
        // Mandamos la peticion a la API para traernos la informacion correspondiente.
        const DATA = await fetchData(NOTA_API, 'predictNotasPromedioPorEstudianteSiguienteSemana', FORM);
        if (DATA.status) {
            let fecha = [];
            let numero = [];
            DATA.dataset.forEach(row => {
                fecha.push(row.fecha);
                numero.push(row.promedio);
            });

            // Destruir la instancia existente del gráfico si existe
            if (chartInstance1) {
                chartInstance1.destroy();
                chartInstance1 = null; // Asegúrate de restablecer la referencia
            }

            // Restablecer el canvas en caso de que sea necesario
            const canvasContainer = document.getElementById('prediccion').parentElement;
            canvasContainer.innerHTML = '<canvas id="prediccion"></canvas> <div id="error_prediccion"></div>';

            // Llamada a la función para generar y mostrar un gráfico lineal.
            chartInstance1 = lineGraphWithFill('prediccion', fecha, numero, 'Prediccion de notas promedio del estudiante', 'Gráfica de notas promedios por estudiante para la siguiente semana');
        } else {
            console.log(DATA.error);
            // Destruir la instancia existente del gráfico si existe
            if (chartInstance1) {
                chartInstance1.destroy();
                chartInstance1 = null; // Asegúrate de restablecer la referencia
            }
            // Restablecer el canvas en caso de que sea necesario
            const canvasContainer = document.getElementById('prediccion').parentElement;
            canvasContainer.innerHTML = ' <div id="error_prediccion"></div> <canvas id="prediccion"></canvas>';

            // Restablecer o crear el contenedor
            errorContainer = document.getElementById('error_prediccion');
            errorContainer.innerHTML += '';
            const tablaHtml = `
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                           <p class="text-primary">${DATA.error} </p>
                        </div>
                    </div>
                </div>
            </div>
            `;
            errorContainer.innerHTML += tablaHtml;
            chartInstance1 = null;
        }
    } catch (error) {
        console.log('Error:', error);
        // Destruir la instancia existente del gráfico si existe
        if (chartInstance2) {
            chartInstance2.destroy();
            chartInstance2 = null; // Asegúrate de restablecer la referencia
        }
        // Restablecer el canvas en caso de que sea necesario
        const canvasContainer = document.getElementById('prediccion').parentElement;
        canvasContainer.innerHTML = ' <div id="error_prediccion"></div> <canvas id="prediccion"></canvas>';

        // Restablecer o crear el contenedor
        errorContainer = document.getElementById('error_prediccion');
        errorContainer.innerHTML += '';
        const tablaHtml = `
        <div class="col-md-12 row d-flex text-center justify-content-center">
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                            <p class="text-primary">No hay datos suficientes para mostrar la gráfica predictiva</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;
        errorContainer.innerHTML += tablaHtml;
        chartInstance2 = null;
    }
}

// Función para cargar la gráfica lineal
const cargarGraficaPredictiva2 = async (FORM) => {
    try {
        // Mandamos la peticion a la API para traernos la informacion correspondiente.
        const DATA = await fetchData(TARDE_API, 'predictLlegadasTardeSiguienteMes', FORM);
        if (DATA.status) {
            let fecha = [];
            let numero = [];
            DATA.dataset.forEach(row => {
                fecha.push(row.fecha);
                numero.push(row.llegadas_tarde);
            });

            // Destruir la instancia existente del gráfico si existe
            if (chartInstance1) {
                chartInstance1.destroy();
                chartInstance1 = null; // Asegúrate de restablecer la referencia
            }

            // Restablecer el canvas en caso de que sea necesario
            const canvasContainer = document.getElementById('prediccion2').parentElement;
            canvasContainer.innerHTML = '<canvas id="prediccion2"></canvas> <div id="error_prediccion2"></div>';

            // Llamada a la función para generar y mostrar un gráfico lineal.
            chartInstance1 = lineGraphWithFill('prediccion2', fecha, numero, 'Prediccion de llegadas tarde del estudiante', 'Gráfica de llegadas tarde por estudiante para la siguiente semana');
        } else {
            console.log(DATA.error);
            // Destruir la instancia existente del gráfico si existe
            if (chartInstance1) {
                chartInstance1.destroy();
                chartInstance1 = null; // Asegúrate de restablecer la referencia
            }
            // Restablecer el canvas en caso de que sea necesario
            const canvasContainer = document.getElementById('prediccion2').parentElement;
            canvasContainer.innerHTML = ' <div id="error_prediccion2"></div> <canvas id="prediccion2"></canvas>';

            // Restablecer o crear el contenedor
            errorContainer = document.getElementById('error_prediccion2');
            errorContainer.innerHTML += '';
            const tablaHtml = `
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                           <p class="text-primary">${DATA.error} </p>
                        </div>
                    </div>
                </div>
            </div>
            `;
            errorContainer.innerHTML += tablaHtml;
            chartInstance1 = null;
        }
    } catch (error) {
        console.log('Error:', error);
        // Destruir la instancia existente del gráfico si existe
        if (chartInstance2) {
            chartInstance2.destroy();
            chartInstance2 = null; // Asegúrate de restablecer la referencia
        }
        // Restablecer el canvas en caso de que sea necesario
        const canvasContainer = document.getElementById('prediccion2').parentElement;
        canvasContainer.innerHTML = ' <div id="error_prediccion2"></div> <canvas id="prediccion2"></canvas>';

        // Restablecer o crear el contenedor
        errorContainer = document.getElementById('error_prediccion2');
        errorContainer.innerHTML += '';
        const tablaHtml = `
        <div class="col-md-12 row d-flex text-center justify-content-center">
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                            <p class="text-primary">No hay datos suficientes para mostrar la gráfica predictiva</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;
        errorContainer.innerHTML += tablaHtml;
        chartInstance2 = null;
    }
}
