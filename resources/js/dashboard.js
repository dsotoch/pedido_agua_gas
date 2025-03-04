import { deshabilitarClienteID, eliminarFavorito, esFavorito, esPaginaPredeterminada, guardarFavorito, guardarPaginaPredeterminada, habilitarClienteID, paginaPredeterminada } from "./cookies";
const token = document.querySelector('meta[name="token"]').getAttribute('content');
const mi_cuenta_input_buscar = document.getElementById('mi_cuenta_input_buscar');
const mi_cuenta_contenedor_pedidos = document.getElementById('mi_cuenta_contenedor_pedidos_super');
const mensajeSinResultados = document.getElementById('mi_cuenta_mensaje_no_resultados');
const id_usuario_autenticado = document.getElementById('id_usuario_autenticado');
const modal_usuario_no_autenticado = document.getElementById('modal_usuario_no_autenticado');
const select_direccion = document.getElementById('select_direccion');
const direccion = document.getElementById('direccion');
const btn_distribuidoras_cliente = document.getElementById('btn_distribuidoras_cliente');
const btn_favorito = document.querySelector('#btn_favorito_dis');
const btn_predeterminado = document.getElementById('btn_predeterminado');
const btn_menu = document.getElementById('btn_menu');
const menu = document.getElementById('menu');
const ruta_cliente_distribuidora = document.querySelector('#ruta_cliente_distribuidora');
const btn_cerrar_menu = document.querySelector('#btn_cerrar_menu');
const mensajeConexion = document.getElementById("mensajeConexion");
const btnAcceder = document.getElementById("btn_acceder");
const contenedorLogin = document.getElementById("contenedor_login");

if (btnAcceder) {
    function mostrarLogin() {
        contenedorLogin.classList.remove("hidden");
        btnAcceder.innerHTML = "X"; // Cambia el texto a "X"
        btnAcceder.classList.add("text-2xl", "font-semibold");
    }

    function ocultarLogin() {
        contenedorLogin.classList.add("hidden");
        btnAcceder.classList.remove("text-2xl", "font-semibold");
        btnAcceder.innerHTML = 'Acceder&nbsp;&nbsp;<i class="fa-solid fa-right-to-bracket"></i>'; // Restaura el botón
    }

    // Detectar si es móvil
    function esMovil() {
        return window.innerWidth <= 768; // Ajusta este valor según sea necesario
    }

    // Evento para mostrar en desktop
    btnAcceder.addEventListener("mouseenter", function () {
        if (!esMovil()) mostrarLogin();
    });

    // Evento para mostrar/ocultar en móviles
    btnAcceder.addEventListener("click", function () {
        if (contenedorLogin.classList.contains("hidden")) {
            mostrarLogin();
        } else {
            ocultarLogin();
        }
    });
}




if (contenedorLogin) {
    contenedorLogin.addEventListener("mouseleave", function () {
        contenedorLogin.classList.add("hidden");
        btnAcceder.classList.remove('text-2xl','font-semibold');

        btnAcceder.innerHTML = 'Acceder&nbsp;&nbsp;<i class="fa-solid fa-right-to-bracket"></i>'; // Restaura el botón

    });
}
function mostrarMensaje(mensaje, color) {
    mensajeConexion.textContent = mensaje;
    mensajeConexion.classList.remove("hidden");
    mensajeConexion.classList.remove("opacity-0");
    mensajeConexion.classList.add("opacity-100");
    mensajeConexion.style.backgroundColor = color;

    setTimeout(() => {
        mensajeConexion.classList.remove("opacity-100");
        mensajeConexion.classList.add("opacity-0");
        setTimeout(() => mensajeConexion.classList.add("hidden"), 500);
    }, 2000);
}

function verificarConexion() {
    if (navigator.onLine) {
        mostrarMensaje("Se restableció la conexión", "#10B981"); // Verde
    } else {
        mostrarMensaje("No tienes conexión a internet", "#DC2626"); // Rojo
    }
}

// Detectar cambios de conexión
window.addEventListener("offline", () => mostrarMensaje("No tienes conexión a internet", "#DC2626"));
window.addEventListener("online", () => mostrarMensaje("Se restableció la conexión", "#10B981"));

