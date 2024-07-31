/*
*   Controlador es de uso general en las páginas web del sitio público.
*   Sirve para manejar las plantillas del encabezado y pie del documento.
*/

// Constante para completar la ruta de la API.
const ESTUDIANTE_API = 'services/public/estudiante.php';
// Constante para establecer el elemento del contenido principal.
const MAIN = document.querySelector('main');
MAIN.style.paddingTop = '75px';
MAIN.style.paddingBottom = '100px';
MAIN.classList.add('container');
// Se establece el título de la página web.
document.querySelector('title').textContent = 'CECSL - SGE';
// Constante para establecer el elemento del título principal.
const MAIN_TITLE = document.getElementById('mainTitle');
MAIN_TITLE.classList.add('text-center', 'py-3');

/*  Función asíncrona para cargar el encabezado y pie del documento.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const loadTemplate = async () => {
    // Petición para obtener en nombre del usuario que ha iniciado sesión.
    const DATA = await fetchData(ESTUDIANTE_API, 'getUser');
    // Se comprueba si el usuario está autenticado para establecer el encabezado respectivo.
    if (DATA.session) {
        // Se verifica si la página web no es el inicio de sesión, de lo contrario se direcciona a la página web principal.
        if (!location.pathname.endsWith('login.html')) {
            // Se agrega el encabezado de la página web antes del contenido principal.
            MAIN.insertAdjacentHTML('beforebegin', `
                <header>
                <div class="menu ">
                <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
                <div class="container">
                    <a class="navbar-brand" href="index.html"><img src="../../resources/img/logo.png" height="50" alt="Booksadre"></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </li>
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav ms-auto">
                            <a class="nav-link" href="index.html"><i class="bi bi-shop"></i> Catálogo</a>
                            <a class="nav-link" href="cart.html"><i class="bi bi-cart"></i> Carrito</a> 
                            <a class="nav-link" href="historial.html"><i class="bi bi-clock-history"></i> Historial</a>
                            <a class="nav-link" href="profile.html"><i class="bi bi-person"></i> Perfil</a> 
                            <a class="nav-link" href="#" onclick="logOut()"><i class="bi bi-box-arrow-left"></i> Cerrar sesión</a>
                        </div>
                    </div>
                </div>
            </nav>
            </nav>
          </header>
            `);
        } else {
            location.href = 'index.html';
        }
    } else {
        // Se agrega el encabezado de la página web antes del contenido principal.
        MAIN.insertAdjacentHTML('beforebegin', `
            <header>
            
            <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
            <div class="container">
                <a class="navbar-brand" href="index.html"><img src="../../resources/img/logo.png" height="50" alt="Booksadre"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <li class="nav-item">
        
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto text-end">
                <a class="nav-link" href="index.html"><i class="bi bi-shop"></i> Catálogo</a>
               <a class="nav-link" href="cart.html"><i class="bi bi-cart"></i> Carrito</a>
               <a class="nav-link" href="login.html"><i class="bi bi-box-arrow-right"></i> Iniciar sesión</a>
        </div>
         </div>
            </div>
        </nav>
            </header>
        `);
    }
    // Se agrega el pie de la página web después del contenido principal.
    MAIN.insertAdjacentHTML('afterend', `
<footer>
                    <nav class="navbar fixed-bottom bg-body-tertiary">
                        <div class="container">
                            <p><a class="nav-link" href="https://github.com/alonso134/Expo2024.git" target="_blank"><i class="bi bi-github"></i> CECSL</a></p>
                            <p><i class="bi bi-envelope-fill"></i> equipo de software</p>
                        </div>
                    </nav>
                </footer>
  
    `);
}