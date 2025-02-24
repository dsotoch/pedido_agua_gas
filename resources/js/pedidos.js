
import Swal from "sweetalert2"
import confetti from "canvas-confetti";
import { agregarPedido } from "./nueva_tarjeta";
import { mostrarNotificacion } from "./notificaciones";
const token = document.querySelector('meta[name="token"]').getAttribute('content');
const btn_siguiente_pedido = document.querySelector('.btnproductoagregar');
const contenedor_form_realizar_pedido = document.getElementById('contenedor_form_realizar_pedido');
const form_realizar_pedido = document.getElementById('form_realizar_pedido');
const input_celular = document.getElementById('celular');
const btn_regresar_a_productos = document.getElementById('btn_regresar_a_productos');
const contenedor_confeti = document.getElementById('contenedor_confeti');
// Seleccionamos los elementos
const copyButton = document.getElementById('copy-button');
const numberToCopy = document.getElementById('number-to-copy');
const formAsignarRepartidor = document.getElementById('formAsignarRepartidor');
const modal_editar_pedido = document.getElementById('modal_editar_pedido');
const btn_venta_rapida = document.querySelector(".btn_venta_rapida");
const paymentModalVentaRapida = document.getElementById('paymentModalVentaRapida');
const form_metodo_pago_venta_rapida = document.getElementById('form_metodo_pago_venta_rapida');
if (btn_venta_rapida) {
    btn_venta_rapida.addEventListener('click', () => {
        paymentModalVentaRapida.classList.remove('hidden');
    });
}

if (form_metodo_pago_venta_rapida) {
    form_metodo_pago_venta_rapida.addEventListener('submit', async (e) => {
        e.preventDefault();
        paymentModalVentaRapida.classList.add('opacity-50');

        const data = new FormData(form_metodo_pago_venta_rapida);
        let datos = {};
        data.forEach((value, key) => {
            datos[key] = value;
        });
        datos['productos'] = window.idproductos || [];
        try {
            // Realizar la solicitud Fetch
            const response = await fetch(form_metodo_pago_venta_rapida.getAttribute('action'), {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                },
                body: JSON.stringify(datos), // Convertir datos a JSON
            });

            // Manejar la respuesta
            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(errorText);
            }

            const respuesta = await response.json();
            window.idproductos = [];
            paymentModalVentaRapida.classList.remove('opacity-50');

            paymentModalVentaRapida.classList.add('hidden');
            let promocion = '';
            if (respuesta.promocion) {
                promocion = '|| Producto Gratis 1'+" "+ respuesta.promocion;
            }

            Swal.fire({
                title: "Pedido Finalizado!",
                text: respuesta.mensaje + " " + promocion,
                icon: "success",
                timerProgressBar: true,
                timer: 4000,
                showConfirmButton: false,
                customClass: {
                    timerProgressBar: "bg-green-500 h-2 rounded-lg"
                }
            });
        } catch (error) {
            console.log(error);
            // Mostrar mensaje de error (usando SweetAlert como ejemplo)
            Swal.fire({
                title: "Ocurrió un error!",
                text: 'Hubo un error al Procesar el pedido. Recarga la pagina y vuelve a intentarlo.',
                icon: "error",
                timerProgressBar: true,
                timer: 3000,
                showConfirmButton: false,
                customClass: {
                    timerProgressBar: "bg-red-500 h-2 rounded-lg"
                }
            });

            paymentModalVentaRapida.classList.remove('opacity-50');

        }
    })
}
// Añadimos el evento al botón
if (copyButton) {
    copyButton.addEventListener('click', (event) => {
        event.preventDefault(); // Evita que el enlace recargue la página

        // Copiar el valor al portapapeles
        numberToCopy.select(); // Selecciona el texto
        numberToCopy.setSelectionRange(0, 99999); // Para dispositivos móviles
        navigator.clipboard.writeText(numberToCopy.value)
            .then(() => {
            })
            .catch(err => {
                console.error('Error al copiar: ', err);
            });
    });
}
if (contenedor_confeti) {
    confetti({
        particleCount: 150,
        angle: 90,
        spread: 70,
        origin: { x: 0.5, y: 0.5 },
        colors: ['#FFFF00', '#FF0000', '#0000FF', '#FFFFFF']
    });
}
if (btn_regresar_a_productos) {
    btn_regresar_a_productos.addEventListener('click', () => {
        ocultarContenedorFormRealizarPedido();
        contener_producto_item.classList.remove('hidden');
        contener_producto_item.classList.add('flex');
    });
}

