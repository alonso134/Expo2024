
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('myForm');
        const tableBody = document.getElementById('data');

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            // Obtener los valores del formulario
            const name = document.getElementById('name').value;
            const grade = document.getElementById('grade').value;
            const seccion = document.getElementById('seccion').value;
            const carnet = document.getElementById('carnet').value;

            // Crear una nueva fila en la tabla con los datos del formulario
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${tableBody.children.length + 1}</td>
                <td>${name}</td>
                <td>${grade}</td>
                <td>${seccion}</td>
                <td>${carnet}</td>
                <td class="action-column">
                    <button class="btn btn-info viewBtn">Ver</button>
                    <button class="btn btn-warning editBtn">Editar</button>
                    <button class="btn btn-danger deleteBtn">Eliminar</button>
                </td>
            `;

            // Agregar la nueva fila a la tabla
            tableBody.appendChild(newRow);

            // Limpiar el formulario después de agregar los datos
            form.reset();

            // Cerrar el modal después de agregar los datos
            const modal = bootstrap.Modal.getInstance(document.getElementById('userForm'));
            modal.hide();
        });

        // Agregar eventos a los botones de la tabla
        tableBody.addEventListener('click', function (event) {
            const target = event.target;

            if (target.classList.contains('viewBtn')) {
                // Lógica para ver
                console.log('Ver');
            } else if (target.classList.contains('editBtn')) {
                // Lógica para editar
                console.log('Editar');
            } else if (target.classList.contains('deleteBtn')) {
                // Lógica para eliminar
                console.log('Eliminar');
            }
        });
    });

