document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('myForm');
    const tableBody = document.getElementById('data');
    const codigoSelect = document.getElementById('codigo');
    const tipocodigoSelect = document.getElementById('tipocodigo');
    let editingRow = null;
    let deletingRow = null;

    const codigosPositivosEstudiantes = [
        { codigo: 'POS001', descripcion: 'Incremento de ventas' },
        { codigo: 'POS002', descripcion: 'Mejora en la satisfacción del cliente' },
        { codigo: 'POS003', descripcion: 'Reducción de costos operativos' },
        { codigo: 'POS004', descripcion: 'Implementación exitosa de nueva tecnología' },
        { codigo: 'POS005', descripcion: 'Aumento en la eficiencia del proceso' },
        { codigo: 'POS006', descripcion: 'Desarrollo de nuevos productos exitosos' },
        { codigo: 'POS007', descripcion: 'Mejora en la retención de empleados' },
        { codigo: 'POS008', descripcion: 'Expansión exitosa a nuevos mercados' },
        { codigo: 'POS009', descripcion: 'Incremento en la productividad del equipo' },
        { codigo: 'POS010', descripcion: 'Obtención de reconocimientos o premios' }
    ];

    const codigosNegativosEstudiantes = [
        { codigo: 'NEG001', descripcion: 'Falta de respeto hacia los compañeros' },
        { codigo: 'NEG002', descripcion: 'Comportamiento disruptivo en clase' },
        { codigo: 'NEG003', descripcion: 'Falta de cumplimiento de reglas' },
        { codigo: 'NEG004', descripcion: 'Falta de entrega de tareas' },
        { codigo: 'NEG005', descripcion: 'Actitud negativa hacia el aprendizaje' },
        { codigo: 'NEG006', descripcion: 'Falta de participación en clase' },
        { codigo: 'NEG007', descripcion: 'Uso inapropiado de dispositivos electrónicos' },
        { codigo: 'NEG008', descripcion: 'Violación del código de honor' },
        { codigo: 'NEG009', descripcion: 'Incumplimiento de la política de asistencia' },
        { codigo: 'NEG010', descripcion: 'Plagio académico' }
    ];

    function obtenerDescripcion(codigo, tipoCodigo) {
        const codigos = tipoCodigo === 'Positivo' ? codigosPositivosEstudiantes : codigosNegativosEstudiantes;
        const codigoEncontrado = codigos.find(c => c.codigo === codigo);
        return codigoEncontrado ? codigoEncontrado.descripcion : '';
    }

    function llenarCodigos(tipocodigo) {
        codigoSelect.innerHTML = '<option value="">Seleccione el código</option>';
        const codigos = tipocodigo === 'Positivo' ? codigosPositivosEstudiantes : codigosNegativosEstudiantes;
        codigos.forEach(codigo => {
            const option = document.createElement('option');
            option.value = codigo.codigo;
            option.textContent = codigo.descripcion;
            codigoSelect.appendChild(option);
        });
    }

    tipocodigoSelect.addEventListener('change', function () {
        llenarCodigos(this.value);
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const name = document.getElementById('name').value;
        const grade = document.getElementById('grade').value;
        const seccion = document.getElementById('seccion').value;
        const carnet = document.getElementById('carnet').value;
        const tipocodigo = document.getElementById('tipocodigo').value;
        const codigo = codigoSelect.value;

        if (name && grade && seccion && carnet && tipocodigo && codigo) {
            if (editingRow) {
                editingRow.cells[1].textContent = name;
                editingRow.cells[2].textContent = grade;
                editingRow.cells[3].textContent = seccion;
                editingRow.cells[4].textContent = carnet;
                editingRow.cells[5].textContent = tipocodigo;
                editingRow.cells[6].textContent = obtenerDescripcion(codigo, tipocodigo);
                editingRow.cells[7].innerHTML = `
                    <button class="btn btn-info viewBtn">Ver</button>
                    <button class="btn btn-warning editBtn">Editar</button>
                    <button class="btn btn-danger deleteBtn">Eliminar</button>
                `;
                editingRow = null;
            } else {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>${tableBody.children.length + 1}</td>
                    <td>${name}</td>
                    <td>${grade}</td>
                    <td>${seccion}</td>
                    <td>${carnet}</td>
                    <td>${tipocodigo}</td>
                    <td>${obtenerDescripcion(codigo, tipocodigo)}</td>
                    <td>
                        <button class="btn btn-info viewBtn">Ver</button>
                        <button class="btn btn-warning editBtn">Editar</button>
                        <button class="btn btn-danger deleteBtn">Eliminar</button>
                    </td>
                `;

                tableBody.appendChild(newRow);
            }

            resetForm();

            const modal = bootstrap.Modal.getInstance(document.getElementById('userForm'));
            modal.hide();
        } else {
            alert('Por favor complete todos los campos.');
        }
    });

    function resetForm() {
        form.reset();
        codigoSelect.innerHTML = '<option value="">Seleccione el código</option>';
    }

    tableBody.addEventListener('click', function (event) {
        const target = event.target;

        if (target.classList.contains('viewBtn')) {
            const row = target.closest('tr');
            const cells = row.cells;
            document.getElementById('detailName').textContent = cells[1].textContent;
            document.getElementById('detailGrade').textContent = cells[2].textContent;
            document.getElementById('detailSeccion').textContent = cells[3].textContent;
            document.getElementById('detailCarnet').textContent = cells[4].textContent;
            document.getElementById('detailTipoCodigo').textContent = cells[5].textContent;
            document.getElementById('detailCodigo').textContent = cells[6].textContent;
            const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
            detailsModal.show();
        }

        if (target.classList.contains('editBtn')) {
            const row = target.closest('tr');
            const cells = row.cells;
            document.getElementById('name').value = cells[1].textContent;
            document.getElementById('grade').value = cells[2].textContent;
            document.getElementById('seccion').value = cells[3].textContent;
            document.getElementById('carnet').value = cells[4].textContent;
            document.getElementById('tipocodigo').value = cells[5].textContent;
            llenarCodigos(cells[5].textContent);
            codigoSelect.value = cells[6].textContent;
            editingRow = row;
            const modal = new bootstrap.Modal(document.getElementById('userForm'));
            modal.show();
        }

        if (target.classList.contains('deleteBtn')) {
            const row = target.closest('tr');
            deletingRow = row;
            const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            confirmDeleteModal.show();
        }
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (deletingRow) {
            deletingRow.remove();
            const confirmDeleteModal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
            confirmDeleteModal.hide();
            deletingRow = null;
        }
    });

});
