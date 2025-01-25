
import Swal from "sweetalert2"
import Push from "push.js";
import confetti from "canvas-confetti";
const token = document.querySelector('meta[name="token"]').getAttribute('content');
const btn_siguiente_pedido = document.querySelector('.btnproductoagregar');
const contenedor_form_realizar_pedido = document.getElementById('contenedor_form_realizar_pedido');
const form_realizar_pedido = document.getElementById('form_realizar_pedido');
const input_celular = document.getElementById('celular');
const btn_regresar_a_productos = document.getElementById('btn_regresar_a_productos');
const dominio = '';
const contenedor_confeti = document.getElementById('contenedor_confeti');
// Seleccionamos los elementos
const copyButton = document.getElementById('copy-button');
const numberToCopy = document.getElementById('number-to-copy');

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

if (input_celular) {
    input_celular.addEventListener('keyup', async (e) => {
        const valor = e.target.value.trim(); // Limpiar espacios en blanco
        let usuario_id = form_realizar_pedido.querySelector("#usuario_id");

        .3
        let nombres = form_realizar_pedido.querySelector("#nombres");
        let direccion = form_realizar_pedido.querySelector("#direccion");
        let referencia = form_realizar_pedido.querySelector("#referencia");

        if (valor.length === 9) { // Validar la longitud del valor del input
            input_celular.disabled = true;

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
                    nombres.value = data.mensaje.usuario;
                    direccion.value = data.mensaje.persona.direccion;
                    referencia.value = data.mensaje.persona.nota;
                    input_celular.disabled = false;
                    input_celular.focus();

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
                    input_celular.disabled = false;
                    form_realizar_pedido.reset();

                    input_celular.focus();


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
                input_celular.disabled = false;
                form_realizar_pedido.reset();
                input_celular.focus();


            }
        }
    });
}



// Agregar eventos a todos los botones de disminución
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

    // Obtener el precio base normal
    const precioNormal = parseFloat(contenedor.querySelector('.precionormal').textContent.replace('S/', '').trim());
    let cantidad = parseInt(cantidadInput.value) || 0;

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
    }

    // Actualiza el input de cantidad
    cantidadInput.value = cantidad;

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

    // Guardar el producto y cantidad en idproductos
    const index = window.idproductos.findIndex(item => item.id === productoId);
    if (index === -1) {
        // Si el producto no está en el array, agregarlo
        window.idproductos.push({ id: productoId, cantidad });
    } else {
        // Si el producto ya existe, actualizar la cantidad
        window.idproductos[index].cantidad = cantidad;
    }

    // Filtrar productos con cantidad cero
    window.idproductos = window.idproductos.filter(item => item.cantidad > 0);
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
        ocultarContenedorProductosItem();
        contenedor_form_realizar_pedido.classList.remove('hidden');
        contenedor_form_realizar_pedido.classList.add('grid');

    });
}

