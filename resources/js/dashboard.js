import { agregarFavorito, deshabilitarClienteID, esFavoritoDistribuidora, esPaginaPredeterminada, esPaginaPredeterminada_Principal, guardarPaginaPredeterminada, habilitarClienteID, paginaPredeterminada } from "./cookies";

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

if (btn_menu) {
    btn_menu.addEventListener("click", () => {
        menu.classList.remove('hidden');
        menu.classList.toggle("-translate-x-full");
    });
}
// Cerrar menú si se hace clic fuera
document.addEventListener("click", (e) => {
    if (menu) {
        if (!menu.contains(e.target) && !btn_menu.contains(e.target)) {
            menu.classList.add("-translate-x-full");
        }
    }
});
if (btn_predeterminado) {
    btn_predeterminado.addEventListener('click', () => {
        let baseUrl = window.location.origin;
        let url = `${baseUrl}/${btn_predeterminado.dataset.dominio}`; // Construcción correcta de la URL
        habilitarClienteID();
        if (!guardarPaginaPredeterminada(url, btn_predeterminado.dataset.nombre, 'distribuidora')) {
            btn_predeterminado.classList.remove('text-green-500');
        } else {
            btn_predeterminado.classList.add('text-green-500');

        }
    });


}
if (btn_favorito) {
    btn_favorito.addEventListener('click', () => {
        let baseUrl = window.location.origin;
        let url = `${baseUrl}/${btn_favorito.dataset.dominio}`; // Construcción correcta de la URL
        if (agregarFavorito(url, btn_favorito.dataset.nombre ,btn_favorito.dataset.logo)) {
            btn_favorito.classList.add('text-yellow-500');

        } else {
            btn_favorito.classList.remove('text-yellow-500');
        }

    });


}
if (esPaginaPredeterminada()) {
    if (btn_predeterminado) {
        btn_predeterminado.classList.add('text-green-500');

    }

}

if (ruta_cliente_distribuidora) {
    ruta_cliente_distribuidora.href = paginaPredeterminada()?? '/';
}

document.addEventListener('DOMContentLoaded', () => {
    if (esFavoritoDistribuidora()) {
        if (btn_favorito) {
            btn_favorito.classList.add('text-yellow-500');
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
    select_direccion.addEventListener('change', () => {
        direccion.value = select_direccion.value;
    });
}
if (mi_cuenta_input_buscar) {
    mi_cuenta_input_buscar.addEventListener('keyup', () => {
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