async function buscar_datos_cliente(valor) {
    let usuario_id = form_realizar_pedido.querySelector("#usuario_id");
    let nombres = form_realizar_pedido.querySelector("#nombres");
    let direccion = form_realizar_pedido.querySelector("#direccion");
    let referencia = form_realizar_pedido.querySelector("#referencia");
    try {
        const response = await fetch(`/buscar-usuario/${valor}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (response.ok) {
            const data = await response.json(); // Parsear la respuesta JSON
            // Asumimos que data tiene un campo 'mensaje'
            usuario_id.value = data.mensaje.id;
            nombres.value = data.mensaje.persona.nombres;
            direccion.value = data.mensaje.persona.direccion;
            referencia.value = data.mensaje.persona.nota;
            return true;


        } else {
            const errorData = await response.json();
            Swal.fire({
                title: 'Error!',
                text: errorData.mensaje || 'Error desconocido',
                icon: 'error',
                showConfirmButton: false, // Oculta el botón de confirmación
                timer: 3000, // Duración de la alerta en milisegundos (3 segundos)
                timerProgressBar: true,// Muestra una barra de progreso del tiempo
                customClass: {
                    timerProgressBar: 'custom-bg-button' // Clase CSS personalizada
                }
            });
            return false;


        }
    } catch (error) {
        Swal.fire({
            title: 'Error!',
            text: 'Hubo un problema con la solicitud: ' + error.message,
            icon: 'error',
            showConfirmButton: false, // Oculta el botón de confirmación
            timer: 3000, // Duración de la alerta en milisegundos (3 segundos)
            timerProgressBar: true, // Muestra una barra de progreso del tiempo
            customClass: {
                timerProgressBar: 'custom-bg-button' // Clase CSS personalizada
            }
        });
        return false;



    }
}

if (input_celular) {
    input_celular.addEventListener('keyup', async (e) => {
        const valor = e.target.value.trim(); // Limpiar espacios en blanco
        if (valor.length === 9) { // Validar la longitud del valor del input
            input_celular.disabled = true;
            const encontrado = await buscar_datos_cliente(valor);

            input_celular.disabled = false; // Habilitar después de la búsqueda
            input_celular.focus();

            if (!encontrado) {
                form_realizar_pedido.reset(); // Borrar formulario solo si no se encontró el usuario
            }
        }
    });
}



// Agregar eventos a todos los botones de di[16px]inución
document.querySelectorAll('.btn-producto-menos').forEach((boton) => {
    boton.addEventListener('click', () => {
        calcularTotalEnProducto(boton, 'resta');


    });
});

// Agregar eventos a todos los botones de aumento
document.querySelectorAll('.btn-producto-mas').forEach((boton) => {
    boton.addEventListener('click', () => {
        calcularTotalEnProducto(boton, 'suma');

    });
});

// Mantén un objeto para rastrear las cantidades anteriores por producto
if (!window.cantidadesAnteriores) {
    window.cantidadesAnteriores = {};
}
if (!window.idproductos) {
    window.idproductos = [];
}


function calcularTotalEnProducto(boton, operacion) {
    const contenedor = boton.closest('.item-container');
    const cantidadInput = contenedor.querySelector('.cantidad');
    const precioElement = contenedor.parentElement.querySelector('.precioprincipal');
    const padre_productos = boton.closest('.padre_productos');
    const valvulas = padre_productos?.querySelector('.valvulas');
    let checkboxSeleccionado = null;

    if (valvulas) {
        checkboxSeleccionado = valvulas.querySelector('input[type="radio"]:checked');
    }

    // Obtener el precio base normal
    const precioNormal = parseFloat(contenedor.querySelector('.precionormal').textContent.replace('S/', '').trim());
    let cantidad = parseInt(cantidadInput.textContent) || 0;

    // Determinar la cantidad anterior del producto
    const productoId = contenedor.getAttribute('data-producto-id'); // Identificador único por producto
    const cantidadAnterior = window.cantidadesAnteriores[productoId] || 0;

    // Obtener las promociones del producto
    const promociones = contenedor.querySelector(".promociones").textContent;
    const objeto = JSON.parse(promociones);

    // Ordenar promociones por cantidad ascendente
    objeto.sort((a, b) => a.cantidad - b.cantidad);

    // Determinar el precio aplicado basado en la cantidad anterior
    let precioAnteriorAplicado = precioNormal;
    objeto.forEach((element) => {
        if (cantidadAnterior >= element.cantidad) {
            precioAnteriorAplicado = parseFloat(element.precio_promocional);
        }
    });

    // Actualizar la cantidad basada en la operación
    if (operacion === 'suma') {
        cantidad++;
    } else if (operacion === 'resta') {
        cantidad = Math.max(0, cantidad - 1);
        if (cantidad == 0) {
            window.idproductos = [];
            logica_boton_siguiente();
            cantidadInput.textContent = cantidad;

            return '';
        }
    }

    // Actualiza el input de cantidad
    cantidadInput.textContent = cantidad;

    // Determinar el precio aplicado basado en la cantidad actual
    let precioActualAplicado = precioNormal;
    objeto.forEach((element) => {
        if (cantidad >= element.cantidad) {
            precioActualAplicado = parseFloat(element.precio_promocional);
        }
    });

    // Actualizar el precio mostrado
    precioElement.textContent = 'S/' + precioActualAplicado.toFixed(2);

    // Calcular la diferencia correctamente
    const diferencia = (cantidad * precioActualAplicado) - (cantidadAnterior * precioAnteriorAplicado);

    // Actualizar el total acumulado
    if (typeof window.totalPedidoAcumulado === 'undefined') {
        window.totalPedidoAcumulado = 0.00;
    }
    window.totalPedidoAcumulado += diferencia;

    // Almacena la nueva cantidad
    window.cantidadesAnteriores[productoId] = cantidad;

    // Actualiza el total en el contenedor correspondiente
    const contenedorTotal = document.querySelector('.total');
    contenedorTotal.value = `Total: S/${window.totalPedidoAcumulado.toFixed(2)}`;

    // Buscar el índice del producto en el array
    // Buscar si el producto ya existe con el mismo id y tipo
    const index = window.idproductos.findIndex(item => item.id === productoId && item.tipo === (checkboxSeleccionado ? checkboxSeleccionado.value : ''));

    // Obtener el checkbox seleccionado
    const tipoSeleccionado = checkboxSeleccionado ? checkboxSeleccionado.value : '';

    // Si el producto con el mismo tipo no existe, agregarlo como un nuevo registro
    if (index === -1) {
        window.idproductos.push({
            id: productoId,
            cantidad: 1,
            tipo: tipoSeleccionado
        });
    } else {
        // Si el producto ya existe con el mismo tipo, actualizar solo la cantidad
        window.idproductos[index].cantidad = cantidad;
    }



    // Filtrar productos con cantidad cero
    window.idproductos = window.idproductos.filter(item => item.cantidad > 0);
    logica_boton_siguiente();
}


function ocultarContenedorProductosItem() {
    const contener_producto_item = document.getElementById('contener_producto_item');
    contener_producto_item.classList.remove('flex');
    contener_producto_item.classList.add('hidden');
}
function ocultarContenedorFormRealizarPedido() {
    contenedor_form_realizar_pedido.classList.add('hidden');

}


//Continuar con el pedido
if (btn_siguiente_pedido) {
    btn_siguiente_pedido.addEventListener('click', () => {
        document.querySelector("#contenedor_modales_usuario_no_auth").classList.remove('hidden');
        ocultarContenedorProductosItem();
        contenedor_form_realizar_pedido.classList.remove('hidden');
        contenedor_form_realizar_pedido.classList.add('grid');

    });
}

//Fomrmulario realizar pedido distribuidora
const btn_atras_distribuidora = document.getElementById('btn_atras_distribuidora');
const contener_producto_item = document.getElementById('contener_producto_item');
if (btn_atras_distribuidora) {
    btn_atras_distribuidora.addEventListener('click', () => {
        contenedor_form_realizar_pedido.classList.add('hidden');
        contener_producto_item.classList.remove('hidden');
        contener_producto_item.classList.add('flex');

    });
}

function logica_boton_siguiente() {
    if (btn_siguiente_pedido) {
        if (window.idproductos.length > 0) {
            btn_siguiente_pedido.removeAttribute('disabled');
            if (btn_venta_rapida) {
                btn_venta_rapida.removeAttribute('disabled');

            }
        } else {
            btn_siguiente_pedido.setAttribute('disabled', true);
            if (btn_venta_rapida) {
                btn_venta_rapida.setAttribute('disabled', true);

            }
        }
    }
}

if (form_realizar_pedido) {
    form_realizar_pedido.addEventListener('submit', async (event) => {
        event.preventDefault();
        form_realizar_pedido.classList.add('opacity-50')
        const span_cupon = document.getElementById('span_cupon');

        const formdata = new FormData(form_realizar_pedido);
        let data = {};

        // Convertir FormData a un objeto
        formdata.forEach((value, key) => {
            data[key] = value;
        });

        // Agregar los productos al objeto de datos
        data['productos'] = window.idproductos || [];
        data['cupon'] = span_cupon.value.replace('#', '');
        try {
            // Realizar la solicitud Fetch
            const response = await fetch(form_realizar_pedido.getAttribute('action'), {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                },
                body: JSON.stringify(data), // Convertir datos a JSON
            });

            // Manejar la respuesta
            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(errorText);
            }

            const respuesta = await response.json();
            window.idproductos = [];
            window.location.href = respuesta.ruta;

        } catch (error) {
            console.log(error);
            // Mostrar mensaje de error (usando SweetAlert como ejemplo)
            Swal.fire({
                title: "Ocurrió un error!",
                text: error.message.mensaje,
                icon: "error",
                timerProgressBar: true,
                timer: 3000,
                showConfirmButton: false,
                customClass: {
                    timerProgressBar: "bg-red-500 h-2 rounded-lg"
                }
            });

            form_realizar_pedido.classList.remove('opacity-50')

        }
    });
}






//Mensaje de Error
function mensajeError(texto) {
    Swal.fire({
        title: 'Ocurrio un Error!',
        text: texto,
        icon: 'error',
        confirmButtonText: 'Aceptar'
    })
}


//WEBSOCKET

const user = document.getElementById('id_usuario_autenticado');
// Canal WebSocket y constantes globales
let webSocketChannel = "";
if (user) {
    webSocketChannel = `App.Models.User.${user.textContent}`;
}

function conectarWebSocket() {
    window.Echo.private(webSocketChannel)
        .listen('MensajeEntendido', async (e) => {
            // Verificar que e.message no sea undefined
            if (!e.message) {
                console.error("⚠️ Error: Mensaje recibido sin datos.");
                return;
            }

            switch (e.message.operacion) {
                case 'pedido_tomado':
                    actualizar_Estado_delivery_panel_cliente(e.message.pedido_id, e.message.estado);
                    break;
                case 'confirmacion':
                    mostrarNotificacion("Tu Pedido está en Camino",
                        `¡Hola Estimado Usuario(a)! El repartidor ha tomado tu pedido #${e.message.pedido_id}, y está en camino para entregártelo. ¡Prepárate para recibirlo pronto!`,
                        'Nuevo-Pedido-Camino'
                    );
                    actualizar_Estado_delivery_panel_cliente(e.message.pedido_id, e.message.estado);
                    break;
                case 'asignacion':
                    mostrarNotificacion("Nuevo Pedido Asignado",
                        `¡Repartidor! La Distribuidora te ha asignado el Pedido #${e.message.pedido_id}, revísalo en este momento`,
                        'Nuevo-Pedido-Asignado'
                    );
                    pedidoasignadoarepartidor(e.message.pedido_id, 'repartidor');
                    agregarPedido(e.message.pedido, "repartidor", e.message.tiempo);
                    break;
                case 'finalizado':
                case 'anulacion':
                    actualizarEstadoYPagoPanelAdministrador(e.message.pedido_id, e.message.estado);
                    break;
                default:
                    mostrarNotificacion("Nuevo Pedido",
                        `¡Administrador! La Distribuidora tiene un nuevo Pedido #${e.message.pedido_id}, revísalo en este momento`,
                        'Nuevo-Pedido'
                    );
                    agregarPedido(e.message.pedido, "admin", e.message.tiempo);
                    break;
            }
        })
        .error((error) => {
            console.error("🚨 Error en WebSocket:", error);
        });

    console.log("✅ WebSocket conectado y escuchando eventos.");
}

