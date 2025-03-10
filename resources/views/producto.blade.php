<div class="pt-[20px]  bg-color-fondo-productos flex flex-col  w-full  items-center space-x-2 ">
    <input type="text" name="" id="empresa_id_para_cupon" hidden value="{{ $empresa ? $empresa->id : '' }}">

    @if ($productos->count() > 0)
        <div id="contener_producto_item" class="flex flex-col w-full pl-[10px] pr-[10px]">
            <div class="flex flex-col mx-auto h-auto text-color-text  items-center justify-center rounded-3xl  bg-transparent text-center w-full md:w-[520px]"
                data-index="">
                @foreach ($productos as $item)
                    <!---Productos Antiguos-->
                    <div class="w-full p-1  bg-white mt-[20px] rounded-[10px] flex ">
                        <div class="w-[79px] h-[145px] my-auto flex items-center">
                            <img src="{{ asset('storage/' . $item->imagen) }}" alt="" class=" object-contain">
                        </div>
                        <div class="flex flex-col w-full   ml-4 space-y-2 padre_productos ">
                            <div class="md:flex grid items-center ">
                                <div class="w-full  md:w-1/2 pt-4  text-start">
                                    <p class="producto_descripcion  text-base ">
                                        {{ $item->nombre }}</p>
                                </div>
                                <div
                                    class="flex w-full  md:pl-0 md:pr-0 pr-[10px] justify-between  md:w-1/2 items-center space-x-2 md:space-x-1  pt-4">
                                    <div class="w-1/2">
                                        <p class="text-center"> <span
                                                class="text-[15px] text-center  precioprincipal">S/{{ number_format($item->precio ?? 0, 2, '.', '') }}</span>
                                        </p>
                                    </div>
                                    <div class="item-container p-1 opacity-80 justify-center space-x-2  border-color-text rounded-[3px] flex w-[76px] h-[37px] max-h-[37px] items-center  border "
                                        data-producto-id="{{ $item->id }}">
                                        <p class="hidden precionormal">
                                            {{ number_format($item->precio ?? 0, 2, '.', '') }}
                                        </p>
                                        <p class="hidden promociones">{{ $item->promociones }}</p>
                                        <!-- Botón de disminución -->
                                        <button
                                            class=" btn-producto-menos w-[20px] h-[20px] text-center items-center  opacity-80 text-color-text  ">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                        <!-- Input de cantidad con flex-grow y max-w-xs -->
                                        <div class="w-auto flex items-center text-center">
                                            <p name="cantidad" readonly
                                                class="cantidad text-[15px] w-full  text-color-text opacity-80  p-0 m-0 text-center">
                                                0</p>

                                        </div>

                                        <!-- Botón de aumento -->
                                        <button
                                            class="btn-producto-mas  w-[20px] h-[20px] text-center items-center opacity-80  text-color-text  ">
                                            <i class="fas fa-plus "></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full text-start">
                                <p class="text-color-text text-[13px]">{{ $item->descripcion }}</p>
                            </div>
                            <div class="w-full text-start">
                                @auth
                                    @if ($promocion_actual = $promociones_faltantes->where('producto_id', $item->id)->first())
                                        @php
                                            $faltante = $promocion_actual['faltante'] ?? 0;
                                            $meta = intval($promocion_actual['meta']) + 1;
                                            $ordinales = [
                                                1 => 'Primero',
                                                2 => 'Segundo',
                                                3 => 'Tercero',
                                                4 => 'Cuarto',
                                                5 => 'Quinto',
                                                6 => 'Sexto',
                                                7 => 'Séptimo',
                                                8 => 'Octavo',
                                                9 => 'Noveno',
                                                10 => 'Décimo',
                                                11 => 'Undécimo',
                                                12 => 'Duodécimo',
                                                13 => 'Decimotercer',
                                                14 => 'Decimocuarto',
                                                15 => 'Decimoquinto',
                                            ];
                                            $ordinal = $ordinales[$meta] ?? "{$meta}°"; // Si no está en el array, usa formato genérico

                                            $producto_gratis =
                                                $productos_con_promociones->firstWhere('producto_id', $item->id)
                                                    ->producto_gratis ?? '';

                                        @endphp

                                        @if ($usuario->tipo == 'cliente')
                                            <span class="bidones-faltan text-[13px] max-h-[55px]  text-color-text">
                                                @if ($promocion_actual['meta'] == 1)
                                                    <p class="tex-[14px] pl-2 pr-2 promocion_producto_gratis_valida"
                                                        data-producto-id="{{ $item->id }}"> 🎉 + 1
                                                        {{ $producto_gratis }}.
                                                    </p>
                                                @else
                                                    @if ($faltante > 0)
                                                        ¡Te faltan <span
                                                            class="resaltar-numero font-bold text-[14px]">{{ $faltante }}</span>
                                                        bidón(es) para tu {{ $ordinal }} GRATIS!
                                                    @else
                                                        <p class="tex-[13px] pl-2 pr-2 promocion_producto_gratis_valida"
                                                            data-producto-id="{{ $item->id }}"> 🎉 ¡Felicidades! Ya has
                                                            cumplido
                                                            la
                                                            promoción
                                                            para reclamar gratis 1
                                                            {{ $producto_gratis }}.
                                                        </p>
                                                    @endif
                                                @endif

                                            </span>
                                        @endif
                                    @endif



                                @endauth
                            </div>
                            @if ($item->categoria == 'gas')
                                <div class="w-full flex space-x-4 valvulas pb-[18px]">
                                    <label class="flex items-center gap-2 cursor-pointer text-[13px]">
                                        <input type="radio" data-id="{{ $item->id }}" value="normal"
                                            name="valvula_{{ $item->id }}" checked class="rounded-full border">
                                        Válvula Normal
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer text-[13px]">
                                        <input type="radio" data-id="{{ $item->id }}" value="premium"
                                            name="valvula_{{ $item->id }}" class="rounded-full border">
                                        Válvula Premium
                                    </label>
                                </div>
                            @endif


                        </div>
                    </div>
                @endforeach

            </div>
            <div class="flex flex-col  md:w-[500px] mx-auto  mt-[40px] w-full  bg-white rounded-[10px] p-[10px]">
                <!-- Mensaje y botón de despliegue -->
                <div class="flex items-center justify-center space-x-2 cursor-pointer mt-[10px] mb-[10px]"
                    id="toggleCupon">
                    <p class="text-base  text-color-titulos-entrega font-sans">¿Tienes un cupón?</p>
                    <i class="fa-solid fa-chevron-down text-color-titulos-entrega transition-transform duration-300"
                        id="iconoFlecha"></i>
                </div>

                <!-- Input para cupón (oculto por defecto) -->
                <div id="cuponForm" class="hidden  mx-auto  mt-[10px] mb-[10px]">
                    <div class="flex text-center w-1/2 space-x-2">
                        <input type="text" id="codigoCupon" placeholder="Ingresa tu codigo"
                            class="flex-1 p-2 text-center border border-gray-400 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button id="btnAplicarCupon"
                            class="px-4 py-2 bg-tarjetas text-white font-semibold rounded-full ">
                            Aplicar
                        </button>
                    </div>
                </div>
            </div>
            <div id="contenedor-total"
                class="mt-[40px]  sticky bottom-0 contenedor-total grid  w-full bg-color-fondo-productos justify-items-center ">
                <div id="contenedor_cupones" class="hidden  flex-col w-full text-center items-center justify-center">
                    <center>
                        <div class="w-full">
                            <p class="text-[15px] grid grid-cols-2 ">
                                Cupón Aplicado:
                                <input id="span_cupon" readonly class="font-bold bg-transparent text-start " />
                            </p>
                        </div>
                    </center>
                    <center>
                        <div class="  w-full mt-2">
                            <p class="text-[15px] grid grid-cols-2 ">
                                Descuento:
                                <input id="descuento" readonly class="font-bold bg-transparent text-start " />
                            </p>
                        </div>
                    </center>
                </div>

                <input type="text" readonly
                    class="total text-[18px]   w-[328px] h-[55px] rounded-md focus:outline-none bg-transparent font-bold  text-center  pb-[10px] "
                    value="Total: S/0.00">
                <button type="button" disabled
                    class="btnproductoagregar disabled:opacity-50 opacity-100  font-normal text-[18px]  w-[328px] h-[55px] rounded-md custom-bg-button  text-white ">
                    Siguiente
                    <i class=" fas fa-arrow-right-long text-2xl ml-2 "></i></button>
                @guest
                    <div class="h-[40px]"></div>
                @endguest
                @auth
                    @if ($usuario->tipo != 'cliente' && $empresa->id == $usuario->empresas()->first()->id)
                        <button type="button" disabled
                            class="btn_venta_rapida mb-[10px] transition-all duration-300 disabled:opacity-50  opacity-100 mt-[10px] font-normal text-[18px]  w-[328px] h-[55px] rounded-md custom-bg-button  text-white ">
                            Venta Rapida
                            <i class=" fas fa-arrow-right-long text-2xl ml-2 "></i></button>
                        
                    @endif
                @endauth

            </div>
        </div>
    @else
        <p class="text-gray-700 mx-auto font-medium text-md m-2">Sin Productos Registrados.</p>
    @endif

    <div id="contenedor_form_realizar_pedido"
        class=" hidden justify-center  mx-auto w-full p-[10px] md:min-w-[450px] md:max-w-[450px] mb-[35px] text-[15px] mt-[20px]  text-color-text">
        @if ($usuario && $usuario->tipo == 'cliente')
            <form action="{{ route('pedido.crear', ['slug' => $empresa->dominio]) }}" id="form_realizar_pedido"
                class=" text-start w-full md:min-w-[450px]  rounded-[20px] bg-white" method="POST">
                <div class="flex space-y-2 flex-col pt-[20px]   pb-[60px] pl-[20px] pr-[20px]">
                    <input type="hidden" id="usuario_id" name="usuario_id" value="{{ $usuario->id }}" required>
                    <input type="hidden" id="empresa_id" name="empresa_id" value="{{ $empresa->id }}" required>

                    <div class="mb-4">
                        <label for="select_direccion"
                            class="block text-lg font-semibold text-color-titulos-entrega">Selecciona tu
                            dirección
                        </label>

                        <div class="relative mt-2">
                            <select name="select_direccion" id="select_direccion"
                                class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none text-gray-700">
                                <option disabled selected>📍 Elige una dirección para tu pedido</option>
                                @if (!empty($usuario->persona->direccion))
                                    <option value="{{ $usuario->persona->direccion }}">🏠
                                        {{ $usuario->persona->direccion }}</option>
                                @endif
                                @if (!empty($usuario->direcciones))
                                    @foreach ($usuario->direcciones as $dir)
                                        <option value="{{ $dir->direccion }}">🏢
                                            {{ $dir->direccion }}</option>
                                    @endforeach
                                @endif
                            </select>

                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                        </div>
                    </div>


                    <label for="celular">Celular <span class="text-red-500">*</span></label>
                    <input type="tel" id="celular" name="celular" value="{{ $usuario->usuario }}"
                        minlength="9" maxlength="9" required pattern="\d{9}"
                        title="El número de teléfono debe tener exactamente 9 dígitos"
                        class=" rounded-[20px]  p-3 border border-color-text">
                    <label for="nombres">Nombres y Apellidos <span class="text-red-500">*</span></label>
                    <input type="text" value="{{ $usuario->persona->nombres }}" name="nombres" id="nombres"
                        class="rounded-[20px]  p-3 border border-color-text" required>
                    <label for="direccion">Dirección <span class="text-red-500">*</span></label>
                    <input type="text" value="{{ $usuario->persona->direccion }}" id="direccion"
                        name="direccion" class="rounded-[20px] p-3  border border-color-text" required>
                    <label for="referencia">Referencia y Nota para la Entrega <span
                            class="text-red-500">*</span></label>
                    <textarea type="text" rows="5" id="referencia" name="referencia" required
                        class="rounded-[20px] p-3  border border-color-text text-start">{{ $usuario->persona->nota }}
                        </textarea>
                    <div class="flex text-[17px]  justify-between pt-4 space-x-2">
                        <button type="button" id="btn_regresar_a_productos"
                            class="w-1/5 h-[57px] border border-color-text rounded-[3px]">Atrás</button>
                        <button type="submit"
                            class="custom-bg-button w-4/5 h-[57px] rounded-[3px]  text-white">Realizar
                            pedido</button>
                    </div>

                </div>
            </form>
        @else
            <form action="{{ route('pedido.crear', ['slug' => $empresa->dominio]) }}" method="POST"
                id="form_realizar_pedido" class="mx-auto   w-full md:min-w-[450px] ">
                <div
                    class="flex space-y-2 flex-col w-full  bg-white rounded-[20px] pt-[20px] pb-[60px] pl-[20px] pr-[20px]">
                    <input type="hidden" id="usuario_id" name="usuario_id"
                        value="{{ $usuario ? $usuario->id : '' }}" required>
                    <input type="hidden" id="empresa_id" name="empresa_id" value="{{ $empresa->id }}" required>

                    <label for="celular">Celular <span class="text-red-500">*</span></label>
                    <input type="tel" id="celular" placeholder="Ingrese el numero para buscar..."
                        name="celular" class="rounded-[20px]  p-3 border border-color-text" required>
                    <label for="nombres">Nombres y Apellidos <span class="text-red-500">*</span></label>
                    <input type="text" name="nombres" id="nombres"
                        class="rounded-[20px]  p-3 border border-color-text" required>
                    <label for="direccion">Dirección <span class="text-red-500">*</span></label>
                    <input type="text" id="direccion" name="direccion"
                        class="rounded-[20px] p-3  border border-color-text" required>
                    <label for="referencia">Referencia y Nota para la Entrega <span
                            class="text-red-500">*</span></label>
                    <textarea type="text" rows="5" id="referencia" name="referencia"
                        class="rounded-[20px] p-3  border border-color-text" required>
                        </textarea>
                    <div class="flex text-[17px]  justify-between pt-4 space-x-2">
                        <button type="button" id="btn_atras_distribuidora"
                            class="w-1/5 h-[57px] border border-color-text rounded-[3px]">Atrás</button>
                        <button type="submit"
                            class="custom-bg-button w-4/5 h-[57px] rounded-[3px] text-white">Realizar
                            pedido</button>
                    </div>

                </div>
            </form>
        @endif

    </div>
