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