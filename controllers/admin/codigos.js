document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('myForm');
    const tableBody = document.getElementById('data');

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        // Obtener los valores del formulario
        const name = document.getElementById('name').value;
        const age = document.getElementById('age').value;
        const city = document.getElementById('city').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const post = document.getElementById('post').value;
        const sDate = document.getElementById('sDate').value;

        // Crear una nueva fila en la tabla con los datos del formulario
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
<td>${tableBody.children.length + 1}</td>
<td>Image</td>
<td>${name}</td>
<td>${age}</td>
<td>${city}</td>
<td>${email}</td>
<td>${phone}</td>
<td>${post}</td>
<td>${sDate}</td>
<td>
    <button class="btn btn-info viewBtn" data-bs-toggle="modal" data-bs-target="#readData">Ver</button>
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

    // Función para mostrar los datos en el modal de lectura
    tableBody.addEventListener('click', function (event) {
        const target = event.target;
        if (target.classList.contains('viewBtn')) {
            const row = target.closest('tr');
            const cells = row.querySelectorAll('td');
            document.getElementById('showName').value = cells[2].textContent;
            document.getElementById('showAge').value = cells[3].textContent;
            document.getElementById('showCity').value = cells[4].textContent;
            document.getElementById('showEmail').value = cells[5].textContent;
            document.getElementById('showPhone').value = cells[6].textContent;
            document.getElementById('showPost').value = cells[7].textContent;
            document.getElementById('showsDate').value = cells[8].textContent;
        }
    });

    // Función para eliminar una fila de la tabla
    tableBody.addEventListener('click', function (event) {
        const target = event.target;
        if (target.classList.contains('deleteBtn')) {
            const row = target.closest('tr');
            row.remove();
        }
    });

    // Función para editar una fila de la tabla
    tableBody.addEventListener('click', function (event) {
        const target = event.target;
        if (target.classList.contains('editBtn')) {
            const row = target.closest('tr');
            const cells = row.querySelectorAll('td');
            document.getElementById('name').value = cells[2].textContent;
            document.getElementById('age').value = cells[3].textContent;
            document.getElementById('city').value = cells[4].textContent;
            document.getElementById('email').value = cells[5].textContent;
            document.getElementById('phone').value = cells[6].textContent;
            document.getElementById('post').value = cells[7].textContent;
            document.getElementById('sDate').value = cells[8].textContent;
            row.remove();
        }
    });
});