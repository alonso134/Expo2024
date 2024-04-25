document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('myForm');
    const tableBody = document.getElementById('data');
    const codigoSelect = document.getElementById('codigo'); // Obtener el select de código

    // Array que contiene los códigos positivos y negativos
    const codigosPositivos = [
        { codigo: 'POS001', descripcion: 'Incremento de ventas' },
        { codigo: 'POS002', descripcion: 'Mejora en la satisfacción del cliente' },
        // Agrega el resto de los códigos positivos aquí
    ];

    const codigosNegativos = [
        { codigo: 'NEG001', descripcion: 'Pérdida de clientes' },
        { codigo: 'NEG002', descripcion: 'Errores en el servicio' },
        // Agrega el resto de los códigos negativos aquí
    ];

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        // Obtener los valores del formulario
        const name = document.getElementById('name').value;
        const grade = document.getElementById('grade').value;
        const seccion = document.getElementById('seccion').value;
        const carnet = document.getElementById('carnet').value;
        const tipocodigo = document.getElementById('tipocodigo').value;
        const codigo = codigoSelect.value; // Obtener el valor seleccionado del select de código

        // Verificar si se han completado todos los campos
        if (name && grade && seccion && carnet && tipocodigo && codigo) {
            // Buscar la descripción del código seleccionado
            let descripcionCodigo;
            if (tipocodigo === 'Positivo') {
                descripcionCodigo = codigosPositivos.find(codigoPositivo => codigoPositivo.codigo === codigo).descripcion;
            } else if (tipocodigo === 'Negativo') {
                descripcionCodigo = codigosNegativos.find(codigoNegativo => codigoNegativo.codigo === codigo).descripcion;
            }

            // Crear una nueva fila en la tabla con los datos del formulario
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${tableBody.children.length + 1}</td>
                <td>${name}</td>
                <td>${grade}</td>
                <td>${seccion}</td>
                <td>${carnet}</td>
                <td>${tipocodigo}</td>
                <td>${descripcionCodigo}</td>
                <td class="action-column">
                    <button class="btn btn-info viewBtn">Ver</button>
                    <button class="btn btn-warning editBtn">Editar</button>
                    <button class="btn btn-danger deleteBtn">Eliminar</button>
                </td>
            `;
    
            // Agregar la nueva fila a la tabla
            tableBody.appendChild(newRow);
    
            // Limpiar el formulario después de agregar los datos
            resetForm();
    
            // Cerrar el modal después de agregar los datos
            const modal = bootstrap.Modal.getInstance(document.getElementById('userForm'));
            modal.hide();
        } else {
            alert('Por favor complete todos los campos.');
        }
    });

    // Filtrar la tabla cuando se selecciona un tipo de código
    const tipoCodigoSelect = document.getElementById('tipocodigo');

    tipoCodigoSelect.addEventListener('change', function () {
        const selectedValue = this.value;

        // Limpiar las opciones del select de código
        codigoSelect.innerHTML = '<option value="">Seleccione el código</option>';

        // Mostrar u ocultar las opciones según el tipo de código seleccionado
        if (selectedValue === 'Positivo') {
            codigosPositivos.forEach(function (codigo) {
                const option = document.createElement('option');
                option.value = codigo.codigo;
                option.textContent = codigo.descripcion;
                codigoSelect.appendChild(option);
            });
        } else if (selectedValue === 'Negativo') {
            codigosNegativos.forEach(function (codigo) {
                const option = document.createElement('option');
                option.value = codigo.codigo;
                option.textContent = codigo.descripcion;
                codigoSelect.appendChild(option);
            });
        }
    });

    // Función para restablecer el formulario
    function resetForm() {
        form.reset();
        codigoSelect.innerHTML = '<option value="">Seleccione el código</option>'; // Limpiar el select de código
    }
});