try {
    if (window.location.pathname !== '/') {  // Mejor usar pathname en lugar de href        
        conectarWebSocket();

        getRepartidores();
    }
} catch (error) {
    console.error("Error en la inicialización:", error);
}

// Función para obtener los mensajes
function getMessages() {
    try {
        fetch(`/mensajes`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token,
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
        }).then(result => {
            setMessages(result); // Actualiza la interfaz con los mensajes    
        }).catch(error => {
            // mensajeError(error.mensaje); // Mostrar mensaje de error
            console.log(error);
        });


    } catch (err) {
        console.error("Error al obtener los mensajes:", err.message);
    }
}

// Función para actualizar el DOM con los mensajes
function setMessages(data) {
    const messagesContainer = document.getElementById('messagesContainer');
    messagesContainer.innerHTML = ''; // Limpiar el contenedor antes de agregar los nuevos mensajes

    // Agregar los mensajes al contenedor
    data.forEach(message => {
        const messageElement = document.createElement('div');
        messageElement.classList.add('mensajes_socket', 'bg-white', 'text-base', 'p-4', 'rounded-lg', 'shadow-md', 'w-full', 'flex', 'flex-col', 'mb-4');

        // Crear el contenedor del mensaje
        const messageText = document.createElement('p');
        messageText.classList.add('text-color-text', 'mb-2', 'text-base', 'font-normal');
        messageText.textContent = message.mensaje; // Contenido del mensaje principal
        messageElement.appendChild(messageText);

        // Crear el contenedor del ID del pedido
        const pedidoId = document.createElement('p');
        pedidoId.classList.add('text-gray-600', 'text-[16px]', 'mt-2');
        pedidoId.textContent = `Pedido ID: ${message.pedido_id}`; // Asegúrate de que `pedido_id` sea el campo correcto
        messageElement.appendChild(pedidoId);

        // Crear el botón "Ver producto"
        const buttonContainer = document.createElement('div');
        buttonContainer.classList.add('mt-2', 'text-right'); // Alineación del botón a la derecha
        const viewProductButton = document.createElement('button');
        viewProductButton.dataset.id = message.pedido_id;
        viewProductButton.classList.add(
            'border',
            'btn_pedido_mensaje',
            'border-color-titulos-entrega',
            'text-color-titulos-entrega',
            'font-semibold',
            'transform', 'transition-transform', 'duration-300', 'ease-in-out', 'hover:scale-110',
            'py-2',
            'px-4',
            'rounded'
        );
        viewProductButton.textContent = 'Ver pedido';
        buttonContainer.appendChild(viewProductButton);
        buttonContainer.addEventListener('click', () => {
            messageElement.remove();
            actualizarEstadoMensaje(message.pedido_id);
            pedidoasignadoarepartidor(message.pedido_id, 'repartidor');
        });
        // Agregar el botón al mensaje
        messageElement.appendChild(buttonContainer);

        // Agregar el mensaje al contenedor principal
        messagesContainer.appendChild(messageElement);

    });

    // Mostrar el modal con los mensajes
    showModal();
}

