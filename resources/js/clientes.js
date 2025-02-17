const cliente_input_buscar = document.getElementById('cliente_input_buscar');
const cliente_contenedor_cliente = document.getElementById('cliente_contenedor_cliente');
if (cliente_input_buscar) {
    cliente_input_buscar.addEventListener('keyup', () => {
        const cliente_a_filtrar = cliente_input_buscar.value.trim(); // Obtiene el valor seleccionado
        const mensajeSinResultados = document.getElementById('cliente_mensaje_no_resultados'); // El <p> para el mensaje
        if (cliente_contenedor_cliente) {
            let hayResultados = false;
            // Mostrar todos los elementos antes de filtrar
            cliente_contenedor_cliente.querySelectorAll('.cliente_clientes').forEach(element => {
                element.classList.remove('hidden'); // Quita la clase 'hidden' de todos los elementos
            });

            // Aplicar el filtro basado en la selección
            cliente_contenedor_cliente.querySelectorAll('.cliente_clientes').forEach(element => {
                const cliente = element.querySelector('.cliente_nombres');
                if (cliente) {
                    const clienteTexto = cliente.textContent.trim().toLowerCase();
                    if (!clienteTexto.includes(cliente_a_filtrar.toLowerCase())) {
                        element.classList.add('hidden'); // Oculta los elementos que no coincidan
                    } else {
                        hayResultados = true; // Marca que hay al menos un resultado visible
                    }
                }
            });
            // Mostrar o esconder el mensaje según los resultados
            if (hayResultados) {
                mensajeSinResultados.classList.remove('flex'); // Oculta el mensaje si hay resultados

                mensajeSinResultados.classList.add('hidden'); // Oculta el mensaje si hay resultados
            } else {
                mensajeSinResultados.classList.add('flex'); // Oculta el mensaje si hay resultados

                mensajeSinResultados.classList.remove('hidden'); // Muestra el mensaje si no hay resultados
            }
        }

    });
}

document.addEventListener("DOMContentLoaded", function () {
    const contenedorDirecciones = document.getElementById("direcciones-container");
    const btnAgregarDireccion = document.getElementById("agregarDireccion");

    // Función para agregar una nueva dirección
    function agregarDireccion(valor = "", referencia = "") {
        const index = document.querySelectorAll(".direccion-item").length; // Índice único

        const div = document.createElement("div");
        div.classList.add("direccion-item");
        div.innerHTML = `
            <input type="text" class="p-2 border-2 w-1/3" name="direcciones[${index}][direccion]" value="${valor}" placeholder="Dirección" required>
            <input type="text" class="p-2 border-2 md:w-1/3 w-1/2" name="direcciones[${index}][referencia]" value="${referencia}" placeholder="Referencia" required>
            <button type="button" class="editarDireccion">✏️</button>
            <button type="button" class="eliminarDireccion">🗑️</button>
        `;

        contenedorDirecciones.appendChild(div);
    }

    if (btnAgregarDireccion) {
        // Agregar dirección al hacer clic en el botón
        btnAgregarDireccion.addEventListener("click", function () {
            agregarDireccion();
        });
    }

    if (contenedorDirecciones) {
        // Delegación de eventos para eliminar o editar
        contenedorDirecciones.addEventListener("click", function (e) {
            if (e.target.classList.contains("eliminarDireccion")) {
                e.target.parentElement.remove();
            } else if (e.target.classList.contains("editarDireccion")) {
                let inputDireccion = e.target.parentElement.querySelector("input[name*='direccion']");
                inputDireccion.focus();
            }
        });
    }
});
