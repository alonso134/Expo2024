const NOTAS_API = '../../services/admin/notas.php';  
const TABLE_BODY = document.getElementById('tableBody');  

// Cuando el documento está cargado
document.addEventListener('DOMContentLoaded', () => {
    fillTable();  // Llena la tabla al cargar
});

// Manejar envío de formulario para guardar registro
document.getElementById('saveForm').addEventListener('submit', async (event) => {
    event.preventDefault();  // Evita el envío por defecto del formulario

    // Recolectar datos del formulario
    const formData = new FormData(document.getElementById('saveForm'));

    // Enviar datos al backend
    const result = await fetchDataFromAPI(NOTAS_API, 'createRow', formData);

    // Mostrar mensaje o manejar la respuesta del backend
    if (result.status === 1) {
        // Éxito: muestra mensaje o realiza acciones necesarias
        sweetAlert(1, result.message, false);
        $('#saveModal').modal('hide');  // Oculta el modal después de guardar
        fillTable();  // Vuelve a llenar la tabla después de guardar
    } else {
        // Error: muestra mensaje de error
        sweetAlert(4, result.error, true);
    }
});

// Función para llenar la tabla con registros
const fillTable = async () => {
    // Limpia el cuerpo de la tabla
    TABLE_BODY.innerHTML = '';

    // Realiza la solicitud al backend para obtener registros
    const DATA = await fetchDataFromAPI(NOTAS_API, 'readAll');

    // Maneja la respuesta del backend
    if (DATA.status) {
        DATA.dataset.forEach(row => {
            // Crea y agrega filas a la tabla
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.nombreEstudiante}</td>
                    <td>${row.TipoCodigo}</td>
                    <td>${row.NombreProfesor}</td>
                    <td>${row.FechaCodigo}</td>
                    <td>${row.descripcionCodigo}</td>
                    <td>
                        <button type="button" class="btn btn-success" onclick="viewRecord(${row.id})">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button type="button" class="btn btn-primary" onclick="openUpdate(${row.id})">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="openDelete(${row.id})">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    } else {
        // Maneja errores
        sweetAlert(4, DATA.error, true);
    }
};

// Función para enviar datos a la API
const fetchDataFromAPI = async (api, action, formData = null) => {
    const options = {
        method: 'POST',
        body: formData
    };

    try {
        const response = await fetch(`${api}?action=${action}`, options);
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return await response.json();  // Devuelve los datos en formato JSON
    } catch (error) {
        console.error('Error:', error);
        return { status: 0, error: 'Ocurrió un error en la solicitud.' };
    }
};