// Función para mostrar el modal
function showModal() {
    const modal = document.getElementById('messagesModal');
    modal.classList.remove("hidden"); // Mostrar el modal
    modal.classList.add('flex');

}

// Función para cerrar el modal
function closeModal() {
    const modal = document.getElementById('messagesModal');
    modal.classList.remove('flex');
    modal.classList.add("hidden"); // Ocultar el modal
}

// Event listener para cerrar el modal cuando se hace clic en la "X"
document.getElementById('closeModalmensajes').addEventListener('click', closeModal);

//FIN WENSCOKET MENSAJES


const cantidad_pedidos = document.querySelector('.cantidad_pedidos');

function actualizarEstadoYPagoPanelAdministrador(pedidoId, estado) {
    // Seleccionar los elementos del DOM correspondientes al pedido
    const pedidoCaja = mi_cuenta_contenedor_pedidos.querySelector(`#caja-${pedidoId}`);
    if (!pedidoCaja) {
        console.error(`Pedido con ID ${pedidoId} no encontrado.`);
        return;
    }
    pedidoCaja.remove();
    cantidad_pedidos.textContent = parseInt(cantidad_pedidos.textContent) - 1;
}


function actualizar_Estado_delivery_panel_cliente($pedido_id, $estado) {
    const estado_pedido_span = mi_cuenta_contenedor_pedidos.querySelector("#caja-" + $pedido_id).querySelector('.estado_pedido_span');
    estado_pedido_span.innerHTML = $estado == 'En Camino'
        ? $estado + " <span class='text-2xl'>🚚</span>"
        : $estado;
}


