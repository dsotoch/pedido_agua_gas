const mi_cuenta_input_buscar = document.getElementById('mi_cuenta_input_buscar');
const mi_cuenta_contenedor_pedidos = document.getElementById('mi_cuenta_contenedor_pedidos_super');
const mensajeSinResultados = document.getElementById('mi_cuenta_mensaje_no_resultados');

if (mi_cuenta_input_buscar) {
    mi_cuenta_input_buscar.addEventListener('keyup', () => {
        const cliente_a_filtrar = mi_cuenta_input_buscar.value.trim(); // Obtiene el valor seleccionado
        let hayResultados = false;
        if (mi_cuenta_contenedor_pedidos) {

            // Mostrar todos los elementos antes de filtrar
            mi_cuenta_contenedor_pedidos.querySelector('#mi_cuenta_contenedor_pedidos').querySelectorAll('.flex-1').forEach(element => {

                element.classList.remove('hidden'); // Quita la clase 'hidden' de todos los elementos
            });

            // Aplicar el filtro basado en la selección
            mi_cuenta_contenedor_pedidos.querySelectorAll('.flex-1').forEach(element => {
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
