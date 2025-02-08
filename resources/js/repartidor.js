import { event } from "jquery";
import Swal from "sweetalert2";

const btndashboardrepartidor = document.getElementById('btndashboardrepartidor');
const panelrepartidor = document.getElementById('panelrepartidor');
const modalUsuario = document.getElementById('modalUsuario');
const botonesclientepanelrepartidor = document.getElementById('botonesclientepanelrepartidor');
const btnregresarrepartidor = document.getElementById('btnregresarrepartidor');
const imgmoto = document.getElementById("imgmoto");
const imgmotomini = document.getElementById('imgmotomini');
const pedidosrepartidor = document.querySelectorAll('.pedidosrepartidor');
const token = document.querySelector('meta[name="token"]').getAttribute('content');
const paymentModal = document.getElementById('paymentModal');
const form_metodo_pago_repartidor = document.getElementById('form_metodo_pago_repartidor');
const mi_cuenta_contenedor_pedidos = document.getElementById('mi_cuenta_contenedor_pedidos_super');
const modal_anular_pedido = document.getElementById('modal_anular_pedido');
const modal_anular_pedido_id = document.getElementById('modal_anular_pedido_id');
const modal_pago_pedido_id = document.getElementById('modal_pago_pedido_id');
const form_anular_pedido_repartidor = document.getElementById('form_anular_pedido_repartidor');
function cerrarmodalPedidoAsignado() {
    modalmensajespedidoasignado.classList.remove('flex');
    modalmensajespedidoasignado.classList.add('hidden');
}
if (mi_cuenta_contenedor_pedidos) {
    mi_cuenta_contenedor_pedidos.addEventListener('click', async (event) => {
        let spanestadopedido;
        let spanpagado;
    
        // Obtener el botón más cercano al clic (ya sea el botón o el icono dentro de él)
        const disparador = event.target.closest('button');
        
        if (!disparador) return; // Si no hay un botón, salir
    
        const id = disparador.dataset.id; // Obtener el ID del pedido
    
        if (!id) {
            console.error("Error: No se encontró un ID en el dataset del botón.");
            return;
        }
    
        // Verificar si el botón pertenece a una de las clases esperadas
        if (disparador.classList.contains('boton_repartidor_aceptar_pedido')) {
            // Buscar el span del estado del pedido
            const estadoPedidoSpan = disparador.closest('.mi_cuenta_pedido')?.querySelector('.estado_pedido_span');
    
            // Desactivar el botón para evitar múltiples solicitudes
            disparador.disabled = true;
    
            try {
                const response = await fetch(`/cambiarestadopago/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                });
    
                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(errorText || 'Error desconocido');
                }
    
                const result = await response.json();
    
                // Mostrar la alerta de éxito con SweetAlert2
                Swal.fire({
                    title: '¡Confirmación!',
                    text: result.mensaje || 'Operación completada correctamente.',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    customClass: {
                        timerProgressBar: 'bg-green-500',
                    },
                });
    
                // Actualizar el estado del pedido
                if (estadoPedidoSpan) {
                    estadoPedidoSpan.textContent = result.estado === 'En Camino'
                        ? result.estado + " 🚚"
                        : result.estado || 'Estado desconocido';
                }
    
                // Ocultar el botón después de completar la acción
                disparador.classList.add('hidden');
    
                cerrarmodalPedidoAsignado();
    
            } catch (error) {
                mensajeError(error.message || 'Ha ocurrido un error inesperado.');
            } finally {
                disparador.disabled = false;
            }
    
            return;
        }
    
        if (disparador.classList.contains('btnconfirmarentrega')) {
            spanestadopedido = disparador.closest('.mi_cuenta_pedido')?.querySelector('.estado_pedido_span');
            spanpagado = disparador.closest('.mi_cuenta_pedido')?.querySelector('.estado_metodo_pago');
            const id_pedido_modal_pago = document.getElementById('id_pedido_modal_pago');
    
            disparador.disabled = true;
            paymentModal.classList.remove('hidden');
            paymentModal.classList.add('flex');
            modal_pago_pedido_id.textContent = "#" + id;
            id_pedido_modal_pago.value = id;
    
            return;
        }
    
        if (disparador.classList.contains('btnanularpedido')) {
            const id_pedido_modal_anular = document.getElementById('id_pedido_modal_anular');
            disparador.disabled = true;
            modal_anular_pedido.classList.remove('hidden');
            modal_anular_pedido.classList.add('flex');
            modal_anular_pedido_id.textContent = "#" + id;
            id_pedido_modal_anular.value = id;
    
            return;
        }
    });
    
}

if (form_metodo_pago_repartidor) {
    form_metodo_pago_repartidor.addEventListener('submit', (e) => {
        e.preventDefault();
        const datosform = new FormData(form_metodo_pago_repartidor);
        let data = {};
        datosform.forEach((value, key) => {
            data[key] = value;
        });
        fetch(form_metodo_pago_repartidor.action, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json', // Define el contenido como JSON
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify(data)
        }).then(response => {
            // Verificar el código de estado
            if (response.status !== 200) {
                // Obtener el mensaje de error del servidor
                return response.text().then((text) => {
                    throw new Error(text); // Lanza un error con el mensaje del servidor
                });
            }
            return response.json(); // Convertir la respuesta exitosa a JSON
        })
            .then(result => {

                Swal.fire(
                    {
                        title: 'Confirmación!',
                        text: result.mensaje,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        customClass: {
                            timerProgressBar: 'bg-green-500'
                        }
                    }
                )
                paymentModal.classList.remove('flex');
                paymentModal.classList.add('hidden');
                ocultar_tarjeta_pedido_entregado(result.pedido_id);


            })
            .catch(error => {
                mensajeError(error.message); // Mostrar mensaje de error

            });


    });
}

if (form_anular_pedido_repartidor) {
    form_anular_pedido_repartidor.addEventListener('submit', (e) => {
        e.preventDefault();
        const datosform = new FormData(form_anular_pedido_repartidor);
        let data = {};
        datosform.forEach((value, key) => {
            data[key] = value;
        });
        fetch(form_anular_pedido_repartidor.action, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json', // Define el contenido como JSON
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify(data)
        }).then(response => {
            // Verificar el código de estado
            if (response.status !== 200) {
                // Obtener el mensaje de error del servidor
                return response.text().then((text) => {
                    throw new Error(text); // Lanza un error con el mensaje del servidor
                });
            }
            return response.json(); // Convertir la respuesta exitosa a JSON
        })
            .then(result => {

                Swal.fire(
                    {
                        title: 'Confirmación!',
                        text: result.mensaje,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        customClass: {
                            timerProgressBar: 'bg-green-500'
                        }
                    }
                )
                modal_anular_pedido.classList.remove('flex');
                modal_anular_pedido.classList.add('hidden');
                ocultar_tarjeta_pedido_entregado(result.pedido_id);


            })
            .catch(error => {
                mensajeError(error.message); // Mostrar mensaje de error

            });


    });
}
const cantidad_pedidos = document.querySelector('.cantidad_pedidos');
function ocultar_tarjeta_pedido_entregado($pedido_id) {
    const pedido_caja = mi_cuenta_contenedor_pedidos.querySelector("#caja-" + $pedido_id);
    pedido_caja.remove();
    cantidad_pedidos.textContent = parseInt(cantidad_pedidos.textContent) - 1;
}

if (pedidosrepartidor) {
    pedidosrepartidor.forEach(element => {
        const btnpagorepartidor = element.querySelector('.btnpagorepartidor');

        if (btnpagorepartidor) {
            btnpagorepartidor.addEventListener('click', () => {
                paymentModal.classList.remove('hidden');
                paymentModal.classList.add('flex');
                pedido = btnpagorepartidor.dataset.id;
                modalUsuario.scrollTo({
                    top: 0,
                    behavior: 'smooth' // Opcional: desplazamiento suave
                });
                const divprincipal = btnpagorepartidor.closest('.pedidosrepartidor');
                const spanestadorepartidor = divprincipal.querySelector('.spanestadorepartidor');
                spanpagado = divprincipal.querySelector('.spanpagado');
                spanestadopedido = spanestadorepartidor;
                metodo = divprincipal.querySelector('.metodopedido');
            });

        }
        const formulario_repartidor = element.querySelector('.formaceptarrepartidor');
        if (formulario_repartidor) {
            const boton_aceptar_pedido_repartidor = formulario_repartidor.querySelector('.btnaceptarrepartidor');

            const spanestadorepartidor = element.querySelector('.spanestadorepartidor');

            boton_aceptar_pedido_repartidor.addEventListener('click', () => {
                boton_aceptar_pedido_repartidor.disabled = true;
                fetch(formulario_repartidor.action, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json', // Define el contenido como JSON
                        'X-CSRF-TOKEN': token,
                    },
                }).then(response => {
                    // Verificar el código de estado
                    if (response.status !== 200) {
                        // Obtener el mensaje de error del servidor
                        return response.text().then((text) => {
                            throw new Error(text); // Lanza un error con el mensaje del servidor
                        });
                    }
                    return response.json(); // Convertir la respuesta exitosa a JSON
                })
                    .then(result => {
                        spanestadorepartidor.classList.remove('text-red-600');
                        spanestadorepartidor.classList.add('text-green-600');
                        spanestadorepartidor.innerHTML = 'En camino 🚚';
                        mensajeExito(result.mensaje); // Manejo del resultado exitoso
                        boton_aceptar_pedido_repartidor.classList.add('hidden');
                    })
                    .catch(error => {
                        mensajeError(error.message); // Mostrar mensaje de error
                        boton_aceptar_pedido_repartidor.disabled = false;

                    });
            });

        }
    });
}
if (btndashboardrepartidor) {
    btndashboardrepartidor.addEventListener('click', () => {
        botonesclientepanelrepartidor.classList.remove('flex');
        botonesclientepanelrepartidor.classList.add('hidden');
        panelrepartidor.classList.remove('hidden');
        panelrepartidor.classList.add('grid');

        modalUsuario.classList.remove('md:w-1/2');
        imgmoto.classList.add('hidden');
        imgmotomini.classList.remove('hidden');
    });
}
if (btnregresarrepartidor) {
    btnregresarrepartidor.addEventListener('click', () => {
        botonesclientepanelrepartidor.classList.remove('hidden');
        botonesclientepanelrepartidor.classList.add('flex');
        modalUsuario.classList.add('md:w-1/2');
        panelrepartidor.classList.remove('grid');
        panelrepartidor.classList.add('hidden');
        imgmoto.classList.remove('hidden');
        imgmotomini.classList.add('hidden');


    });
}
//Mensaje de Error
function mensajeError(texto) {
    Swal.fire({
        title: 'Ocurrio un Error!',
        text: texto,
        icon: 'error',
        showConfirmButton: false,
        timerProgressBar: true,
        timer: 2000,
        customClass: {
            timerProgressBar: 'bg-red-500 h-2 rounded-md'
        }
    })
}
//Mensaje de Exito
function mensajeExito(texto) {
    Swal.fire({
        title: 'Confirmación!',
        text: texto,
        icon: 'success',
        showConfirmButton: false,
        timerProgressBar: true,
        timer: 2000,
        customClass: {
            timerProgressBar: 'bg-green-500 h-2 rounded-md'
        }
    })
}