const modalmensajespedidoasignado = document.getElementById('modalmensajespedidoasignado');
const closeModalmensajesAsignacion = document.getElementById('closeModalmensajesAsignacion');
const modalmensajespedidodetalle = document.getElementById('modalmensajespedidodetalle');
const closeModalmensajesdetalle = document.getElementById('closeModalmensajesdetalle');
if (closeModalmensajesAsignacion) {
    closeModalmensajesAsignacion.addEventListener('click', () => {
        cerrarmodalPedidoAsignado();
    });
}
if (closeModalmensajesdetalle) {
    closeModalmensajesdetalle.addEventListener('click', () => {
        cerrarmodalPedidoDetalles();
    });
}

function cerrarmodalPedidoAsignado() {
    modalmensajespedidoasignado.classList.remove('flex');
    modalmensajespedidoasignado.classList.add('hidden');
}
function abrirmodalPedidoDetalles() {
    modalmensajespedidodetalle.classList.remove('hidden');
    modalmensajespedidodetalle.classList.add('flex');

}
function cerrarmodalPedidoDetalles() {
    modalmensajespedidodetalle.classList.remove('flex');
    modalmensajespedidodetalle.classList.add('hidden');
}

function getRepartidores() {
    const url = "/repartidores/";
    const selectElement = document.getElementById('selectrepartidores'); // Selecciona el <select> por su ID
    const select_pedidos_tarjetas = document.getElementById('repartidor');

    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json', // Define el contenido como JSON
        },
    })
        .then(response => {
            if (response.status !== 200) {
                // Manejo de errores si el servidor responde con un estado distinto a 200
                return response.text().then(text => {
                    throw new Error(text);
                });
            }
            return response.json(); // Convertir la respuesta a JSON
        })
        .then(data => {
            const repartidores = data.repartidores;

            // Verifica si 'repartidores' es un arreglo
            if (repartidores && Array.isArray(repartidores)) {
                const opciones = repartidores.map(repartidor => {
                    // Asegúrate de que el objeto 'persona' y 'nombres' existan
                    const nombres = repartidor.persona ? repartidor.persona.nombres : 'N/A';

                    return `
                        <option value="${repartidor.id}">
                            ${nombres} 
                        </option>
                    `;
                }).join(''); // Combina todas las opciones en un solo string

                // Actualiza el contenido del <select>
                selectElement.innerHTML += opciones;
                select_pedidos_tarjetas.innerHTML += opciones;
            } else {
                console.warn('No se encontraron repartidores disponibles.');
            }
        })
        .catch(error => {
        });
}



function pedidoasignadoarepartidor(id, tipo) {
    const url = "/mensajes/" + id;
    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json', // Define el contenido como JSON
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
            if (tipo == 'repartidor') {
                return;
            } else {
                abrirmodalPedidoDetalles();
                llenarPedido(result, 'admin');
            }
        })
        .catch(error => {
            mensajeError(error); // Mostrar mensaje de error
        });
}
function llenarPedido(pedido, tipo) {
    let contenedor = null;
    if (tipo == 'repartidor') {
        contenedor = document.getElementById("pedidoAsignado");

    } else {
        contenedor = document.getElementById("pedidoAsignadoDetalle");

    }

    // Generar contenido dinámico
    contenedor.innerHTML = `
    <div class="flex text-[16px] items-center justify-between text-color-titulos-entrega">
        <h3 class="font-bold text-base">Pedido #${pedido.id}</h3>
        <span class="text-[16px] ">${pedido.fecha}</span>
    </div>
    <p class="text-[16px] mt-2 text-color-titulos-entrega">
        <i class="fa-solid fa-user "></i>
        ${pedido.nombres || "Sin nombre"}
    </p>
    <p class="text-[16px] mt-1">
        <i class="fa-solid fa-phone text-color-titulos-entrega"></i>
        ${pedido.celular || "Sin teléfono"}
    </p>
    <p class="text-[16px] mt-1">
        <i class="fa-solid fa-location-dot text-color-titulos-entrega"></i>
        ${pedido.direccion || "Sin dirección"}
    </p>
    <p class="text-[16px] mt-1">
        <i class="fa-solid fa-box text-color-titulos-entrega"></i>
        ${pedido.detalles.map(item => `${item.producto?.descripcion || "Nulo"} x ${item.cantidad}`).join(", ")}
    </p>
    <div class="mt-2">
        <p class="font-semibold">Total: <span class="text-color-titulos-entrega">S/ ${pedido.total}</span></p>
    </div>
    <div class="flex items-center justify-between mt-2">
        <span class="text-[16px] ">Delivery: 
            <span class=" font-bold spanestadorepartidor">
                ${pedido.estado}
            </span>
        </span>
       <p class="text-[16px] mt-1 metodopedido font-bold">
        <i class="fa-solid fa-wallet 
     text-color-titulos-entrega"></i>
        ${pedido.pago ? `Pagado con ${pedido.metodo}` : "Pendiente de pago"}
    </p>
    </div>
   
    <p class="text-[16px] mt-1">
        <i class="fa-solid fa-sticky-note text-color-titulos-entrega"></i>
        Notas del pedido: ${pedido.nota || "Sin notas"}
    </p>
    <p class="text-[16px] mt-1">
        <i class="fas fa-motorcycle text-color-titulos-entrega"></i>
        Repartidor: <span class="spanrepartidoradmin">
            ${pedido.repartidor ? pedido.repartidor?.persona?.nombres : 'Sin Asignación'}
        </span>
    </p>
    <div class="contenedor-de-botones flex space-x-2 p-4 shadow-md rounded-lg items-center justify-center">
        ${botonesAccion(pedido, tipo)}
    </div>
`;

}



