const medio = document.getElementById('medio');
const repartidor = document.getElementById('repartidor_pagos');
const input_cliente = document.getElementById('input_cliente');
const contenedor_pedido = document.getElementById('contenedor_pedido');

if (medio) {
    medio.addEventListener('change', () => {
        const selectedValue = medio.value; // Obtiene el valor seleccionado
        const mensajeSinResultados = document.getElementById('mensaje_no_resultados'); // El <p> para el mensaje

        if (contenedor_pedido) {
            let hayResultados = false;
            // Mostrar todos los elementos antes de filtrar
            contenedor_pedido.querySelectorAll('.pedidos').forEach(element => {
                element.classList.remove('hidden'); // Quita la clase 'hidden' de todos los elementos
            });

            // Aplicar el filtro basado en la selección
            contenedor_pedido.querySelectorAll('.pedidos').forEach(element => {
                const metodo = element.querySelector('.metodo');
                if (selectedValue == '') {
                    element.classList.remove('hidden');
                    hayResultados = true;
                    return '';
                }



                if (metodo && metodo.textContent.trim() !== selectedValue) {
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

const total_efectivo = document.querySelector('.total_efectivo');
const total_yape = document.querySelector('.total_yape');
const monto_inicial_yape = total_yape?.textContent.trim();
const monto_inicial_efectivo = total_efectivo?.textContent.trim();
if (repartidor) {
    repartidor.addEventListener('change', () => {
        const selectedValue = repartidor.value; // Obtiene el valor seleccionado
        const mensajeSinResultados = document.getElementById('mensaje_no_resultados'); // El <p> para el mensaje

        if (contenedor_pedido) {
            let hayResultados = false;

       
            let reset = false;
            // Mostrar todos los elementos antes de filtrar
            contenedor_pedido.querySelectorAll('.pedidos').forEach(element => {
                element.classList.remove('hidden'); // Quita la clase 'hidden' de todos los elementos
            });

            // Aplicar el filtro basado en la selección
            contenedor_pedido.querySelectorAll('.pedidos').forEach(element => {
                const repartidor = element.querySelector('.repartidor');
                const spansTotales = document.querySelectorAll('span.total');

             

                if (selectedValue == '') {
                    reset = true;
                    element.classList.remove('hidden');
                    hayResultados = true;
                    total_efectivo.textContent = monto_inicial_efectivo;
                    total_yape.textContent = monto_inicial_yape;
                    return '';
                }


                let sum_efe = 0;
                let sum_yap = 0;
                if (repartidor && repartidor.textContent.trim() !== selectedValue) {
                    element.classList.add('hidden'); // Oculta los elementos que no coincidan
                    spansTotales.forEach((span) => {
                        const pedidos_con = span.closest('.pedidos');

                        if (!pedidos_con.classList.contains('hidden')) { // Verifica si el contenedor está visible

                            const metodo = pedidos_con.querySelector('.metodo b');
                            if (metodo.textContent.trim() == 'efectivo') {
                                sum_efe += parseFloat(span.textContent.trim()) || 0; // Suma el valor de los spans;
                            } else {
                                sum_yap += parseFloat(span.textContent.trim()) || 0; // Suma el valor de los spans;
                            }
                        }
                    });

                    if (!reset) {
                        total_efectivo.textContent = "S/ " + sum_efe.toFixed(2); // Formatear el valor a 2 decimales
                        total_yape.textContent = "S/ " + sum_yap.toFixed(2); // Formatear el valor a 2 decimales

                    }
                } else {
                    hayResultados = true;
                }
            });
            // Mostrar o esconder el mensaje según los resultados
            if (hayResultados) {
                mensajeSinResultados.classList.remove('flex'); // Oculta el mensaje si hay resultados

                mensajeSinResultados.classList.add('hidden'); // Oculta el mensaje si hay resultados
                // Mostrar la suma total con formato 'S/'

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