if (form_realizar_pedido) {
    form_realizar_pedido.addEventListener('submit', async (event) => {
        event.preventDefault();
        form_realizar_pedido.classList.add('opacity-50')

        const formdata = new FormData(form_realizar_pedido);
        let data = {};

        // Convertir FormData a un objeto
        formdata.forEach((value, key) => {
            data[key] = value;
        });

        // Agregar los productos al objeto de datos
        data['productos'] = window.idproductos || [];
        console.log(window.idproductos);
        try {
            // Realizar la solicitud Fetch
            const response = await fetch(form_realizar_pedido.action, {
                method: form_realizar_pedido.method,
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
            window.idproductos=[];
            window.location.href = respuesta.ruta;

        } catch (error) {
            console.error("Error al realizar el pedido:", error);

            // Mostrar mensaje de error (usando SweetAlert como ejemplo)
            Swal.fire({
                title: "Error",
                text: error.message || "Ha ocurrido un error inesperado.",
                icon: "error",

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
//Mensaje de Exito
function mensajeExito(texto) {
    Swal.fire({
        title: 'Confirmación!',
        text: texto,
        icon: 'success',
        confirmButtonText: 'Aceptar'
    })
}
//Mensaje de Exito
function mensajeExitoPedido(titulo, texto) {
    Swal.fire({
        title: titulo,
        text: texto,
        icon: 'success',
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
const headers = {
    "Content-Type": "application/json",
    'X-CSRF-TOKEN': token, // El token CSRF debe estar configurado correctamente
};
function conectarWebSocket() {
    window.Echo.private(webSocketChannel)
        .listen('MensajeEntendido', async (e) => {
            switch (e.message.operacion) {
                case 'confirmacion':
                    crearNotificacionWindow(e.message.operacion, e.message.pedido_id);
                    break;
                case 'asignacion':
                    crearNotificacionWindow(e.message.operacion, e.message.pedido_id);
                    pedidoasignadoarepartidor(e.message.pedido_id, 'repartidor');

                    break;
                default:
                    getMessages(); // Recargar los mensajes cuando se recibe un nuevo evento
                    crearNotificacionWindow(e.message.operacion, e.message.pedido_id);
                    break;
            }


        });
    console.log("Conexion establecida  a websocket." + "Canal" + " " + webSocketChannel);
}
// Llamar la función para conectar el WebSocket
if (dominio.trim().length > 3) {
    conectarWebSocket();
    getRepartidores();

}

// Función para enviar un mensaje
function enviarMensaje(mensaje, usuarioDestino, pedido) {

    const data = {
        message: mensaje,
        receiver_id: usuarioDestino,
        pedido_id: pedido,
    };

    fetch(`${dominio}/crearmensaje`, {
        method: "POST",
        headers,
        body: JSON.stringify(data),
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
            // mensajeError(error.mensaje); // Mostrar mensaje de error
            console.log(error);
        });

}
// Función para obtener los mensajes
function getMessages() {
    try {
        fetch(`${dominio}/mensajes`, {
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
        messageElement.classList.add('mensajes_socket', 'bg-tarjetas', 'p-4', 'rounded-lg', 'shadow-sm', 'w-full', 'flex', 'flex-col', 'mb-4');

        // Crear el contenedor del mensaje
        const messageText = document.createElement('p');
        messageText.classList.add('text-white', 'mb-2', 'text-lg', 'font-normal');
        messageText.textContent = message.mensaje; // Contenido del mensaje principal
        messageElement.appendChild(messageText);

        // Crear el contenedor del ID del pedido
        const pedidoId = document.createElement('p');
        pedidoId.classList.add('text-gray-500', 'text-sm');
        pedidoId.textContent = `Pedido ID: ${message.pedido_id}`; // Asegúrate de que `pedido_id` sea el campo correcto
        messageElement.appendChild(pedidoId);

        // Crear el botón "Ver producto"
        const buttonContainer = document.createElement('div');
        buttonContainer.classList.add('mt-2', 'text-right'); // Alineación del botón a la derecha
        const viewProductButton = document.createElement('button');
        viewProductButton.dataset.id = message.pedido_id;
        viewProductButton.classList.add(
            'bg-principal',
            'btn_pedido_mensaje',
            'hover:bg-principalhover',
            'text-white',
            'py-2',
            'px-4',
            'rounded'
        );
        viewProductButton.textContent = 'Ver pedido';
        buttonContainer.appendChild(viewProductButton);
        buttonContainer.addEventListener('click', () => {
            messageElement.remove();
            actualizarEstadoMensaje(message.pedido_id);
            pedidoasignadoarepartidor(message.pedido_id);
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

// Registro de notificaciones activas
const notificacionesActivas = new Map();

function crearNotificacionWindow(operacion, pedido_id) {
    if (!('Notification' in window)) {
        alert("Este navegador no soporta las notificaciones.");
        return;
    }

    // Verificar y solicitar permisos si es necesario
    if (Notification.permission !== 'granted') {
        Notification.requestPermission().then(respuesta => {
            if (respuesta === 'denied') {
                mensajeError("Has bloqueado las notificaciones. Por favor, habilítalas en la configuración del navegador.");
                return;
            }
        });
    }

    // Crear notificación según la operación

    switch (operacion) {
        case 'confirmacion':
            crearNotificacionRepartidor(pedido_id);

            break;
        case 'asignacion':
            crearNotificacionRepartidorAsignacion(pedido_id);

            break;
        default:
            crearNotificacion();

            break;
    }
}

function crearNotificacion() {
    const tag = "pedido-notificacion";

    // Evitar duplicidad comprobando el tag
    if (notificacionesActivas.has(tag)) {
        return;
    }

    const notificacion = Push.create("Nuevo Pedido", {
        body: "Administrador, ha llegado un nuevo pedido para la empresa. ¡Revísalo ahora!", // Texto de la notificación
        icon: "https://th.bing.com/th/id/R.34060d9efd4c69e41d3bd43661e3c6e0?rik=2c0ktKDtZCElOw&pid=ImgRaw&r=0", // URL del ícono
        requireInteraction: true, // Mantener la notificación abierta hasta que el usuario la cierre
        vibrate: [200, 100, 200], // Patrón de vibración en dispositivos móviles
        tag: tag, // Identificador único
        renotify: true, // Mostrar como nueva aunque tenga el mismo tag
        onClick: function () {
            window.focus();
            this.close();
        },
        onClose: function () {
            notificacionesActivas.delete(tag); // Eliminar del registro cuando se cierre
        }
    });

    // Registrar la notificación activa
    notificacionesActivas.set(tag, notificacion);
}

function crearNotificacionRepartidor(pedido_id) {
    const tag = `pedido-recibido-${pedido_id}`; // Un tag único por pedido

    // Evitar duplicidad comprobando el tag
    if (notificacionesActivas.has(tag)) {
        return;
    }

    const notificacion = Push.create("Tu pedido está en camino", {
        body: `El repartidor ha tomado tu pedido #${pedido_id} y está en camino para entregártelo. ¡Prepárate para recibirlo pronto!`,
        icon: "https://th.bing.com/th/id/R.34060d9efd4c69e41d3bd43661e3c6e0?rik=2c0ktKDtZCElOw&pid=ImgRaw&r=0", // Icono de notificación
        requireInteraction: true,
        vibrate: [200, 100, 200],
        tag: tag, // Identificador único
        renotify: true,
        onClick: function () {
            window.focus();
            this.close();
        },
        onClose: function () {
            notificacionesActivas.delete(tag); // Eliminar del registro cuando se cierre
        }
    });

    // Registrar la notificación activa
    notificacionesActivas.set(tag, notificacion);
}
function crearNotificacionRepartidorAsignacion(pedido_id) {
    const tag = `pedido-asignado-${pedido_id}`; // Un tag único por pedido

    // Evitar duplicidad comprobando el tag
    if (notificacionesActivas.has(tag)) {
        return;
    }

    const notificacion = Push.create("Nuevo Pedido Asignado", {
        body: `Repartidor, se te ha asignado el pedido #${pedido_id}. Por favor, revísalo y prepárate para realizar la entrega.`,
        icon: "https://th.bing.com/th/id/R.34060d9efd4c69e41d3bd43661e3c6e0?rik=2c0ktKDtZCElOw&pid=ImgRaw&r=0", // Icono de notificación
        requireInteraction: true,
        vibrate: [200, 100, 200],
        tag: tag, // Identificador único
        renotify: true,
        onClick: function () {
            window.focus();
            this.close();
        },
        onClose: function () {
            notificacionesActivas.delete(tag); // Eliminar del registro cuando se cierre
        }
    });

    // Registrar la notificación activa
    notificacionesActivas.set(tag, notificacion);
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
function abrirmodalPedidoAsignado() {
    modalmensajespedidoasignado.classList.remove('hidden');
    modalmensajespedidoasignado.classList.add('flex');

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
    const url = dominio.replace("/", "") + "/repartidores/";
    const selectElement = document.getElementById('selectrepartidores'); // Selecciona el <select> por su ID

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
        .then(result => {
            const mensaje = Object.values(result); // Convierte las propiedades del objeto en un arreglo

            // Verifica si el mensaje es válido y contiene datos
            if (mensaje && Array.isArray(mensaje)) {
                const opciones = mensaje.map(element => {
                    // Si "persona" es un arreglo, usa map para iterar y generar los nombres
                    const nombres = element.persona.map(it => it.nombres).join(', '); // Combina los nombres en una sola cadena
                    const persona = element.persona.map(it => it.id).join('');
                    return `
                        <option value="${persona}">
                            ${nombres} 
                        </option>
                    `;
                }).join(''); // Combina todas las opciones en un solo string
                // Actualiza el contenido del <select>
                selectElement.innerHTML += opciones;
            } else {
                console.warn('No se encontraron repartidores disponibles.');
            }
        })
        .catch(error => {
            console.error('Error al obtener los repartidores:', error);
            // Opcional: puedes agregar una opción para informar sobre el error
            selectElement.innerHTML += `< option value = "" disabled > Error al cargar repartidores</option > `;
        });
}



function pedidoasignadoarepartidor(id, tipo) {
    const url = dominio.replace("/", "") + "/mensajes/" + id;
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
                abrirmodalPedidoAsignado();
                llenarPedido(result, 'repartidor');
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
    <div class="flex items-center justify-between">
        <h3 class="font-bold text-lg">Pedido #${pedido.id}</h3>
        <span class="text-sm text-gray-500">${pedido.fecha}</span>
    </div>
    <p class="text-sm mt-2">
        <i class="fa-solid fa-user text-blue-500"></i>
        ${pedido.cliente?.nombres || "Sin nombre"} ${pedido.cliente?.apellidos || ""}
    </p>
    <p class="text-sm mt-1">
        <i class="fa-solid fa-phone text-green-500"></i>
        ${pedido.cliente?.telefono || "Sin teléfono"}
    </p>
    <p class="text-sm mt-1">
        <i class="fa-solid fa-location-dot text-red-500"></i>
        ${pedido.cliente?.direccion || "Sin dirección"}
    </p>
    <p class="text-sm mt-1">
        <i class="fa-solid fa-box text-yellow-500"></i>
        ${pedido.detalles.map(item => `${item.producto?.descripcion || "Nulo"} x ${item.cantidad}`).join(", ")}
    </p>
    <div class="mt-2">
        <p class="font-semibold">Total: <span class="text-green-600">S/ ${pedido.total}</span></p>
    </div>
    <div class="flex items-center justify-between mt-2">
        <span class="text-sm ${tipo == 'repartidor' ? 'text-white' : 'text-black'}">Delivery: 
            <span class="${pedido.estado === 'RECIBIDO' ? 'text-red-600' : 'text-green-600'} font-bold spanestadorepartidor">
                ${estadoDelivery(pedido.estado, tipo)}
            </span>
        </span>
        <span class="text-sm ${tipo == 'repartidor' ? 'text-white' : 'text-black'}">Pagado: 
            <span class="${pedido.pago ? 'text-green-600' : 'text-red-600'} font-bold spanpagado">
                ${pedido.pago ? '✅' : '❌'}
            </span>
        </span>
    </div>
    <p class="text-sm mt-1 metodopedido">
        <i class="fa-solid fa-wallet text-purple-500"></i>
        ${pedido.pago ? `Pagado con ${pedido.metodo}` : "Pendiente de pago"}
    </p>
    <p class="text-sm mt-1">
        <i class="fa-solid fa-sticky-note text-gray-500"></i>
        Notas del pedido: ${pedido.nota || "Sin notas"}
    </p>
    <p class="text-sm mt-1">
        <i class="fas fa-motorcycle text-red-500"></i>
        Repartidor: <span class="spanrepartidoradmin">
            ${pedido.repartidor ? pedido.repartidor.persona.nombres : 'Sin Asignación'}
        </span>
    </p>
    <div class="contenedor-de-botones flex space-x-2 p-4 shadow-md rounded-lg items-center justify-center">
        ${botonesAccion(pedido, tipo)}
    </div>
`;

}

function estadoDelivery(estado, tipo) {
    switch (estado) {
        case "RECIBIDO":
            return "Pendiente ❌";
        case "ENVIADO":
            return "En camino 🚚";
        case "ENTREGADO":
            return "Entregado ✅";
        default:
            return "Estado desconocido ⚠️";
    }
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
        if (pedido.estado === 'RECIBIDO') {
            return `
                <button data-id="${pedido.id}"
            type = "button"
            class="btnasignarrepartidordetalle p-3 bg-principal hover:bg-principalhover text-xl text-white rounded" >
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


function asignarRepartidor(pedidoId, repartidor, spanrepartidor) {
    const data = { pedido_id: pedidoId, repartidor_id: repartidor };
    fetch(`${dominio} /asignarRepartidor `, {
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
            mensajeExito(result.mensaje); // Manejo del resultado exitoso
            spanrepartidor.textContent = result.repartidor;
        })
        .catch(error => {
            mensajeError(error.message); // Mostrar mensaje de error

        });
}

const pedidoAsignado = document.getElementById('pedidoAsignado');
if (pedidoAsignado) {

    pedidoAsignado.addEventListener('click', (event) => {
        const boton = event.target.closest('.btnaceptarrepartidordirecto');
        const id = boton.dataset.id;
        boton.disabled = true;
        fetch(`${dominio} /cambiarestadopago/${id} `, {
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
                mensajeExito(result.mensaje); // Manejo del resultado exitoso
                cerrarmodalPedidoAsignado();
            })
            .catch(error => {
                mensajeError(error.message); // Mostrar mensaje de error
                boton.disabled = false;

            });



    });

}

function actualizarEstadoMensaje(id) {
    fetch(`${dominio} /actualizar/${id} `, {
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