function botonesAccion(pedido, tipo) {
    if (tipo === 'repartidor') {
        // Verifica si el estado del pedido es "RECIBIDO"
        if (pedido.estado === "RECIBIDO") {
            return `
                < button title = "El repartidor ya tomó el pedido."
            data - id="${pedido.id}"
            type = "button"
            class="btnaceptarrepartidordirecto border-2 bg-principal text-white hover:bg-principal hover:border-principal rounded p-3 text-xl" >
                <i class="fa-solid fa-hands-holding-circle"></i>
            </button > `;
        }
    } else {
        // Opción para asignar un repartidor
        if (pedido.estado === 'Pendiente') {
            return `
                <button data-id="${pedido.id}"
            type = "button"
            class="btnasignarrepartidordetalle p-3 bg-naranja  text-base hover:border-red-500 border text-white rounded" >
                Asignar Repartidor
            </button> `;
        }
    }
    // Devuelve un string vacío si no se cumple ninguna condición
    return '';
}


document.addEventListener('DOMContentLoaded', () => {
    // Selecciona el contenedor donde se agregarán los botones dinámicamente
    const modalmensajespedidodetalle = document.querySelector('#modalmensajespedidodetalle'); // Asegúrate de tener el ID correcto

    if (modalmensajespedidodetalle) {
        modalmensajespedidodetalle.addEventListener('click', (event) => {
            // Verifica si el elemento clicado es un botón con la clase específica
            if (event.target.classList.contains('btnasignarrepartidordetalle')) {
                const pedidoId = event.target.dataset.id; // Obtener el ID del pedido
                const selectRepartidor = document.querySelector('#selectrepartidores'); // Seleccionar el elemento <select>
                const repartidor = document.querySelector('.spanrepartidoradmin');
                // Verifica si se seleccionó un repartidor
                if (!selectRepartidor || selectRepartidor.value === '') {
                    mensajeError("Por favor, seleccione un repartidor.");
                } else {
                    // Llamar a la función para manejar la asignación
                    asignarRepartidor(pedidoId, selectRepartidor.value, repartidor);
                }
            }
        });
    }
});
function modificarDomPedido(repartidor, pedido_id, elemento) {
    const mi_cuenta_contenedor_pedidos = document.getElementById('mi_cuenta_contenedor_pedidos');
    if (mi_cuenta_contenedor_pedidos) {
        switch (elemento) {
            case 'repartidor':
                const _contenedor = mi_cuenta_contenedor_pedidos.querySelector('#caja-' + pedido_id);
                if (_contenedor) {
                    _contenedor.querySelector('.span_repartidor_nombre').textContent = repartidor;
                }
                break;

            default:
                break;
        }
    }
}

const pedido_id = document.getElementById('pedido_id');
const id_pedido_modal_editar = document.getElementById('id_pedido_modal_editar');
const field_cliente = document.getElementById('field_cliente');
const field_Celular = document.getElementById('field_Celular');
const field_direccion = document.getElementById('field_direccion');
const field_referencia = document.getElementById('field_referencia');
const estado_pedido = document.getElementById('estado_pedido');
const estado_pago = document.getElementById('estado_pago');
const medio_pago = document.getElementById('medio_pago');
const notas = document.getElementById('notas');
const modal_editar_pedido_id = document.getElementById('modal_editar_pedido_id');
const form_editar_pedido_repartidor = document.getElementById('form_editar_pedido_repartidor');
let spanrepartidor;
const mi_cuenta_contenedor_pedidos = document.getElementById('mi_cuenta_contenedor_pedidos_super');
let productos_del_pedido;

