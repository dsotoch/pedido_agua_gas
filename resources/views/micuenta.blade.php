@extends('layout-cuenta')
@section('logica')
    <div class="relative md:container">


        <div class="flex w-full h-full ">
            <div class="pl-[20px] flex space-x-2">

                <div class="max-w-[212px] w-[212px]">
                    <input id="mi_cuenta_input_buscar" class="rounded-3xl bg-transparent border-color-text border p-3"
                        type="search" autocomplete="off" name="first_name" value=""
                        placeholder="Buscar por nombres..." aria-label="Buscar por nombre">
                </div>
                <button type="button" class="bg-naranja text-white w-[48px] h-[50px] rounded-xl" disabled>
                    <i class="fa fa-search text-base font-bold"></i>
                </button>


            </div>
            <div class="text-base m-4 ">
                <p class="text-color-text"><b><span
                            class="font-bold cantidad_pedidos">{{ $pedidos->count() > 0 ? $pedidos->count() : 0 }}</span></b>
                    pedidos
                    por {{ $usuario->tipo == 'admin' || $usuario->tipo == 'repartidor' ? 'gestionar' : 'visualizar' }}.</p>
            </div>
        </div>
        <div id="mi_cuenta_contenedor_pedidos_super" class="w-full">

            <div class="flex flex-wrap  justify-center space-y-4 md:space-y-0 md:justify-start w-full" id="mi_cuenta_contenedor_pedidos">
                <!---Pedidos--->
                @if ($usuario->tipo == 'admin' || $usuario->tipo == 'repartidor')
                    @if ($pedidos->count() > 0)
                        @foreach ($pedidos as $pedido)
                            <div id="caja-{{ $pedido->id }}"
                                class="md:p-[15px] p-0 mi_cuenta_pedido w-4/5 md:w-[363px]">
                                <div
                                    class="flex-1 h-full w-full md:w-[333px] md:max-w-[333px] p-[20px] bg-color-tarjetas rounded-3xl text-color-titulos-entrega font-sans text-base">
                                    <div class="space-y-0 relative">
                                        <div class="flex justify-between">
                                            <div class="flex">
                                                <div class="flex">
                                                    <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                                                        <img src="{{ asset('imagenes/cajas/tag.svg') }}" alt="">
                                                    </div>
                                                    <div class=""><b class="p-2">#{{ $pedido->id }}</b></div>
                                                </div>
                                                <div class="flex ml-2">
                                                    <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                                                        <img src="{{ asset('imagenes/cajas/id.svg') }}" alt="">

                                                    </div>
                                                    <div class="pl-2"><b>{{ $pedido->id }}</b></div>
                                                </div>


                                            </div>

                                            <div class="flex space-x-2 ml-2 z-50">
                                                @if ($usuario->tipo == 'admin')
                                                    <!-- Botón Asignar Repartidor -->
                                                    <button title="Asignar repartidor" data-id="{{ $pedido->id }}"
                                                        class="btnasignarrepartidor z-50 flex items-center px-2 py-2  border-color-titulos-entrega text-color-titulos-entrega rounded shadow-md hover:scale-150 transform">
                                                        <i class=" fas fa-user-plus mr-2"></i>
                                                        <!-- Ícono de Font Awesome -->
                                                    </button>

                                                    <!-- Botón Acción Adicional -->
                                                    <button title="Editar pedido" data-id="{{ $pedido->id }}"
                                                        class="btn_editar_pedido flex items-center px-2 py-2  border-color-titulos-entrega text-color-titulos-entrega rounded shadow-md hover:scale-150 transform ">
                                                        <i class=" fas fa-edit  mr-2"></i>
                                                        <!-- Ícono de Font Awesome -->
                                                    </button>
                                                @else
                                                    <!-- Botón Confirmar Entrega y Pago -->
                                                    <button title="Confirmar entrega y pago" data-id="{{ $pedido->id }}"
                                                        class="btnconfirmarentrega z-50 flex items-center px-2 py-2  border-color-titulos-entrega text-color-titulos-entrega rounded shadow-md hover:scale-110 transform hover:bg-green-600 hover:text-white">
                                                        <i class=" fas fa-hands-helping mr-2"></i>
                                                        <!-- Ícono de manos ayudando -->
                                                    </button>

                                                    <!-- Botón Anular Pedido -->
                                                    <button title="Anular pedido" data-id="{{ $pedido->id }}"
                                                        class="btnanularpedido z-50 flex items-center px-2 py-2 border-color-titulos-entrega text-color-titulos-entrega rounded shadow-md hover:scale-110 transform hover:bg-red-600 hover:text-white">
                                                        <i class=" fas fa-times-circle mr-2"></i>
                                                        <!-- Ícono de anulación -->
                                                    </button>


                                                    @if ($pedido->estado == 'Pendiente')
                                                        <!-- Botón Acción Adicional -->
                                                        <button title="Aceptar Pedido" data-id="{{ $pedido->id }}"
                                                            class="boton_repartidor_aceptar_pedido flex items-center px-2 py-2  border-color-titulos-entrega text-color-titulos-entrega rounded shadow-md hover:bg-naranja  hover:text-white transform ">
                                                            <i class=" fas fa-check mr-2"></i>
                                                            <!-- Ícono de Font Awesome -->
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>

                                        </div>

                                        <div class="flex items-center ">
                                            <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                                                <img src="{{ asset('imagenes/cajas/persona.svg') }}" alt="">

                                            </div>
                                            <div class="flex flex-col justify-end h-[35px] ml-2">
                                                <p class="mi_cuenta_cliente">{{ $pedido->nombres }}</p>
                                            </div>

                                        </div>

                                        <div class=" flex items-center">
                                            <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                                                <img src="{{ asset('imagenes/cajas/celular.svg') }}" alt="">
                                            </div>
                                            <div class="flex flex-col justify-end h-[35px] ml-2">
                                                <p>{{ $pedido->celular }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center ">
                                            <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                                                <img src="{{ asset('imagenes/cajas/direccion.svg') }}" alt="">
                                            </div>
                                            <div class="flex flex-col justify-end h-[35px] ml-2">
                                                <p>{{ $pedido->direccion }}</p>
                                            </div>
                                        </div>
                                        <div class="flex  min-h-[35px] mt-1 mb-1">
                                            <div class="w-[18px] h-[18px] mt-2 mb-2 text-color-titulos-entrega opacity-80">
                                                <img src="{{ asset('imagenes/cajas/nota.svg') }}" alt="">
                                            </div>
                                            <p class="p-2"> {{ $pedido->nota }}</p>
                                        </div>

                                        <div class="flex items-center ">

                                            <div class="flex space-x-2">
                                                <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                                                    <img src="{{ asset('imagenes/cajas/calendario.svg') }}" alt="">

                                                </div>
                                                <div class="jet-listing-dynamic-field__content">
                                                    {{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y') }}
                                                </div>
                                            </div>
                                            <div class="flex ml-6 space-x-2 items-center ">
                                                <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80"> <img
                                                        src="{{ asset('imagenes/cajas/timer.svg') }}" alt="">
                                                </div>
                                                <div class="flex flex-col justify-center h-[35px] ml-2">
                                                    {{ \Carbon\Carbon::parse($pedido->fecha)->format(' h:i:s a') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div>

                                                <div id="contador-{{ $pedido->id }}"
                                                    class="text-start p-2 text-naranja text-base font-semibold"></div>
                                                <div id="contador_fin-{{ $pedido->id }}"
                                                    class=" hidden  flex-col justify-end h-[35px]  text-[13px]  ">
                                                    <p>El contador de 20
                                                        min. llegó a cero.</p>
                                                </div>
                                            </div>
                                            <script>
                                                (function() {
                                                    const pedidoFecha = new Date("{{ $pedido->fecha }}");
                                                    const duracionMaxima = 20 * 60; // 20 minutos en segundos

                                                    const interval = setInterval(() => {
                                                        const ahora = new Date();
                                                        const diferencia = Math.floor((ahora - pedidoFecha) / 1000); // Diferencia en segundos
                                                        const tiempoRestante = duracionMaxima - diferencia;

                                                        try {
                                                            if (tiempoRestante <= 0) {
                                                                clearInterval(interval); // Detener el contador
                                                                document.getElementById('contador-{{ $pedido->id }}').innerText = "";
                                                                document.getElementById('contador_fin-{{ $pedido->id }}').classList.remove('hidden');

                                                                return;
                                                            }


                                                            const minutos = Math.floor(tiempoRestante / 60);
                                                            const segundos = tiempoRestante % 60;

                                                            document.getElementById('contador-{{ $pedido->id }}').innerText =
                                                                `Tiempo Restante: ${minutos}m ${segundos}s`;
                                                        } catch (error) {

                                                        }
                                                    }, 1000);

                                                })
                                                ();
                                            </script>



                                        </div>

                                        <div class="flex items-center">
                                            <div
                                                class="w-[17px] h-[17px] min-h-[17px] min-w-[17px] text-color-titulos-entrega opacity-80">
                                                <img src="{{ asset('imagenes/cajas/carrito.svg') }}" alt="">


                                            </div>
                                            <div class="flex flex-col justify-center  ml-2">
                                                @foreach ($pedido->detalles as $item)
                                                    <p>{{ $item->producto->descripcion }} x {{ $item->cantidad }}</p>
                                                @endforeach
                                                @foreach ($pedido->entregaPromociones as $et)
                                                    <p> {{ $et->producto }} x {{ $et->cantidad }} Gratis.
                                                    </p>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                                                <img src="{{ asset('imagenes/cajas/dinero.svg') }}" alt="">

                                            </div>
                                            <div class="flex flex-col justify-center h-[35px] ml-2"><b
                                                    class="">S/{{ $pedido->total }}</b>
                                            </div>
                                        </div>
                                        <div class="flex  my-auto space-x-2">
                                            <div class="w-[25px] h-[25px] text-color-titulos-entrega ">
                                                <img src="{{ asset('imagenes/cajas/moto.svg') }}" alt="">


                                            </div>
                                            <div class="flex  justify-center h-[35px] space-x-1">
                                                <p>Delivery: </p>
                                                <b
                                                    class="estado_pedido_span">{{ $pedido->estado == 'En Camino' ? $pedido->estado . ' 🚚' : $pedido->estado }}</b>
                                            </div>
                                        </div>
                                        <div class="flex my-auto space-x-2">
                                            <div class="w-[25px] h-[25px]  text-color-titulos-entrega opacity-80">
                                                <img src="{{ asset('imagenes/cajas/caja_plata.svg') }}" alt="">

                                            </div>
                                            <div class="flex  justify-center h-[35px] "><b class="estado_metodo_pago">
                                                    {{ $pedido->pago ? 'Pagado ✅' : 'Pendiente de pago' }}
                                                </b>
                                            </div>
                                        </div>
                                        @if ($usuario->tipo == 'admin')
                                            <div class="flex items-center">
                                                <div class="w-[17px] h-[17px] text-color-text opacity-80"><i
                                                        class="fas fa-motorcycle w-full h-full"></i>
                                                </div>
                                                <div class="flex flex-col justify-center h-[35px] pl-4">
                                                    <span class="span_repartidor_nombre">
                                                        {{ $pedido->repartidor?->persona?->nombres ?? 'Repartidor no asignado' }}
                                                    </span>
                                                </div>

                                            </div>
                                        @endif
                                        <div>
                                            <p class="underline mt-2">Notas del pedido:</p>
                                            <p class="p-2">{{ $pedido->nota_interna }}</p>
                                        </div>

                                        @if (!empty($pedido->cupon))
                                            <div class="absolute -top-0 right-10 h-[50px]">
                                                <div
                                                    class="relative bg-transparent w-[150px]  h-[190px]  text-color-titulos-entrega group ">
                                                    <div>
                                                        <div
                                                            class="z-50 -top-10 bg-green-400 left-0 hidden absolute group-hover:flex  w-[250px] min-w-[250px] text-white p-2 rounded-md text-center">
                                                            <p class="relative text-[14px] leading-[19.6px] ">
                                                                <strong>¡Cupón Aplicado!</strong><br>
                                                                Aplica un descuento de S/{{ $pedido->descuento }}
                                                                equivalente al cupón
                                                                #{{ $pedido->cupon }}
                                                                en este pedido.
                                                            </p>
                                                            <div
                                                                class="absolute -bottom-[13px] left-[calc(50%-60px)] clip-v-shape h-[20px] w-[20px] bg-green-400 ">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <img src="{{ asset('imagenes/cajas/cupons.png') }}" alt=""
                                                        class="absolute top-[65px] left-[25px] w-[60px] h-[50px] ">

                                                </div>


                                            </div>
                                        @endif
                                        @if ($pedido->entregaPromociones->count() > 0)
                                            <div class="absolute -top-14 right-0">
                                                <div class="relative">
                                                    <div
                                                        class="bg-transparent w-[150px]  h-[190px]  text-color-titulos-entrega group ">
                                                        <div class="relative">
                                                            <div
                                                                class="z-50 top-0 left-0 hidden absolute group-hover:flex bg-tarjetas w-[250px] min-w-[250px] text-white p-2 rounded-md text-center">
                                                                <p class="relative text-[14px] leading-[19.6px] pb-4">
                                                                    <strong>¡Promo Producto Gratis!</strong><br>
                                                                    Aplica un descuento equivalente al costo de
                                                                    {{ $pedido->entregaPromociones->count() }} producto(s)
                                                                    incluido(s)
                                                                    en este pedido.
                                                                </p>
                                                                <div
                                                                    class="absolute -bottom-[13px] left-[calc(50%-10px)] clip-v-shape h-[20px] w-[20px] bg-color-titulos-entrega ">
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <img src="{{ asset('imagenes/cajas/botella.svg') }}"
                                                            alt="" class="absolute top-[120px] left-[104px]">

                                                    </div>

                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="w-full ">
                            <p class="text-center text-base  text-color-text">Aun No se ha Realizado Ningun Pedido.</p>
                        </div>
                    @endif
                @else
                    @if ($pedidos->count() > 0)
                        @foreach ($pedidos as $pedido)
                            <div id="caja-{{ $pedido->id }}" class="mi_cuenta_pedido w-5/6 md:w-[363px] p-[15px] md:max-w-[363px]">
                                <div
                                    class="flex-1 h-full w-full md:w-[333px] md:max-w-[333px] m-[15px] p-[20px] bg-color-tarjetas rounded-3xl text-color-titulos-entrega font-sans text-base">
                                    <div class="space-y-0 relative">
                                        <div class="flex justify-between">
                                            <div class="flex">
                                                <div class="flex">
                                                    <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                                                        <svg class="" aria-hidden="true" viewBox="0 0 512 512"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M0 252.118V48C0 21.49 21.49 0 48 0h204.118a48 48 0 0 1 33.941 14.059l211.882 211.882c18.745 18.745 18.745 49.137 0 67.882L293.823 497.941c-18.745 18.745-49.137 18.745-67.882 0L14.059 286.059A48 48 0 0 1 0 252.118zM112 64c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <div class=""><b class="p-2">#{{ $pedido->id }}</b></div>
                                                </div>
                                                <div class="flex ml-2">
                                                    <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                                                        <svg class="" aria-hidden="true" viewBox="0 0 512 512"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M294.75 188.19h-45.92V342h47.47c67.62 0 83.12-51.34 83.12-76.91 0-41.64-26.54-76.9-84.67-76.9zM256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm-80.79 360.76h-29.84v-207.5h29.84zm-14.92-231.14a19.57 19.57 0 1 1 19.57-19.57 19.64 19.64 0 0 1-19.57 19.57zM300 369h-81V161.26h80.6c76.73 0 110.44 54.83 110.44 103.85C410 318.39 368.38 369 300 369z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <div class="pl-2"><b>{{ $pedido->id }}</b></div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="flex items-center ">
                                            <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80">
                                                <svg aria-hidden="true" viewBox="0 0 448 512"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M313.6 304c-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 304 0 364.2 0 438.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-25.6c0-74.2-60.2-134.4-134.4-134.4zM400 464H48v-25.6c0-47.6 38.8-86.4 86.4-86.4 14.6 0 38.3 16 89.6 16 51.7 0 74.9-16 89.6-16 47.6 0 86.4 38.8 86.4 86.4V464zM224 288c79.5 0 144-64.5 144-144S303.5 0 224 0 80 64.5 80 144s64.5 144 144 144zm0-240c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="flex flex-col justify-end h-[35px] ml-2">
                                                <p class="mi_cuenta_cliente">{{ $pedido->nombres }}</p>
                                            </div>

                                        </div>

                                        <div class=" flex items-center">
                                            <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80"><svg
                                                    class="e-font-icon-svg e-fas-phone-alt" aria-hidden="true"
                                                    viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M497.39 361.8l-112-48a24 24 0 0 0-28 6.9l-49.6 60.6A370.66 370.66 0 0 1 130.6 204.11l60.6-49.6a23.94 23.94 0 0 0 6.9-28l-48-112A24.16 24.16 0 0 0 122.6.61l-104 24A24 24 0 0 0 0 48c0 256.5 207.9 464 464 464a24 24 0 0 0 23.4-18.6l24-104a24.29 24.29 0 0 0-14.01-27.6z">
                                                    </path>
                                                </svg></div>
                                            <div class="flex flex-col justify-end h-[35px] ml-2">
                                                <p>{{ $pedido->celular }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center ">
                                            <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80"><svg
                                                    class="e-font-icon-svg e-fas-map-marker-alt" aria-hidden="true"
                                                    viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z">
                                                    </path>
                                                </svg></div>
                                            <div class="flex flex-col justify-end h-[35px] ml-2">
                                                <p>{{ $pedido->direccion }}</p>
                                            </div>
                                        </div>
                                        <div class="flex  min-h-[35px] mt-1 mb-1">
                                            <div class="w-[18px] h-[18px] mt-2 mb-2"><svg
                                                    class="text-color-titulos-entrega opacity-80 w-full h-full"
                                                    aria-hidden="true" viewBox="0 0 288 512"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M112 316.94v156.69l22.02 33.02c4.75 7.12 15.22 7.12 19.97 0L176 473.63V316.94c-10.39 1.92-21.06 3.06-32 3.06s-21.61-1.14-32-3.06zM144 0C64.47 0 0 64.47 0 144s64.47 144 144 144 144-64.47 144-144S223.53 0 144 0zm0 76c-37.5 0-68 30.5-68 68 0 6.62-5.38 12-12 12s-12-5.38-12-12c0-50.73 41.28-92 92-92 6.62 0 12 5.38 12 12s-5.38 12-12 12z">
                                                    </path>
                                                </svg></div>
                                            <p class="p-2"> {{ $pedido->nota }}</p>
                                        </div>

                                        <div class="flex items-center ">

                                            <div class="flex space-x-2">
                                                <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80"><svg
                                                        class="e-font-icon-svg e-far-calendar-check" aria-hidden="true"
                                                        viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M400 64h-48V12c0-6.627-5.373-12-12-12h-40c-6.627 0-12 5.373-12 12v52H160V12c0-6.627-5.373-12-12-12h-40c-6.627 0-12 5.373-12 12v52H48C21.49 64 0 85.49 0 112v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm-6 400H54a6 6 0 0 1-6-6V160h352v298a6 6 0 0 1-6 6zm-52.849-200.65L198.842 404.519c-4.705 4.667-12.303 4.637-16.971-.068l-75.091-75.699c-4.667-4.705-4.637-12.303.068-16.971l22.719-22.536c4.705-4.667 12.303-4.637 16.97.069l44.104 44.461 111.072-110.181c4.705-4.667 12.303-4.637 16.971.068l22.536 22.718c4.667 4.705 4.636 12.303-.069 16.97z">
                                                        </path>
                                                    </svg></div>
                                                <div class="jet-listing-dynamic-field__content">
                                                    {{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y') }}
                                                </div>
                                            </div>
                                            <div class="flex ml-6 space-x-2 items-center ">
                                                <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80"><svg
                                                        class="" aria-hidden="true" viewBox="0 0 512 512"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z">
                                                        </path>
                                                    </svg></div>
                                                <div class="flex flex-col justify-center h-[35px] ml-2">
                                                    {{ \Carbon\Carbon::parse($pedido->fecha)->format(' h:i:s a') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div>

                                                <div id="contador-{{ $pedido->id }}"
                                                    class="text-start p-2 text-naranja text-base font-semibold"></div>
                                                <div id="contador_fin-{{ $pedido->id }}"
                                                    class=" hidden  flex-col justify-end h-[35px]  text-[13px]  ">
                                                    <p>El contador de 20
                                                        min. llegó a cero.</p>
                                                </div>
                                            </div>
                                            <script>
                                                (function() {
                                                    const pedidoFecha = new Date("{{ $pedido->fecha }}");
                                                    const duracionMaxima = 20 * 60; // 20 minutos en segundos

                                                    const interval = setInterval(() => {
                                                        const ahora = new Date();
                                                        const diferencia = Math.floor((ahora - pedidoFecha) / 1000); // Diferencia en segundos
                                                        const tiempoRestante = duracionMaxima - diferencia;

                                                        if (tiempoRestante <= 0) {
                                                            clearInterval(interval); // Detener el contador
                                                            document.getElementById('contador-{{ $pedido->id }}').innerText = '';
                                                            document.getElementById('contador_fin-{{ $pedido->id }}').classList.remove('hidden');

                                                            return;
                                                        }

                                                        const minutos = Math.floor(tiempoRestante / 60);
                                                        const segundos = tiempoRestante % 60;

                                                        document.getElementById('contador-{{ $pedido->id }}').innerText =
                                                            `Tiempo Restante: ${minutos}m ${segundos}s`;
                                                    }, 1000);
                                                })
                                                ();
                                            </script>



                                        </div>

                                        <div class="flex items-center">
                                            <div
                                                class="w-[17px] h-[17px] min-h-[17px] min-w-[17px] text-color-titulos-entrega opacity-80">
                                                <svg class="e-font-icon-svg e-fas-shopping-cart" aria-hidden="true"
                                                    viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M528.12 301.319l47.273-208C578.806 78.301 567.391 64 551.99 64H159.208l-9.166-44.81C147.758 8.021 137.93 0 126.529 0H24C10.745 0 0 10.745 0 24v16c0 13.255 10.745 24 24 24h69.883l70.248 343.435C147.325 417.1 136 435.222 136 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-15.674-6.447-29.835-16.824-40h209.647C430.447 426.165 424 440.326 424 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-22.172-12.888-41.332-31.579-50.405l5.517-24.276c3.413-15.018-8.002-29.319-23.403-29.319H218.117l-6.545-32h293.145c11.206 0 20.92-7.754 23.403-18.681z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="flex flex-col justify-center  ml-2">
                                                @foreach ($pedido->detalles as $item)
                                                    <p>{{ $item->producto->descripcion }} x {{ $item->cantidad }}</p>
                                                @endforeach
                                                @foreach ($pedido->entregaPromociones as $et)
                                                    <p> {{ $et->producto }} x {{ $et->cantidad }} Gratis.
                                                    </p>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-[17px] h-[17px] text-color-titulos-entrega opacity-80"><svg
                                                    class="" aria-hidden="true" viewBox="0 0 640 512"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M608 64H32C14.33 64 0 78.33 0 96v320c0 17.67 14.33 32 32 32h576c17.67 0 32-14.33 32-32V96c0-17.67-14.33-32-32-32zM48 400v-64c35.35 0 64 28.65 64 64H48zm0-224v-64h64c0 35.35-28.65 64-64 64zm272 176c-44.19 0-80-42.99-80-96 0-53.02 35.82-96 80-96s80 42.98 80 96c0 53.03-35.83 96-80 96zm272 48h-64c0-35.35 28.65-64 64-64v64zm0-224c-35.35 0-64-28.65-64-64h64v64z">
                                                    </path>
                                                </svg></div>
                                            <div class="flex justify-between h-[35px] w-full md:w-3/4 items-center ml-2">
                                                <b>S/{{ $pedido->total }}</b>
                                                @if ($pedido->pago)
                                                    <div class="flex">
                                                        @if ($pedido->metodo == 'yape')
                                                            <img src="{{ asset('imagenes/Yape-color.svg') }}"
                                                                alt=""
                                                                class="w-[30px] h-[30px] object-contain mr-2">
                                                            <b>Yape</b>
                                                        @else
                                                            <img src="{{ asset('imagenes/efectivo.svg') }}"
                                                                alt=""
                                                                class="w-[30px] h-[30px] object-contain mr-2">
                                                            <b>Efectivo</b>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex  my-auto space-x-2">
                                            <div class="w-[25px] h-[25px] text-color-titulos-entrega ">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" id="Nautik_Delivery"
                                                    x="0px" y="0px" width="25px" height="25px"
                                                    viewBox="0 0 383.95 378.328" xml:space="preserve">
                                                    <path fill="#293241"
                                                        d="M383.37,312.817c-4.689-23.21-18.352-38.301-40.99-45.171c-2.51-0.76-3.529-1.979-3.32-4.5 c0.102-1.238,0.012-2.5,0.012-3.75c-0.012-25.068,0-50.148-0.012-75.22c0.672,0.24,1.361,0.45,2.063,0.64 c15.359,4.24,30.49-2.04,33.789-14.02c3.311-11.98-6.469-25.12-21.83-29.36c-4.82-1.33-9.619-1.63-14.039-1.03 c0.01-3.95,0-7.91,0-11.86c-0.01-8.47-4.281-12.76-12.641-12.77c-16.619-0.02-33.24-0.01-49.859-0.01c-1.881,0-3.74,0-5.53,0.73 c-4.99,2.02-7.841,7.03-6.99,12.37c0.91,5.67,5.171,9.47,11.261,9.53c12.239,0.13,24.489,0.13,36.74,0 c3.17-0.03,4.42,0.85,4.398,4.23c-0.109,24.87-0.1,49.741-0.02,74.61c0.01,2.898-0.811,4.061-3.869,4.54 c-22.191,3.47-41.361,13.159-57.102,29.13c-15.35,15.58-24.699,34.399-28.04,56.061c-0.479,3.141-1.761,3.83-4.601,3.789 c-10.25-0.119-20.5-0.159-30.74,0.021c-3.43,0.061-4.54-1.029-4.52-4.48c0.09-12,0.12-24,0.1-36c-0.38-9-0.55-13.81-13.21-13.81 H8.92c-6.09,0.56-8.59,3.72-8.92,8.31c0,16.66,0,61.041,0,61.041c3.09,5.89,8.01,8.01,14.56,7.699c6.85-0.33,13.74-0.029,20.61-0.09 c2.19-0.021,3.35,0.721,4.02,2.949c6.93,23.12,22.31,36.939,46.01,41.5c0.36,0.07,0.7,0.279,1.04,0.432h15 c7.57-1.701,15-3.771,21.69-7.932c12.54-7.789,21-18.721,25-32.92c0.89-3.141,2.35-4.09,5.49-4.068 c27.24,0.1,54.48,0.059,81.72,0.049c9.83,0,13.521-3.539,13.979-13.379c0.95-20.142,8.01-37.812,21.971-52.392 c11.271-11.761,25.09-19.229,41.07-22.63c2.898-0.609,4.459-0.21,4.25,3.352c-0.271,4.729-0.23,9.5-0.01,14.238 c0.148,3.07-1.08,4.29-3.951,5.189c-22.57,7.08-36.439,22.24-40.369,45.61c-4.871,29,13.688,56.431,42.34,63.5 c1.689,0.421,3.359,0.921,5.039,1.38h16.5c5.35-1.66,10.881-2.729,15.961-5.26c16.209-8.061,26.449-20.881,30.811-38.421 c0.359-1.449,0.811-2.88,1.221-4.31v-15.75C383.75,313.998,383.491,313.417,383.37,312.817z M93.74,355.658 c-18.16,0-33.43-15.191-33.6-33.421c-0.16-18.221,15.32-33.771,33.61-33.771c18.3,0.01,33.76,15.561,33.58,33.779 C127.16,340.487,111.9,355.658,93.74,355.658z M360.6,327.917c-2.229,13.949-13.6,25.039-27.83,27.14 c-16.17,2.399-31.6-6.851-36.67-21.96c-5.301-15.79,1.189-32.561,15.641-40.46c4.131-2.25,4.58-2.011,4.6,2.521 c0.01,4.37,0,8.739,0,13.12c0.01,4.619-0.068,9.239,0.031,13.859c0.139,6.61,5.029,11.36,11.469,11.28 c6.301-0.07,11.111-4.74,11.201-11.181c0.119-9.239,0.01-18.479,0.049-27.729c0.021-3.75,0.451-3.971,3.83-2.271 C356.02,298.817,363,312.908,360.6,327.917z">
                                                    </path>
                                                    <path id="silueta_galón" fill="#293241"
                                                        d="M162.296,89.16c0-0.19-0.01-0.38-0.02-0.57c-0.09-1.62-0.66-4.41-3.07-7.6 c-0.23-4.34-1.31-10.54-5.23-15.29c-1.9-2.96-4.97-4.99-6.98-6.32c-6.17-4.08-16.96-9.59-32.07-16.38l-1.28-0.45l0.01-0.02h-0.01 l0.02-0.87c0.05-2.68-0.98-5.27-2.86-7.19l-0.09-0.09c0.95-1.52,1.52-3.31,1.54-5.24l0.19-17.99c0.01-1.68-0.39-3.34-1.18-4.82 c-0.67-1.24-2.24-3.57-5.29-5.18c-1.44-0.75-3.04-1.15-4.66-1.15h-23c-1.27,0-2.54,0.25-3.73,0.72c-1.43,0.58-4.95,2.36-6.68,6.38 c-0.55,1.27-0.83,2.65-0.81,4.04l0.19,18.1c0.02,1.86,0.54,3.59,1.44,5.08c-0.17,0.18-0.35,0.36-0.51,0.56 c-1.82,2.17-2.68,4.71-2.31,7.41l-29.85,14.7c-1.77,0.86-12.32,6.49-15.19,19.57c-0.1,0.49-0.17,0.98-0.2,1.47 c-0.09,1.229-0.13,2.28-0.15,3.18c-1.56,1.78-3.26,4.84-3.06,9.59v9.29c0,0.17,0,0.34,0.01,0.51c0.13,2.51,1.03,5.58,3.05,8.09 l0.03,69.61c-1.45,1.76-3.09,4.65-3.09,8.669v10.791c0,1.021,0.16,2.039,0.47,3.021c0.43,1.35,1.2,3.229,2.53,4.959v8.09 c-1.38,1.83-3,4.92-3,9.32v4.479c0,1.062,0.2,10.451,7.19,18.111c1.79,2.149,8.44,9.129,20.15,10.779c0.46,0.061,0.92,0.1,1.39,0.1 h88.45c0.15,0,0.29-0.01,0.43-0.01c10.94-0.47,27.23-9.939,27.23-34.119h-0.01c-0.04-2.5-1.04-4.48-1.48-5.361 c-0.29-0.58-0.78-1.539-1.59-3.108v-8.472c1.68-2.079,2.86-5,2.83-8.868l0.24-9.421c0.01-0.39,0-0.779-0.03-1.17 c-0.22-2.35-1.19-5.37-3.21-8.12c-0.01-1.13,0-2.72,0.04-4.92v-0.2l-0.09-63.98c1.58-1.91,3.3-5.03,3.3-9.54V89.16L162.296,89.16z  M142.296,97.27c-1.86,2.18-2.65,4.43-2.9,5.28c-0.27,0.92-0.4,1.87-0.4,2.83l0.09,67.4c-0.17,9.54-0.22,13.13,3.12,16.53 c0.01,0.01,0.01,0.01,0.02,0.02l-0.13,5.021c-1.98,2.6-2.59,5.408-2.76,6.5c-0.08,0.51-0.12,1.02-0.12,1.54v14.069 c0,1.609,0.38,3.189,1.13,4.619c0.85,1.641,1.45,2.812,1.88,3.631c-0.66,9.75-5.62,11.59-8.05,11.91h-87.2 c-4.11-0.761-6.4-3.061-6.86-3.57c-0.24-0.34-0.3-0.369-0.64-0.729c-1.74-1.851-1.99-4.47-2.02-4.851v-2.76 c1.77-2.451,2.5-5.08,2.78-6.351c0.14-0.69,0.22-1.38,0.22-2.08v-13.01c0.05-3.86-1.61-6.84-3-8.649v-5.17 c1.62-2.14,2.4-4.36,2.71-5.47c0.25-0.89,0.38-1.81,0.38-2.73l-0.03-75.17c0.03-3.51-1.32-6.48-3.06-8.66v-4.93 c2.52-3.1,3.09-5.97,3.17-7.63c0.03-0.68,0-1.36-0.1-2.03c-0.03-0.5-0.01-1.57,0.04-2.61c0.52-1.71,1.52-2.99,2.42-3.85 c0.9-0.87,1.72-1.34,1.87-1.42l31.19-15.36c7.026-4.057,21.296-2.092,27.8,0.36c1.224,0.461,2.29,1.15,3.38,1.52 c22.05,9.93,28.57,14.34,30.22,15.63c0.33,0.47,0.69,0.91,1.09,1.33c0.5,0.92,0.84,3.65,0.69,5.57c-0.28,3.06,0.86,6.07,3.07,8.17 V97.27L142.296,97.27z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="flex  items-center justify-center h-[35px] space-x-1">
                                                <p>Delivery:</p>
                                                <b class="estado_pedido_span">
                                                    {{ $pedido->estado == 'En Camino' ? $pedido->estado . '🚚' : $pedido->estado }}
                                                </b>

                                            </div>
                                        </div>
                                        <div class="flex my-auto space-x-2">
                                            <div class="w-[25px] h-[25px]  text-color-titulos-entrega opacity-80"><svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px"
                                                    y="0px" width="25px" height="25px" viewBox="0 0 388.638 382.53"
                                                    xml:space="preserve" class="text-color-titulos-entrega">
                                                    <g>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#293241"
                                                            d="M230.17,320.31V142.75h0.01c0-6.3,0.011-12.6-0.01-18.9  c-0.05-19.36-12.65-32.01-32-32.15c-3.74-0.03-7.48,0.1-11.21-0.1c-0.88-0.05-2.45-1.16-2.46-1.8c-0.16-9.56-0.11-19.12-0.11-29.04  c1.68-0.08,3-0.2,4.31-0.2c10.84-0.02,21.67,0.01,32.5-0.02c6.22-0.02,8.95-2.67,8.96-8.82c0-8.09,0.45-16.24-0.34-24.26  c-1.4-14.19-10.67-23.82-24.63-26.79c-0.601-0.12-1.15-0.44-1.721-0.67H74.22c-1.42,0.41-2.83,0.83-4.25,1.22  c-13.62,3.8-22.3,15.44-22.37,30.33c-0.09,18.41-0.03,36.82-0.03,55.23v4.91H36.08C16.91,91.7,7.24,99.04,2.17,117.44  c-0.07,0.22-0.28,0.4-0.42,0.6v238.34c0.31,0.91,0.67,1.8,0.93,2.71c3.42,12.141,11.18,19.78,23.52,22.67  c0.83,0.19,1.63,0.51,2.44,0.771h174.83c1.311-0.36,2.601-0.75,3.91-1.09c13.58-3.521,22.67-15.37,22.771-30.07  c0.069-10.351,0.06-20.71,0.05-31.061H230.17z M184.76,29.02c0.51-7.89,6.48-13.5,14.44-13.91c7.64-0.39,14.399,4.59,15.47,12.23  c0.76,5.38,0.521,10.91,0.45,16.37c-0.01,0.66-1.75,1.81-2.73,1.87c-4.1,0.21-8.21,0.08-12.319,0.08  c-4.11,0.01-8.21-0.06-12.32,0.04c-2.1,0.05-3.26-0.45-3.17-2.87C184.73,38.23,184.47,33.61,184.76,29.02z M62.3,32.02  c0-10.82,6.38-17.15,17.19-17.16c30.01-0.01,60.02,0,90.02,0h4.32c-5.57,9.52-4.04,19.49-4.08,29.22  c-0.11,29.63-0.1,59.27,0.04,88.9c0.02,3.57-0.94,4.59-4.55,4.57c-32.87-0.13-65.74-0.12-98.61-0.01  c-3.33,0.01-4.44-0.75-4.43-4.29C62.34,99.51,62.27,65.77,62.3,32.02z M215.28,125.38c0.01,74.44,0.01,148.89,0,223.33  c0,13.17-5.681,18.84-18.88,18.83c-53.78-0.06-107.561-0.13-161.331-0.2c-12.22-0.02-18.11-5.89-18.12-18.08  c-0.01-37.35,0-74.689,0-112.04c0-37.72,0.02-75.44-0.02-113.16c0-5.5,1.11-10.74,5.91-13.77c2.97-1.89,6.81-2.7,10.38-3.26  c3.4-0.53,6.96,0.04,10.43-0.16c2.87-0.17,3.59,0.99,3.56,3.69c-0.12,10.71-0.06,21.42-0.04,32.12c0.02,7.05,2.89,9.91,10.04,9.9  c39.21-0.07,78.42-0.15,117.63-0.24c6.96-0.01,9.72-2.75,9.74-9.74c0.05-10.71,0.12-21.42-0.05-32.12  c-0.05-3.16,1.04-4.14,4.08-3.96c3.84,0.23,7.72-0.04,11.57,0.12c8.431,0.35,14.391,6.21,15.04,14.63  C215.33,122.63,215.28,124.01,215.28,125.38z">
                                                        </path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#293241"
                                                            d="M55.057,274.141c2.49,0.005,4.991-0.15,7.466,0.038  c4.741,0.36,7.911,3.631,7.875,7.873c-0.034,4.099-3.039,7.452-7.615,7.714c-5.209,0.297-10.464,0.3-15.672-0.023  c-4.609-0.285-7.457-3.601-7.426-7.804c0.032-4.364,3.073-7.443,7.904-7.773C50.067,273.996,52.567,274.135,55.057,274.141z">
                                                        </path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#293241"
                                                            d="M55.024,335.561c-2.613,0-5.239,0.165-7.836-0.037  c-4.55-0.354-7.48-3.494-7.502-7.743c-0.022-4.12,2.609-7.351,7.091-7.647c5.444-0.361,10.949-0.37,16.392-0.002  c4.587,0.312,7.451,3.873,7.212,7.969c-0.243,4.154-3.337,7.161-7.893,7.45c-2.479,0.156-4.976,0.028-7.464,0.028  C55.024,335.572,55.024,335.566,55.024,335.561z">
                                                        </path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#293241"
                                                            d="M177.011,289.35c-2.852,0-5.728,0.226-8.552-0.055  c-3.99-0.395-6.543-3.437-6.55-7.287c-0.006-3.683,2.354-6.962,6.168-7.212c5.92-0.387,11.902-0.383,17.823-0.001  c3.806,0.245,6.207,3.535,6.214,7.189c0.008,3.821-2.594,6.747-6.545,7.358c-0.368,0.057-0.739,0.122-1.109,0.125  c-2.483,0.011-4.965,0.006-7.448,0.006C177.012,289.432,177.011,289.391,177.011,289.35z">
                                                        </path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#293241"
                                                            d="M115.849,289.37c-2.728,0-5.472,0.196-8.178-0.046  c-4.092-0.366-6.576-3.176-6.665-7.111c-0.091-4.008,2.144-7.164,6.238-7.446c5.792-0.398,11.649-0.395,17.443-0.018  c4.057,0.266,6.411,3.486,6.345,7.396c-0.067,4.017-2.732,6.786-6.999,7.267c-0.369,0.042-0.743,0.067-1.114,0.068  c-2.357,0.006-4.714,0.003-7.071,0.003C115.849,289.445,115.849,289.407,115.849,289.37z">
                                                        </path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#293241"
                                                            d="M115.648,59.37c-5.949,0-11.935,0.196-17.836-0.046  c-8.926-0.366-14.342-3.176-14.537-7.111c-0.198-4.008,4.677-7.164,13.605-7.446c12.632-0.398,25.408-0.395,38.045-0.018  c8.849,0.266,13.982,3.486,13.839,7.396c-0.146,4.017-5.958,6.786-15.266,7.267c-0.805,0.042-1.62,0.067-2.429,0.068  c-5.141,0.006-10.281,0.003-15.422,0.003C115.648,59.445,115.648,59.407,115.648,59.37z">
                                                        </path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#293241"
                                                            d="M115.648,88.704c-5.949,0-11.935,0.196-17.836-0.046  c-8.926-0.366-14.342-3.176-14.537-7.111c-0.198-4.008,4.677-7.164,13.605-7.446c12.632-0.398,25.408-0.395,38.045-0.018  c8.849,0.266,13.982,3.486,13.839,7.396c-0.146,4.017-5.958,6.786-15.266,7.267c-0.805,0.042-1.62,0.067-2.429,0.068  c-5.141,0.006-10.281,0.003-15.422,0.003C115.648,88.779,115.648,88.741,115.648,88.704z">
                                                        </path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#293241"
                                                            d="M176.964,320.352c2.607-0.003,5.22-0.115,7.819,0.023  c4.262,0.228,7.072,2.99,7.313,6.977c0.228,3.74-2.212,7.329-6.15,7.618c-5.914,0.436-11.905,0.44-17.821,0.022  c-3.975-0.281-6.377-3.768-6.201-7.568c0.186-4.002,2.968-6.804,7.221-7.043C171.743,320.234,174.356,320.354,176.964,320.352z">
                                                        </path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#293241"
                                                            d="M115.969,320.349c2.606-0.002,5.217-0.112,7.816,0.023  c4.152,0.219,6.88,2.797,7.225,6.611c0.366,4.049-1.949,7.669-6.074,7.986c-5.908,0.454-11.902,0.462-17.811,0.016  c-4.201-0.317-6.415-3.764-6.103-7.933c0.288-3.843,2.977-6.447,7.131-6.675C110.752,320.236,113.364,320.352,115.969,320.349z">
                                                        </path>
                                                    </g>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" fill="#293241"
                                                        d="M187.676,212.25c0,6.627-5.373,12-12,12H56.361 c-6.627,0-12-5.373-12-12v-22.5c0-6.627,5.373-12,12-12h119.315c6.627,0,12,5.373,12,12V212.25z">
                                                    </path>
                                                    <g>
                                                        <path fill="#293241"
                                                            d="M291.718,127.335c-23.3,0-44.699,8.26-61.43,22.01v20.79c15.01-17.04,36.98-27.8,61.43-27.8  c45.17,0,81.92,36.74,81.92,81.91s-36.75,81.92-81.92,81.92c-24.439,0-46.42-10.76-61.43-27.8v20.779  c16.73,13.761,38.13,22.021,61.43,22.021c53.44,0,96.92-43.479,96.92-96.92C388.638,170.805,345.158,127.335,291.718,127.335z">
                                                        </path>
                                                    </g>
                                                    <g>
                                                        <path fill="#293241"
                                                            d="M289.723,243.598c0-2.606-0.923-4.638-2.768-6.094c-1.846-1.455-5.087-2.962-9.725-4.52  c-4.639-1.557-8.43-3.063-11.375-4.52c-9.581-4.705-14.371-11.172-14.371-19.398c0-4.096,1.193-7.71,3.58-10.842  c2.387-3.131,5.764-5.568,10.131-7.313c4.367-1.743,9.275-2.615,14.727-2.615c5.314,0,10.08,0.948,14.295,2.844  c4.215,1.896,7.49,4.596,9.826,8.1s3.504,7.508,3.504,12.01h-17.773c0-3.013-0.923-5.349-2.768-7.008  c-1.846-1.658-4.342-2.488-7.49-2.488c-3.183,0-5.696,0.703-7.541,2.107c-1.846,1.405-2.768,3.191-2.768,5.357  c0,1.896,1.016,3.614,3.047,5.154c2.031,1.541,5.603,3.132,10.715,4.773c5.111,1.643,9.31,3.411,12.594,5.307  c7.989,4.604,11.984,10.952,11.984,19.043c0,6.467-2.438,11.545-7.313,15.234c-4.875,3.69-11.562,5.535-20.059,5.535  c-5.992,0-11.418-1.074-16.275-3.225c-4.858-2.149-8.515-5.095-10.969-8.836c-2.455-3.74-3.682-8.049-3.682-12.924h17.875  c0,3.961,1.023,6.881,3.072,8.76c2.048,1.879,5.374,2.818,9.979,2.818c2.945,0,5.272-0.635,6.982-1.904  C288.867,247.686,289.723,245.9,289.723,243.598z">
                                                        </path>
                                                        <path fill="#293241"
                                                            d="M321.766,269.598h-12.137l25.238-80.285h12.188L321.766,269.598z">
                                                        </path>
                                                    </g>
                                                </svg></div>
                                            <div class="flex  justify-center h-[35px] "><b class="">
                                                    {{ $pedido->pago ? 'Pagado ✅' : 'Pendiente de pago' }}
                                                </b>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="underline">Notas del pedido:</p>
                                            <p class="p-2">{{ $pedido->nota_interna }}</p>

                                        </div>
                                        @if ($pedido->entregaPromociones->count() > 0)
                                            <div class="absolute -top-14 right-0">
                                                <div class="relative">
                                                    <div
                                                        class="bg-transparent w-[150px]  h-[190px]  text-color-titulos-entrega group ">
                                                        <div class="relative">
                                                            <div
                                                                class="z-10 top-0 left-0 hidden absolute group-hover:flex bg-tarjetas w-[250px] min-w-[250px] text-white p-2 rounded-md text-center">
                                                                <p class="relative text-[14px] leading-[19.6px] pb-4">
                                                                    <strong>¡Promo Producto Gratis!</strong><br>
                                                                    Aplica un descuento equivalente al costo de
                                                                    {{ $pedido->entregaPromociones->count() }} producto(s)
                                                                    incluido(s)
                                                                    en este pedido.
                                                                </p>
                                                                <div
                                                                    class="absolute -bottom-[13px] left-[calc(50%-10px)] clip-v-shape h-[20px] w-[20px] bg-color-titulos-entrega ">
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            id="Nautik_Delivery" x="0px" y="0px" width="40px"
                                                            height="40px" class="absolute top-[120px] left-[104px]"
                                                            viewBox="17.44 0 144.856 256.619" xml:space="preserve">
                                                            <path id="silueta_galón" fill="#FF6600"
                                                                d="M162.296,89.16c0-0.19-0.01-0.38-0.02-0.57c-0.09-1.62-0.66-4.41-3.07-7.6 c-0.23-4.34-1.31-10.54-5.23-15.29c-1.9-2.96-4.97-4.99-6.98-6.32c-6.17-4.08-16.96-9.59-32.07-16.38l-1.28-0.45l0.01-0.02h-0.01 l0.02-0.87c0.05-2.68-0.98-5.27-2.86-7.19l-0.09-0.09c0.95-1.52,1.52-3.31,1.54-5.24l0.19-17.99c0.01-1.68-0.39-3.34-1.18-4.82 c-0.67-1.24-2.24-3.57-5.29-5.18c-1.44-0.75-3.04-1.15-4.66-1.15h-23c-1.27,0-2.54,0.25-3.73,0.72c-1.43,0.58-4.95,2.36-6.68,6.38 c-0.55,1.27-0.83,2.65-0.81,4.04l0.19,18.1c0.02,1.86,0.54,3.59,1.44,5.08c-0.17,0.18-0.35,0.36-0.51,0.56 c-1.82,2.17-2.68,4.71-2.31,7.41l-29.85,14.7c-1.77,0.86-12.32,6.49-15.19,19.57c-0.1,0.49-0.17,0.98-0.2,1.47 c-0.09,1.229-0.13,2.28-0.15,3.18c-1.56,1.78-3.26,4.84-3.06,9.59v9.29c0,0.17,0,0.34,0.01,0.51c0.13,2.51,1.03,5.58,3.05,8.09 l0.03,69.61c-1.45,1.76-3.09,4.65-3.09,8.669v10.79c0,1.021,0.16,2.039,0.47,3.021c0.43,1.35,1.2,3.229,2.53,4.959v8.09 c-1.38,1.83-3,4.92-3,9.32v4.479c0,1.062,0.2,10.451,7.19,18.111c1.79,2.149,8.44,9.129,20.15,10.779c0.46,0.061,0.92,0.1,1.39,0.1 h88.45c0.15,0,0.29-0.01,0.43-0.01c10.94-0.47,27.23-9.939,27.23-34.119h-0.01c-0.04-2.5-1.04-4.48-1.48-5.361 c-0.29-0.58-0.78-1.539-1.59-3.108v-8.472c1.68-2.079,2.86-5,2.83-8.868l0.24-9.421c0.01-0.39,0-0.779-0.03-1.17 c-0.22-2.35-1.19-5.37-3.21-8.12c-0.01-1.13,0-2.72,0.04-4.92v-0.2l-0.09-63.98c1.58-1.91,3.3-5.03,3.3-9.54V89.16L162.296,89.16z  M142.296,97.27c-1.86,2.18-2.65,4.43-2.9,5.28c-0.27,0.92-0.4,1.87-0.4,2.83l0.09,67.4c-0.17,9.54-0.22,13.13,3.12,16.53 c0.01,0.01,0.01,0.01,0.02,0.02l-0.13,5.021c-1.98,2.6-2.59,5.408-2.76,6.5c-0.08,0.51-0.12,1.02-0.12,1.54v14.069 c0,1.609,0.38,3.189,1.13,4.619c0.85,1.641,1.45,2.812,1.88,3.631c-0.66,9.75-5.62,11.59-8.05,11.91h-87.2 c-4.11-0.761-6.4-3.061-6.86-3.57c-0.24-0.34-0.3-0.369-0.64-0.729c-1.74-1.851-1.99-4.47-2.02-4.851v-2.76 c1.77-2.451,2.5-5.08,2.78-6.351c0.14-0.69,0.22-1.38,0.22-2.08v-13.01c0.05-3.86-1.61-6.84-3-8.649v-5.17 c1.62-2.14,2.4-4.36,2.71-5.47c0.25-0.89,0.38-1.81,0.38-2.73l-0.03-75.17c0.03-3.51-1.32-6.48-3.06-8.66v-4.93 c2.52-3.1,3.09-5.97,3.17-7.63c0.03-0.68,0-1.36-0.1-2.03c-0.03-0.5-0.01-1.57,0.04-2.61c0.52-1.71,1.52-2.99,2.42-3.85 c0.9-0.87,1.72-1.34,1.87-1.42l31.19-15.36c7.026-4.057,21.296-2.092,27.8,0.36c1.224,0.461,2.29,1.15,3.38,1.52 c22.05,9.93,28.57,14.34,30.22,15.63c0.33,0.47,0.69,0.91,1.09,1.33c0.5,0.92,0.84,3.65,0.69,5.57c-0.28,3.06,0.86,6.07,3.07,8.17 V97.27L142.296,97.27z">
                                                            </path>
                                                            <path fill="#FF6600"
                                                                d="M125.009,108.926c-1.344,2.028-2.281,4.093-2.61,6.331c-0.412,2.812-0.833,5.655-0.846,8.486 c-0.078,16.523-0.064,33.048-0.018,49.573c0.011,3.856,0.209,7.729,1.541,11.413c0.48,1.329,1.167,2.582,1.789,3.933 c-0.188,0.011-0.46,0.039-0.733,0.039c-3.774,0.003-7.55-0.034-11.324,0.013c-3.605,0.043-6.301-1.342-8.415-4.369 c-14.503-20.778-29.089-41.497-43.65-62.234c-0.187-0.266-0.38-0.526-0.795-0.775c0,0.325,0.002,0.65,0,0.975 c-0.072,10.063,1.661,19.86,4.755,29.385c2.636,8.116,5.682,16.098,8.491,24.158c0.828,2.374,1.518,4.799,2.166,7.229 c0.208,0.779,0.181,1.664,0.076,2.476c-0.191,1.479-1.154,2.376-2.554,2.769c-0.681,0.191-1.398,0.344-2.101,0.348 c-5.347,0.027-10.694,0.015-15.93,0.015c0.654-1.616,1.434-3.211,1.946-4.888c1.013-3.32,1.22-6.777,1.228-10.218 c0.039-16.792,0.038-33.583-0.007-50.376c-0.009-3.561-0.259-7.128-1.554-10.508c-0.485-1.263-1.127-2.466-1.738-3.784 c0.168,0,0.357,0,0.546,0c3.626,0,7.252-0.027,10.877,0.011c2.396,0.025,4.793,0.238,6.733,1.842 c0.926,0.766,1.771,1.686,2.462,2.67c14.375,20.468,28.724,40.955,43.078,61.437c0.283,0.403,0.578,0.798,1.075,1.142 c0.021-0.306,0.053-0.611,0.062-0.917c0.244-8.319-1.036-16.426-3.251-24.427c-2.56-9.244-6.078-18.148-9.418-27.118 c-1.125-3.022-2.042-6.123-3.037-9.193c-0.145-0.447-0.214-0.925-0.271-1.396c-0.222-1.804,0.561-3.076,2.3-3.617 c0.729-0.227,1.51-0.402,2.268-0.406C113.706,108.91,119.263,108.926,125.009,108.926z">
                                                            </path>
                                                        </svg>

                                                    </div>

                                                </div>
                                            </div>
                                        @endif
                                        @if (!empty($pedido->cupon))
                                            <div class="absolute -top-0 right-10 h-[50px]">
                                                <div
                                                    class="relative bg-transparent w-[150px]  h-[190px]  text-color-titulos-entrega group ">
                                                    <div>
                                                        <div
                                                            class="z-50 -top-10 bg-green-400 left-0 hidden absolute group-hover:flex  w-[250px] min-w-[250px] text-white p-2 rounded-md text-center">
                                                            <p class="relative text-[14px] leading-[19.6px] ">
                                                                <strong>¡Cupón Aplicado!</strong><br>
                                                                Aplica un descuento de S/{{ $pedido->descuento }}
                                                                equivalente al cupón
                                                                #{{ $pedido->cupon }}
                                                                en este pedido.
                                                            </p>
                                                            <div
                                                                class="absolute -bottom-[13px] left-[calc(50%-60px)] clip-v-shape h-[20px] w-[20px] bg-green-400 ">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <img src="{{ asset('imagenes/cajas/cupons.png') }}" alt=""
                                                        class="absolute top-[55px] left-[25px] w-[60px] h-[50px] ">

                                                </div>


                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="w-full ">
                            <p class="text-center text-base  text-color-text">Aun No Has Realizado Ningun Pedido.</p>
                        </div>
                    @endif


                @endif


            </div>
        </div>

        <div class="w-full justify-center hidden pt-10" id="mi_cuenta_mensaje_no_resultados">
            <p class="flex justify-center w-full text-base text-color-titulos-entrega">No existen Resultados para la
                Busqueda.
            </p>
        </div>

        @include('modales_mi_cuenta', ['empresa' => $empresa])
    </div>

@endsection
