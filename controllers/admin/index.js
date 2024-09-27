// Constante para establecer el formulario de registro del primer usuario.
const SIGNUP_FORM = document.getElementById('signupForm');
// Constante para establecer el formulario de inicio de sesión.
const LOGIN_FORM = document.getElementById('loginForm');

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Petición para consultar los usuarios registrados.
    const DATA = await fetchData(USER_API, 'readUsers');
    // Se comprueba si existe una sesión, de lo contrario se sigue con el flujo normal.
    if (DATA.session) {
        // Se direcciona a la página web de bienvenida.
        location.href = 'inicio.html';
    } else if (DATA.status) {
        // Se establece el título del contenido principal.
        MAIN_TITLE.textContent = 'Iniciar sesión';
        // Se muestra el formulario para iniciar sesión.
        LOGIN_FORM.classList.remove('d-none');
        sweetAlert(4, DATA.message, true);
    } else {
        // Se establece el título del contenido principal.
        MAIN_TITLE.textContent = 'Registrar primer usuario';
        // Se muestra el formulario para registrar el primer usuario.
        SIGNUP_FORM.classList.remove('d-none');
        sweetAlert(4, DATA.error, true);
    }
        // Evento cuando se haga clic en "¿Olvidaste tu contraseña?"
        document.querySelector('a[href="recuperacion_clave.html"]').addEventListener('click', async (event) => {
            event.preventDefault(); // Prevenir comportamiento por defecto del enlace
    
            const correo = document.getElementById('correo').value; // Obtener el correo ingresado
    
            if (!correo) {
                sweetAlert(2, "Por favor, ingresa tu correo para recuperar la contraseña.", false);
                return;
            }
    
            // Llamar a la API para enviar el correo de recuperación
            const FORM = new FormData();
            FORM.append('correo', correo);
    
            const DATA = await fetchData(USER_API, 'emailPasswordSender', FORM);
    
            if (DATA.status) {
                sweetAlert(1, "Se ha enviado un código a tu correo.", true);
                // Redirigir a la página de recuperaciónfd
                window.location.href = 'recuperacion_clave.html?token=' + DATA.dataset; // Pasa el token en la URL
            } else {
                sweetAlert(2, DATA.error, false);
            }
        });
});

// Método del evento para cuando se envía el formulario de registro del primer usuario.
SIGNUP_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Petición para consultar los usuarios registrados.
    const DATA = await fetchData(USER_API, 'readUsers');
    // Se comprueba si existe una sesión, de lo contrario se sigue con el flujo normal.
    if (DATA.session) {
        // Se direcciona a la página web de bienvenida.
        location.href = 'inicio.html';
    } else if (DATA.status) {
        // Se direcciona a la página web de bienvenida.
        location.href = 'index.html';
    } else {
        // Constante tipo objeto con los datos del formulario.
        const FORM = new FormData(SIGNUP_FORM);
        // Petición para registrar el primer usuario del sitio privado.
        const DATA = await fetchData(USER_API, 'signUp', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            sweetAlert(1, DATA.message, true, 'index.html');
        } else {
            sweetAlert(2, DATA.error, false);
        }
    }
});

// Método del evento para cuando se envía el formulario de inicio de sesión.
LOGIN_FORM.addEventListener('submit', async (event) => {
    event.preventDefault();

    const FORM = new FormData(LOGIN_FORM);

    // Enviar correo con el código de verificación
    const DATA = await fetchData(USER_API, 'sendVerificationCode', FORM);
    if (DATA.status) {
        sweetAlert(1, "Se ha enviado un código de verificación a tu correo.", true);
        // Ocultar campos de correo y contraseña
        LOGIN_FORM.classList.add('d-none'); // Asegúrate de que esto oculte el formulario correctamente
        // Mostrar el contenedor del código de verificación
        document.getElementById('doubleCheckContainer').classList.remove('d-none');
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

// Evento para verificar el código de verificación
document.getElementById('verifyButton').addEventListener('click', async () => {
    const verificationCode = document.getElementById('verificacion').value;
    const FORM = new FormData(LOGIN_FORM); // Aquí no necesitas el formulario de login, ya que solo estás verificando
    FORM.append('verificacion', verificationCode);

    // Petición para iniciar sesión con el código de verificación
    const DATA = await fetchData(USER_API, 'logIn', FORM);

    if (DATA.status) {
        sweetAlert(1, DATA.message, true, 'inicio.html');
    } else {
        sweetAlert(2, DATA.error, false);
    }
});


/* 

// Método del evento para cuando se envía el formulario de inicio de sesión.
LOGIN_FORM.addEventListener('submit', async (event) => {
    event.preventDefault();

    const FORM = new FormData(LOGIN_FORM);

    // Enviar correo con el código de verificación
    const DATA = await fetchData(USER_API, 'sendVerificationCode', FORM);
    if (DATA.status) {
        sweetAlert(1, "Se ha enviado un código de verificación a tu correo.", true);
        // Ocultar campos de correo y contraseña
        LOGIN_FORM.classList.add('d-none'); // Asegúrate de que esto oculte el formulario correctamente
        // Mostrar el contenedor del código de verificación
        document.getElementById('doubleCheckContainer').classList.remove('d-none');
    } else {
        sweetAlert(2, DATA.error, false);
    }
});


*/