export function agregarPedido(pedido, usuario) {
    const cantidad_pedidos = document.querySelector('.cantidad_pedidos');
    cantidad_pedidos.textContent = parseInt(cantidad_pedidos.textContent) + 1;
    const contenedor = document.getElementById("mi_cuenta_contenedor_pedidos"); // Asegúrate de que existe este div en tu HTML

    const divPedido = document.createElement("div");
    divPedido.className = "p-[15px] mi_cuenta_pedido w-[363px] max-w-[363px]";
    divPedido.id = `caja-${pedido.id}`;
    const pedidoFecha = new Date(pedido.fecha); // Convertir la fecha a objeto Date
    const opcionesFecha = { day: '2-digit', month: '2-digit', year: 'numeric' };
    const opcionesHora = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };

    const fechaFormateada = pedidoFecha.toLocaleDateString('es-PE', opcionesFecha); // Formato dd/mm/yyyy
    const horaFormateada = pedidoFecha.toLocaleTimeString('es-PE', opcionesHora); // Formato hh:mm:ss am/pm
    const detallesHTML = Array.isArray(pedido.detalles) && pedido.detalles.length > 0
        ? pedido.detalles.map(item =>
            `<p>${item.producto?.descripcion || "Producto desconocido"} x ${item.cantidad}</p>`
        ).join('')
        : '<p>No hay detalles disponibles</p>';


    const promocionesHTML = Array.isArray(pedido.entrega_promociones) && pedido.entrega_promociones.length > 0
        ? pedido.entrega_promociones.map(et =>
            `<p>${et.producto} x ${et.cantidad} Gratis.</p>`
        ).join('')
        : '';


    const botella_promocion = (promociones) => {
        if (!promociones || promociones.length === 0) return "";

        return `
                <div class="absolute -top-14 right-0">
                    <div class="relative">
                        <div class="bg-transparent w-[150px] h-[190px] text-color-titulos-entrega group">
                            <div class="relative">
                                <div class="z-50 top-0 left-0 hidden absolute group-hover:flex bg-tarjetas w-[250px] min-w-[250px] text-white p-2 rounded-md text-center">
                                    <p class="relative text-[14px] leading-[19.6px] pb-4">
                                        <strong>¡Promo Producto Gratis!</strong><br>
                                        Aplica un descuento equivalente al costo de 
                                        ${promociones.length} producto(s) incluido(s) en este pedido.
                                    </p>
                                    <div class="absolute -bottom-[13px] left-[calc(50%-10px)] clip-v-shape h-[20px] w-[20px] bg-color-titulos-entrega">
                                    </div>
                                </div>
                            </div>
                            <img src="imagenes/cajas/botella.svg" alt="" class="absolute top-[120px] left-[104px]">
                        </div>
                    </div>
                </div>
            `;
    };

    const repartidor_admin = (usuario) => {
        if (usuario === 'admin') {
            return `<div class="flex items-center">
                    <div class="w-[17px] h-[17px] text-color-text opacity-80">
                        <i class="fas fa-motorcycle w-full h-full"></i>
                    </div>
                    <div class="flex flex-col justify-center h-[35px] pl-4">
                        <span class="span_repartidor_nombre">Repartidor no asignado</span>
                    </div>
                </div>`;
        }
        return ''; // Retorna una cadena vacía en caso contrario
    };

    const botones = (pedido) => {
        if (usuario == 'admin') {
            return `<div class="flex space-x-2 ml-2 z-50">
                        
                        <button title="Asignar repartidor" data-id="${pedido}"
                            class="btnasignarrepartidor z-50 flex items-center px-2 py-2 border-color-titulos-entrega text-color-titulos-entrega rounded shadow-md hover:scale-150 transform">
                            <i class="btnasignarrepartidor fas fa-user-plus mr-2" data-id="${pedido}"></i> 
                        </button>
    
                        <button title="Editar pedido" data-id="${pedido}"
                            class="btn_editar_pedido flex items-center px-2 py-2 border-color-titulos-entrega text-color-titulos-entrega rounded shadow-md hover:scale-150 transform">
                            <i class="btn_editar_pedido fas fa-edit mr-2" data-id="${pedido}"></i> 
                        </button>
                    </div>`;
        } else {
            return `<div class="flex space-x-2 ml-2 z-50">
                        
                        <button title="Confirmar entrega y pago" data-id="${pedido}"
                            class="btnconfirmarentrega z-50 flex items-center px-2 py-2 border-color-titulos-entrega text-color-titulos-entrega rounded shadow-md hover:scale-110 transform hover:bg-green-600 hover:text-white">
                            <i class="btnconfirmarentrega fas fa-hands-helping mr-2" data-id="${pedido}"></i>
                        </button>
    
                        <button title="Anular pedido" data-id="${pedido}"
                            class="btnanularpedido z-50 flex items-center px-2 py-2 border-color-titulos-entrega text-color-titulos-entrega rounded shadow-md hover:scale-110 transform hover:bg-red-600 hover:text-white">
                            <i class="btnanularpedido fas fa-times-circle mr-2" data-id="${pedido}"></i>
                        </button>
    
                        <button title="Aceptar Pedido" data-id="${pedido}"
                            class="boton_repartidor_aceptar_pedido flex items-center px-2 py-2 border-color-titulos-entrega text-color-titulos-entrega rounded shadow-md hover:bg-naranja hover:text-white transform">
                            <i class="boton_repartidor_aceptar_pedido fas fa-check mr-2" data-id="${pedido}"></i>
                        </button>
    
                    </div>`;
        }
    }
    


    divPedido.innerHTML = `
    <div class="flex-1 h-full w-[333px] max-w-[333px] p-[20px] bg-color-tarjetas rounded-3xl text-color-titulos-entrega font-sans text-base">
        <div class="space-y-0 relative">
            <div class="flex justify-between">
                <div class="flex">
                    <div class="flex">
                        <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                            <img src="imagenes/cajas/tag.svg" alt="">
                        </div>
                        <div><b class="p-2">#${pedido.id}</b></div>
                    </div>
                    <div class="flex ml-2">
                        <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                            <img src="imagenes/cajas/id.svg" alt="">
                        </div>
                        <div class="pl-2"><b>${pedido.id}</b></div>
                    </div>
                </div>
                ${botones(pedido.id)}
            </div>
        ${botella_promocion(pedido.entrega_promociones)}

            <div class="flex items-center">
                <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                    <img src="imagenes/cajas/persona.svg" alt="">
                </div>
                <div class="flex flex-col justify-end h-[35px] ml-2">
                    <p class="mi_cuenta_cliente">${pedido.nombres}</p>
                </div>
            </div>

            <div class="flex items-center">
                <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                    <img src="imagenes/cajas/celular.svg" alt="">
                </div>
                <div class="flex flex-col justify-end h-[35px] ml-2">
                    <p>${pedido.celular}</p>
                </div>
            </div>

            <div class="flex items-center">
                <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                    <img src="imagenes/cajas/direccion.svg" alt="">
                </div>
                <div class="flex flex-col justify-end h-[35px] ml-2">
                    <p>${pedido.direccion}</p>
                </div>
            </div>
            <div class="flex  min-h-[35px] mt-1 mb-1">
                <div class="w-[18px] h-[18px] mt-2 mb-2 text-color-titulos-entrega opacity-80">
                    <img src="imagenes/cajas/nota.svg" alt="">
                </div>
                <p class="p-2"> ${pedido.nota}</p>
            </div>
            <div class="flex items-center">
                <div class="flex items-center space-x-2">
                    <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                        <img src="imagenes/cajas/calendario.svg" alt="">
                    </div>
                    <div class="jet-listing-dynamic-field__content">${fechaFormateada}</div>
                </div>
                <div class="flex items-center space-x-2 ml-6">
                    <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                        <img src="imagenes/cajas/timer.svg" alt="">
                    </div>
                    <div class="jet-listing-dynamic-field__content">${horaFormateada}</div>
                </div>        
            </div>

            <div id="term-${pedido.id}" class="text-start p-2 text-naranja text-base font-semibold"></div>
            <div id="term-${pedido.id}" class="hidden flex-col justify-end h-[35px] text-[13px]">
                <p>El contador de 20 min. llegó a cero.</p>
            </div>

            <div class="flex items-center">
                <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                    <img src="imagenes/cajas/carrito.svg" alt="">
                </div>
                <div class="flex flex-col justify-center ml-2">
                    ${detallesHTML}
                    ${promocionesHTML}
                </div>
            </div>

            <div class="flex items-center">
                <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                    <img src="imagenes/cajas/dinero.svg" alt="">
                </div>
                <div class="flex flex-col justify-center h-[35px] ml-2">
                    <b>S/${pedido.total}</b>
                </div>
            </div>
        </div>
    
        <div class="flex  my-auto space-x-2">
            <div class="w-[25px] h-[25px] text-color-titulos-entrega ">
                <img src="imagenes/cajas/moto.svg" alt="">


            </div>
            <div class="flex  justify-center h-[35px] space-x-1">
                <p>Delivery: </p>
                <b
                    class="estado_pedido_span">${pedido.estado == 'En Camino' ? pedido.estado + ' 🚚' : pedido.estado}</b>
            </div>
        </div>

        <div class="flex my-auto space-x-2">
            <div class="w-[25px] h-[25px]  text-color-titulos-entrega opacity-80">
                <img src="imagenes/cajas/caja_plata.svg" alt="">

            </div>
            <div class="flex  justify-center h-[35px] "><b class="estado_metodo_pago">
                    ${pedido.pago ? 'Pagado ✅' : 'Pendiente de pago'}
                </b>
            </div>
        </div>

        ${repartidor_admin(usuario)}
         <div>
            <p class="underline mt-2">Notas del pedido:</p>
            <p class="p-2">${pedido.nota_interna ? pedido.nota_interna : ''}</p>
        </div>

    </div>
    
`;


    contenedor.prepend(divPedido);
    contador(pedido.fecha, pedido.id);
}



export function contador(fecha, id) {
    const pedidoFecha = new Date(fecha);
    const duracionMaxima = 20 * 60; // 20 minutos en segundos
    const interval = setInterval(() => {
        const ahora = new Date();
        const diferencia = Math.floor((ahora - pedidoFecha) / 1000); // Diferencia en segundos
        const tiempoRestante = duracionMaxima - diferencia;

        const contador = document.getElementById(`term-${id}`);
        const contadorFin = document.getElementById(`term_fin-${id}`);
        if (tiempoRestante <= 0) {
            clearInterval(interval);
            if (contador) contador.innerText = "";
            if (contadorFin) contadorFin.classList.remove("hidden");
            return;
        }

        const minutos = Math.floor(tiempoRestante / 60);
        const segundos = tiempoRestante % 60;

        if (contador) {
            contador.innerText = `Tiempo Restante: ${minutos}m ${segundos}s`;
        }
    }, 1000);
}
