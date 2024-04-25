document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('myForm');
    const tableBody = document.getElementById('data');
    const codigoSelect = document.getElementById('codigo'); // Obtener el select de código

    // Array que contiene los códigos positivos y negativos
    const codigosPositivos = [
        { codigo: 'POS011', descripcion: 'Participación activa en actividades extracurriculares' },
        { codigo: 'POS012', descripcion: 'Colaboración positiva con compañeros en proyectos escolares' },
        { codigo: 'POS013', descripcion: 'Mejora notable en el desempeño académico' },
        { codigo: 'POS014', descripcion: 'Actitud positiva hacia el aprendizaje' },
        { codigo: 'POS015', descripcion: 'Contribución significativa al ambiente escolar' },
        { codigo: 'POS016', descripcion: 'Voluntariado en actividades solidarias o comunitarias' },
        { codigo: 'POS017', descripcion: 'Creatividad destacada en proyectos o tareas escolares' },
        { codigo: 'POS018', descripcion: 'Respeto y tolerancia hacia los compañeros y maestros' },
        { codigo: 'POS019', descripcion: 'Cumplimiento puntual de tareas y responsabilidades escolares' },
        { codigo: 'POS020', descripcion: 'Liderazgo positivo dentro y fuera del aula' }
        // Agrega el resto de los códigos positivos aquí
    ];

    const codigosNegativos = [
        { codigo: 'NEG01', descripcion: 'Falta de respeto hacia los compañeros' },
        { codigo: 'NEG02', descripcion: 'Comportamiento disruptivo en clase' },
        { codigo: 'NEG03', descripcion: 'Bullying o acoso escolar' },
        { codigo: 'NEG04', descripcion: 'Falta de participación en actividades académicas' },
        { codigo: 'NEG05', descripcion: 'Incumplimiento de normas del colegio' },
        { codigo: 'NEG06', descripcion: 'Ausentismo injustificado' },
        { codigo: 'NEG07', descripcion: 'Uso inapropiado de dispositivos electrónicos en clase' },
        { codigo: 'NEG08', descripcion: 'Falta de atención o concentración en clase' },
        { codigo: 'NEG09', descripcion: 'Copiar en exámenes o trabajos' },
        { codigo: 'NEG010', descripcion: 'Intimidación a otros estudiantes' }
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