</div>
<!-- Modal Pago Pedido VENTA RAPIDA-->
<div id="paymentModalVentaRapida"
    class="hidden fixed  inset-0 bg-black bg-opacity-70 my-auto  items-center justify-center z-50">
    <div class="bg-white rounded text-color-text text-base font-sans  shadow-md w-full mx-auto max-w-md p-6">
        <h2 class="text-xl font-semibold text-center mb-4 font-cabin">Finalizar Pedido <span
                id="modal_pago_pedido_id"></span></h2>
        <form id="form_metodo_pago_venta_rapida" method="post" class="mx-auto my-auto"
            action="{{ route('pedido.pedidorapido', ['slug' => $empresa->dominio]) }}">
            <input type="text" name="empresa_id" value="{{ $empresa->id }}" hidden>
            <!-- Opciones de pago -->
            <div class="mb-4">
                <label class="flex items-center space-x-2">
                    <input type="radio" name="paymentMethod" value="yape"
                        class="text-blue-500 focus:ring-blue-500">
                    <span>Pagó con Yape</span>
                </label>
            </div>
            <div class="mb-4">
                <label class="flex items-center space-x-2">
                    <input type="radio" name="paymentMethod" value="efectivo"
                        class="text-blue-500 focus:ring-blue-500" checked>
                    <span>Pagó en Efectivo</span>
                </label>
            </div>
            <div class="mb-4">
                <label class="flex items-center space-x-2">
                    <input type="radio" name="paymentMethod" value="account"
                        class="text-blue-500 focus:ring-blue-500">
                    <span>Deuda Pendiente</span>
                </label>
            </div>

            <!-- Botones -->
            <div class="flex justify-end mt-6 space-x-1">
                <button type="submit"
                    class="px-4 py-2 bg-naranja text-white rounded hover:bg-border-red-500 hover:scale-105 transition">
                    Aceptar
                </button>
                <button type="button"
                    class="px-4 py-2 border border-color-titulos-entrega text-color-titulos-entrega rounded hover:scale-105 transition"
                    onclick="document.getElementById('paymentModalVentaRapida').classList.remove('flex');document.getElementById('paymentModalVentaRapida').classList.add('hidden')">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

</div>
