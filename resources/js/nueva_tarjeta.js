export function agregarPedido(pedido, usuario, tiempo) {
    const pedidos_vacio = document.querySelector('.pedidos_vacio');
    if (pedidos_vacio && !pedidos_vacio.classList.contains('hidden')) {
        pedidos_vacio.classList.add('hidden');
    }
    const cantidad_pedidos = document.querySelector('.cantidad_pedidos');
    cantidad_pedidos.textContent = parseInt(cantidad_pedidos.textContent) + 1;
    const contenedor = document.getElementById("mi_cuenta_contenedor_pedidos"); // Asegúrate de que existe este div en tu HTML

    const divPedido = document.createElement("div");
    divPedido.className = "md:p-[15px] p-0 mi_cuenta_pedido flex flex-col justify-center items-center w-[95%] md:w-[363px]";
    divPedido.id = `caja-${pedido.id}`;
    const pedidoFecha = new Date(pedido.fecha); // Convertir la fecha a objeto Date
    const opcionesFecha = { day: '2-digit', month: '2-digit', year: 'numeric' };
    const opcionesHora = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };

    const fechaFormateada = pedidoFecha.toLocaleDateString('es-PE', opcionesFecha); // Formato dd/mm/yyyy
    const horaFormateada = pedidoFecha.toLocaleTimeString('es-PE', opcionesHora); // Formato hh:mm:ss am/pm
    const detallesHTML = Array.isArray(pedido.detalles) && pedido.detalles.length > 0
        ? pedido.detalles.map(item => `
        <span hidden>
            ${item.producto?.nombre}${item.tipo ? '_' + item.tipo : ''}/${item.cantidad}
        </span>

        <p>
            ${item.producto?.nombre}${item.tipo ? '_' + item.tipo : ''} x ${item.cantidad}
        </p>
    `).join('')
        : '';


    const promocionesHTML = Array.isArray(pedido.entrega_promociones) && pedido.entrega_promociones.length > 0
        ? pedido.entrega_promociones
            .filter(et => et.estado) // Filtra solo los que tienen estado `true`
            .map(et => `
                <span hidden>${et.producto}/${et.cantidad}</span>
                <p>${et.producto} x ${et.cantidad} Gratis.</p>
            `)
            .join('')
        : '';




    const botella_promocion = (promociones) => {
        if (!promociones || promociones.length === 0) return "";

        return `
                <div class="absolute -top-14 right-0">
                    <div class="relative">
                        <div class="bg-transparent w-[150px] h-[190px] text-color-titulos-entrega ">
                            
                            <img src="imagenes/cajas/pg.svg" alt="" class="absolute top-[120px] left-[104px]">
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
                            <i class=" fas fa-user-plus mr-2" data-id="${pedido}"></i> 
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

    const cupon = (pedido) => {
        if (pedido.cupon && pedido.cupon.trim() !== "") {
            return `
                <div class="absolute -top-0 right-28 md:right-10 h-[50px]">
                    <div class="relative bg-transparent w-[150px] h-[190px] text-color-titulos-entrega group">
                        <div>
                            <div class="contenedor_cupon_aplicado z-50 -top-10 bg-green-400 left-0 hidden absolute group-hover:flex w-[250px] min-w-[250px] text-white p-2 rounded-md text-center">
                                <p class="relative text-[14px] leading-[19.6px]">
                                    <strong>¡Cupón Aplicado!</strong><br>
                                    Aplica un descuento de S/${pedido.descuento} 
                                    equivalente al cupón #${pedido.cupon} en este pedido.
                                </p>
                                <div class="absolute -bottom-[13px] left-1/2 md:left-1/2 -translate-x-1/2 md:-translate-x-[60px] clip-v-shape h-[20px] w-[20px] bg-green-400"></div>
                            </div>
                        </div>
                        <img src="/imagenes/cajas/cd.svg" alt=""
                            class="btn_cupon_aplicado cursor-pointer absolute top-[55px] md:left-[25px] right-0 md:w-[40px] w-[35px] h-[50px]">
                    </div>
                </div>
            `;
        }
        return ""; // Retorna vacío si no hay cupón válido
    };



    divPedido.innerHTML = `
    <div class="flex-1 h-full w-[95%] md:w-[333px] md:max-w-[333px] p-[20px] bg-color-tarjetas rounded-3xl text-color-titulos-entrega font-sans text-base">
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
        ${cupon(pedido)}
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
                <div class="flex flex-col justify-end ml-2">
                    <p>${pedido.direccion}</p>
                </div>
            </div>
            <div class="flex items-center   mt-1 mb-1 gap-x-2">
                    <div class=" h-[28px] flex-shrink-0 flex items-center"> 
                        <svg class="text-color-titulos-entrega text-left mt-1 opacity-80 w-full h-full" aria-hidden="true" viewBox="0 0 288 512" xmlns="http://www.w3.org/2000/svg">
                                <path d="M112 316.94v156.69l22.02 33.02c4.75 7.12 15.22 7.12 19.97 0L176 473.63V316.94c-10.39 1.92-21.06 3.06-32 3.06s-21.61-1.14-32-3.06zM144 0C64.47 0 0 64.47 0 144s64.47 144 144 144 144-64.47 144-144S223.53 0 144 0zm0 76c-37.5 0-68 30.5-68 68 0 6.62-5.38 12-12 12s-12-5.38-12-12c0-50.73 41.28-92 92-92 6.62 0 12 5.38 12 12s-5.38 12-12 12z"></path>
                        </svg>
                    </div>
                            <p class="mt-1"> ${pedido.nota}</p>
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
                <div class="productos_del_pedido flex flex-col justify-center ml-2">
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
    contador(pedido.fecha, pedido.id, tiempo);
}



export function contador(fecha, id, tiempo) {
    const pedidoFecha = new Date(fecha);
    const duracionMaxima = tiempo * 60; // 20 minutos en segundos
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