if (mi_cuenta_contenedor_pedidos) {
    mi_cuenta_contenedor_pedidos.addEventListener('click', (event) => {
        const botonasignar = event.target.closest('.btnasignarrepartidor');
        const btn_editar_pedido = event.target.closest('.btn_editar_pedido');
        if (botonasignar) {
            botonasignar.addEventListener('click', () => {
                const pedido = botonasignar.closest('.mi_cuenta_pedido');
                spanrepartidor = pedido.querySelector('.span_repartidor_nombre');
                const idpedido = botonasignar.dataset.id;
                const productos_requeridos = document.getElementById('productos_requeridos');
                modalasignarrepartidor.classList.remove('hidden');
                modalasignarrepartidor.classList.add('flex');
                pedido_id.value = idpedido;
                productos_del_pedido = pedido.querySelectorAll('.productos_del_pedido span'); // Encuentra los productos
                const productos_del_pedido_detalles = pedido.querySelectorAll('.productos_del_pedido p'); // Encuentra los productos
                let productos_requ = Array.from(productos_del_pedido_detalles).map(element => element.textContent.trim());
                // Limpiar la lista antes de agregar nuevos elementos
                productos_requeridos.innerHTML = '';

                // Crear un elemento <li> por cada producto
                productos_requ.forEach(texto => {
                    let li = document.createElement("li");
                    li.textContent = texto;
                    productos_requeridos.appendChild(li);
                });



            })
        }
        if (btn_editar_pedido) {
            btn_editar_pedido.addEventListener('click', () => {
                const idpedido = btn_editar_pedido.dataset.id;

                modal_editar_pedido.classList.remove('hidden');
                modal_editar_pedido.classList.add('flex');
                id_pedido_modal_editar.value = idpedido;
                modal_editar_pedido_id.textContent = idpedido;
                obtenerDatosPedido(idpedido);
            })
        }
    });

}





async function obtenerDatosPedido(idPedido) {
    try {
        // Realizar la solicitud al servidor
        const response = await fetch(`/pedido/${idPedido}`, {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        // Verificar si la respuesta es correcta
        if (!response.ok) {
            throw new Error(`Error al obtener el pedido: ${response.statusText}`);
        }

        // Parsear la respuesta como JSON
        const data = await response.json();

        // Llenar los campos del formulario con los datos obtenidos
        field_cliente.value = data.mensaje.nombres || '';
        field_Celular.value = data.mensaje.celular || '';
        field_direccion.value = data.mensaje.direccion || '';
        field_referencia.value = data.mensaje.nota || '';
        estado_pedido.value = data.mensaje.estado || 'Pendiente';
        estado_pago.value = data.mensaje.pago ? 'Pagado' : 'Pendiente de pago';
        medio_pago.value = data.mensaje.metodo;

        notas.value = data.mensaje.nota_interna || '';
    } catch (error) {
        console.error('Error:', error.message);
    }
}

if (form_editar_pedido_repartidor) {
    form_editar_pedido_repartidor.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formdata = new FormData(form_editar_pedido_repartidor);
        let data = {};
        formdata.forEach((element, key) => {
            data[key] = element;
        });
        try {
            const response = await fetch(form_editar_pedido_repartidor.action, {
                method: 'PUT', // Especifica el método HTTP
                headers: {
                    "Content-Type": "application/json", // Especifica el tipo de contenido
                    'X-CSRF-TOKEN': token // Token CSRF
                },
                body: JSON.stringify(data)

            });
            // Verificar si la respuesta es correcta
            if (!response.ok) {
                const errorData = await response.json();
                console.log(errorData);
                throw new Error(`Error al obtener el pedido: ${response.statusText}`);
            }
            // Parsear la respuesta como JSON
            const result = await response.json();
            Swal.fire({

                title: 'Confirmación',
                text: result.mensaje,
                icon: 'success',
                timerProgressBar: true,
                timer: 2000,
                showConfirmButton: false,
                customClass: {
                    timerProgressBar: 'bg-green-500'
                }
            })
            setTimeout(() => {
                window.location.reload();
            }, 3000); // delay es el tiempo en milisegundos
        } catch (error) {
            mensajeError(error);
        }
    });
}
if (formAsignarRepartidor) {
    formAsignarRepartidor.addEventListener('submit', async (e) => {
        e.preventDefault();

        const repartidorNombre = repartidor.options[repartidor.selectedIndex].text.trim();
        let repartidor_encontrado = null;
        // Selecciona todos los elementos con la clase 'repartidor_nombre_salida'
        let salidas_repartidores_para_asignar = document.querySelectorAll('.repartidor_nombre_salida');
        let salida_id;
        salidas_repartidores_para_asignar.forEach(element => {
            if (repartidorNombre === element.textContent.trim()) {
                repartidor_encontrado = element;
                salida_id = element.dataset.salida_id;
            }
        });
        if (!repartidor_encontrado) {
            Swal.fire({
                title: 'Repartidor no Encontrado',
                text: `No se ha registrado la salida del repartidor.`,
                icon: 'warning',
                timerProgressBar: true,
                timer: 2000,
                showConfirmButton: false,
                customClass: {
                    timerProgressBar: 'bg-red-500 h-2 rounded'
                }
            })

            return;
        }
        const productos = repartidor_encontrado.closest('.contenedor_salidas').querySelectorAll('ul li');
        let detalles_productos = [];
        productos.forEach(element => {
            detalles_productos.push(element.textContent.trim());
        });

        let textos = Array.from(productos_del_pedido).map(element => element.textContent.trim());
        let productosSeparados_requeridos = textos.map(item => {
            let partes = item.split('/');
            return {
                cantidad: parseInt(partes[1].trim()),// Convertir a número
                descripcion: partes[0].trim()
            };
        });
        let productosSeparados = detalles_productos.map(item => {
            let partes = item.split('×'); // Dividir en cantidad y descripción
            return {
                cantidad: parseInt(partes[0].trim()), // Convertir cantidad a número
                descripcion: partes[1].trim()
            };
        });

        // Crear un mapa con el stock disponible
        let stockDisponible = new Map();
        productosSeparados.forEach(producto => {
            stockDisponible.set(producto.descripcion, producto.cantidad);
        });

        // Verificar si hay suficiente stock
        let puedeVender = true;

        productosSeparados_requeridos.forEach(producto => {
            let stock = stockDisponible.get(producto.descripcion) || 0;
            if (stock < producto.cantidad) {
                Swal.fire({
                    title: 'Stock Faltante',
                    text: `No hay suficiente stock para ${producto.descripcion}. Disponible: ${stock}, Requerido: ${producto.cantidad}`,
                    icon: 'warning',
                    timerProgressBar: true,
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: {
                        timerProgressBar: 'bg-red-500 h-2 rounded'
                    }
                })
                puedeVender = false;
            }
        });
        let exito_repartidor = false;

        if (puedeVender) {
            let datos_actualizar = repartidor_encontrado.closest('.contenedor_salidas').querySelector('ul');

            // Restar el stock de los productos vendidos
            productosSeparados_requeridos.forEach(producto => {
                let stockActual = stockDisponible.get(producto.descripcion) || 0;
                let nuevoStock = stockActual - producto.cantidad;

                // Evitar stock negativo
                if (nuevoStock < 0) {
                    nuevoStock = 0;
                }

                stockDisponible.set(producto.descripcion, nuevoStock);
            });
            const dataform = new FormData(formAsignarRepartidor);
            let data = {};
            dataform.forEach((value, key) => {
                data[key] = value;
            });
            await fetch(formAsignarRepartidor.action, {
                method: formAsignarRepartidor.method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify(data),
            })
                .then(response => {
                    if (response.status !== 201) {
                        return response.text().then((text) => {
                            throw new Error(text);
                        });
                    }
                    return response.json();
                })
                .then(result => {
                    exito_repartidor = true;
                    Swal.fire({

                        title: 'Confirmación',
                        text: result.mensaje,
                        icon: 'success',
                        timerProgressBar: true,
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: {
                            timerProgressBar: 'bg-green-500'
                        }
                    })
                    formAsignarRepartidor.reset();
                    spanrepartidor.textContent = result.repartidor;

                })
                .catch(error => {
                    console.error(error.message);
                    exito_repartidor = false;
                    mensajeError("El repartidor ya esta Asignado a este pedido.");
                });
            if (exito_repartidor) {
                datos_actualizar.innerHTML = '';

                // Agregar todos los productos disponibles a la lista
                stockDisponible.forEach((cantidad, descripcion) => {
                    let li = document.createElement('li');
                    li.textContent = `${cantidad} × ${descripcion}`;
                    datos_actualizar.appendChild(li);
                });



                console.log("Venta procesada. Stock actualizado:", Array.from(stockDisponible.entries()).map(([descripcion, cantidad]) => ({ descripcion, cantidad })));
            }
        } else {
            Swal.fire({
                title: 'Stock Faltante',
                text: 'El repartidor Elegido no tiene suficiente stock para algunos productos.',
                icon: 'warning',
                timerProgressBar: true,
                timer: 2000,
                showConfirmButton: false,
                customClass: {
                    timerProgressBar: 'bg-red-500 h-2 rounded'
                }
            });
            return;
        }


        if (exito_repartidor) {
            console.log(stockDisponible);
            await registrarNuevoStock(salida_id, Array.from(stockDisponible.entries()).map(([descripcion, cantidad]) => ({ descripcion, cantidad })));

        }

    });
}

