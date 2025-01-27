const medio = document.getElementById('medio');
const repartidor = document.getElementById('repartidor');
const input_cliente = document.getElementById('input_cliente');
const contenedor_pedido = document.getElementById('contenedor_pedido');

if (medio) {
    medio.addEventListener('change', () => {
        const selectedValue = medio.value; // Obtiene el valor seleccionado
        const mensajeSinResultados = document.getElementById('mensaje_no_resultados'); // El <p> para el mensaje

        if (contenedor_pedido) {
            let hayResultados=false;
            // Mostrar todos los elementos antes de filtrar
            contenedor_pedido.querySelectorAll('.pedidos').forEach(element => {
                element.classList.remove('hidden'); // Quita la clase 'hidden' de todos los elementos
            });

            // Aplicar el filtro basado en la selección
            contenedor_pedido.querySelectorAll('.pedidos').forEach(element => {
                const metodo = element.querySelector('.metodo');
                if (selectedValue == '') {
                    element.classList.remove('hidden');
                    return;
                }
                if (metodo && metodo.textContent.trim() !== selectedValue) {
                    element.classList.add('hidden'); // Oculta los elementos que no coincidan
                }else{
                    hayResultados=true;
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

if (repartidor) {
    repartidor.addEventListener('change', () => {
        const selectedValue = repartidor.value; // Obtiene el valor seleccionado
        const mensajeSinResultados = document.getElementById('mensaje_no_resultados'); // El <p> para el mensaje

        if (contenedor_pedido) {
            let hayResultados = false;

            // Mostrar todos los elementos antes de filtrar
            contenedor_pedido.querySelectorAll('.pedidos').forEach(element => {
                element.classList.remove('hidden'); // Quita la clase 'hidden' de todos los elementos
            });

            // Aplicar el filtro basado en la selección
            contenedor_pedido.querySelectorAll('.pedidos').forEach(element => {
                const repartidor = element.querySelector('.repartidor');
                if (selectedValue == '') {
                    element.classList.remove('hidden');
                    return;
                }
                if (repartidor && repartidor.textContent.trim() !== selectedValue) {
                    element.classList.add('hidden'); // Oculta los elementos que no coincidan
                } else {
                    hayResultados = true;
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

if (input_cliente) {
    input_cliente.addEventListener('keyup', () => {
        const cliente_a_filtrar = input_cliente.value.trim(); // Obtén el valor del input sin espacios en blanco
        const mensajeSinResultados = document.getElementById('mensaje_no_resultados'); // El <p> para el mensaje

        if (contenedor_pedido) {
            let hayResultados = false;
            // Mostrar todos los elementos antes de filtrar
            contenedor_pedido.querySelectorAll('.pedidos').forEach(element => {
                element.classList.remove('hidden'); // Quita la clase 'hidden' de todos los elementos
            });

            // Aplicar el filtro basado en el texto ingresado
            contenedor_pedido.querySelectorAll('.pedidos').forEach(element => {
                const cliente = element.querySelector('.cliente');
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