if (btn_menu) {
    btn_menu.addEventListener("click", () => {
        menu.classList.remove('hidden');
        menu.classList.toggle("-translate-x-full");
    });
}
// Cerrar menú si se hace clic fuera
if (btn_cerrar_menu) {
    btn_cerrar_menu.addEventListener("click", (e) => {
        if (menu) {
            menu.classList.add("-translate-x-full");

        }
    });
}
if (btn_predeterminado) {
    btn_predeterminado.addEventListener('click', async () => {
        if (id_usuario_autenticado.textContent.trim() == '') {
            Swal.fire({
                title: 'Requerimiento faltante!',
                text: "Inicia sesion para realizar esta operacion.",
                icon: 'warning',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                customClass: {
                    timerProgressBar: 'bg-red-500 h2 rounded',
                }
            });
        } else {
            let baseUrl = window.location.origin;
            let url = `${baseUrl}/${btn_predeterminado.dataset.dominio}`; // Construcción correcta de la URL
            habilitarClienteID();
            if (!await guardarPaginaPredeterminada(url, btn_predeterminado.dataset.nombre, 'distribuidora')) {
                btn_predeterminado.classList.remove('text-green-500');
            } else {
                btn_predeterminado.classList.add('text-green-500');

            }
        }
    });


}
if (btn_favorito) {
    btn_favorito.addEventListener('click', async () => {
        if (id_usuario_autenticado.textContent.trim() == '') {
            Swal.fire({
                title: 'Requerimiento faltante!',
                text: "Inicia sesion para realizar esta operacion.",
                icon: 'warning',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                customClass: {
                    timerProgressBar: 'bg-red-500 h2 rounded',
                }
            });
        } else {
            if (!await esFavorito()) {
                if (guardarFavorito(id_usuario_autenticado.textContent.trim(), btn_favorito.dataset.dominio)) {
                    btn_favorito.classList.add('text-yellow-500');

                } else {
                    btn_favorito.classList.remove('text-yellow-500');
                }
            } else {
                await eliminarFavorito(btn_favorito.dataset.dominio);
                btn_favorito.classList.remove('text-yellow-500');

            }

        }
    });


}
if (esPaginaPredeterminada()) {
    if (btn_predeterminado) {
        btn_predeterminado.classList.add('text-green-500');

    }

}

if (ruta_cliente_distribuidora) {
    ruta_cliente_distribuidora.href = paginaPredeterminada() ?? '/';
}

document.addEventListener('DOMContentLoaded', async () => {
    if (await esFavorito()) {
        if (btn_favorito) {
            btn_favorito.classList.add('text-yellow-500');
        }

    } else {
        if (btn_favorito) {
            btn_favorito.classList.remove('text-yellow-500');
        }
    }
})



if (btn_distribuidoras_cliente) {
    btn_distribuidoras_cliente.addEventListener('click', () => {
        deshabilitarClienteID();
        window.location.href = '/';
    })
}

if (select_direccion) {
    select_direccion.addEventListener('change', async () => {
        const referencia = document.getElementById('referencia');
        try {
            const response = await fetch('/obtenerReferencia', {
                method: 'POST', // Aunque sea para obtener datos, el backend puede manejarlo como GET
                headers: {
                    'Content-Type': 'application/json',
                    'X_CSRF_TOKEN':token
                },
                body: JSON.stringify({ direccion: select_direccion.value })
            });

            const data = await response.json();
            direccion.value = select_direccion.value;
            referencia.value = data.mensaje || '';
        } catch (error) {
            console.error('Error al obtener la dirección:', error);
        }
    });
}
if (mi_cuenta_input_buscar) {
    mi_cuenta_input_buscar.addEventListener('input', (e) => {

        if (!e.target.value) {
            mi_cuenta_contenedor_pedidos.querySelector('#mi_cuenta_contenedor_pedidos').querySelectorAll('.mi_cuenta_pedido').forEach(element => {

                element.classList.remove('hidden'); // Quita la clase 'hidden' de todos los elementos
            });
            return;
        }
        const cliente_a_filtrar = mi_cuenta_input_buscar.value.trim(); // Obtiene el valor seleccionado
        let hayResultados = false;
        if (mi_cuenta_contenedor_pedidos) {

            // Mostrar todos los elementos antes de filtrar
            mi_cuenta_contenedor_pedidos.querySelector('#mi_cuenta_contenedor_pedidos').querySelectorAll('.mi_cuenta_pedido').forEach(element => {

                element.classList.remove('hidden'); // Quita la clase 'hidden' de todos los elementos
            });

            // Aplicar el filtro basado en la selección
            mi_cuenta_contenedor_pedidos.querySelectorAll('.mi_cuenta_pedido').forEach(element => {
                const cliente = element.querySelector('.mi_cuenta_cliente');

                if (cliente) {
                    const clienteTexto = cliente.textContent.trim().toLowerCase();
                    if (!clienteTexto.includes(cliente_a_filtrar.toLowerCase())) {
                        element.classList.add('hidden'); // Oculta los elementos que no coincidan
                    } else {
                        hayResultados = true; // Marca que hay al menos un resultado visible
                    }
                }
            });
            if (hayResultados) {
                mensajeSinResultados.classList.remove('flex'); // Oculta el mensaje si hay resultados

                mensajeSinResultados.classList.add('hidden'); // Oculta el mensaje si hay resultados
            } else {
                mensajeSinResultados.classList.add('flex'); // Oculta el mensaje si hay resultados

                mensajeSinResultados.classList.remove('hidden'); // Muestra el mensaje si no hay resultados
            }

            return;

        }


    });
}
if (window.location.pathname.trim() != '/') {
    verificar_login();
}

function verificar_login() {
    if (!id_usuario_autenticado.textContent.trim()) {
        modal_usuario_no_autenticado.classList.remove('hidden');
        modal_usuario_no_autenticado.classList.add('flex');
    }
}



