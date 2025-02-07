import { eliminarFavorito, obtenerFavoritos } from "./cookies";

if (window.location.pathname === '/mi-cuenta/favoritas') {
    let contenedor = document.getElementById('distribuidorasFavoritas');
    if (contenedor) {
        let favoritos = obtenerFavoritos();
        contenedor.innerHTML = ''; // Limpiar contenido previo

        if (favoritos.length === 0) {
            contenedor.innerHTML = '<p class="text-gray-500 text-center">No tienes distribuidoras favoritas.</p>';
        } else {
            favoritos.forEach(fav => {
                let item = document.createElement('div');
                item.className = 'flex items-center gap-3 p-4 border rounded-lg bg-white shadow-sm hover:shadow-md transition';

                item.innerHTML = `
                    <img src="/storage/${fav.logo}" alt="${fav.nombre}" class="w-10 h-10 rounded-full object-cover">
                    <a href="${fav.url}" class="text-color-titulos-entrega font-medium hover:underline flex-1">${fav.nombre}</a>
                    <button data-id="${fav.url}" class=" btn_eliminar_favorito_panel text-red-500 hover:text-red-700 text-xl">
                        ✖ 
                    </button>
                `;


                contenedor.appendChild(item);
            });
        }
    }
    const btnsEliminarFavorito = document.querySelectorAll('.btn_eliminar_favorito_panel');

    if (btnsEliminarFavorito.length > 0) {
        btnsEliminarFavorito.forEach(btn => {
            btn.addEventListener('click', () => {
                eliminarYActualizar(btn.dataset.id);
            });
        });
    }

}

// Función para eliminar y actualizar la lista
function eliminarYActualizar(url) {
    eliminarFavorito(url);
    setTimeout(() => {
        location.reload(); // Recargar para actualizar la lista
    }, 2000);
}