async function registrarNuevoStock(salida_id, prod) {
    const data = { productos: prod, salida_id: salida_id };
    await fetch('/procesarStock', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
        },
        body: JSON.stringify(data),
    })
        .then(response => {
            if (response.status !== 200) {
                return response.text().then((text) => {
                    throw new Error(text);
                });
            }
            return response.json();
        })
        .then(result => {
            console.log(result);

        })
        .catch(error => {
            console.error(error.message);

        });

}

function asignarRepartidor(pedidoId, repartidor, spanrepartidor) {
    const data = { pedido_id: pedidoId, repartidor_id: repartidor };
    fetch(`/mi-cuenta/asignarRepartidor `, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json', // Define el contenido como JSON
            'X-CSRF-TOKEN': token,
        },
        body: JSON.stringify(data)
    }).then(response => {
        // Verificar el código de estado
        if (response.status !== 201) {
            // Obtener el mensaje de error del servidor
            return response.text().then((text) => {
                throw new Error(text); // Lanza un error con el mensaje del servidor
            });
        }
        return response.json(); // Convertir la respuesta exitosa a JSON
    })
        .then(result => {
            Swal.fire({

                title: 'Confirmación',
                text: result.mensaje,
                icon: 'success',
                timerProgressBar: true,
                timer: 2000,
                showConfirmButton: false,
                customClass: {
                    timerProgressBar: 'bg-green-500'
                }
            })

            spanrepartidor.textContent = result.repartidor;
            modificarDomPedido(result.repartidor, pedidoId, 'repartidor');
        })
        .catch(error => {
            mensajeError(error.message); // Mostrar mensaje de error

        });
}



function actualizarEstadoMensaje(id) {
    fetch(`/actualizar/${id} `, {
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
        })
        .catch(error => {
            mensajeError(error.message); // Mostrar mensaje de error

        });
}

