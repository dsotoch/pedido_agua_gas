<div class="pt-[40px] pb-[40px] bg-color-fondo-productos flex flex-row  w-full justify-stretch items-center space-x-2 ">


    @if ($productos->count() > 0)
        <div id="contener_producto_item"
            class="  flex flex-col mx-auto bg-white text-color-text  items-center justify-center rounded-3xl pt-4 pb-16 bg-transparent text-center w-[450px] shadow-sm "
            data-index="">
            @foreach ($productos as $item)
                <div>
                    <p
                        class="producto_descripcion text-[18px] w-full
                    font-semibold mt-5 mb-[11px] leading-9">
                        {{ $item->descripcion }}</p>
                    <div class="item-container flex  space-x-2 w-full  mx-auto justify-center"
                        data-producto-id="{{ $item->id }}">
                        <p class="hidden precionormal">{{ number_format($item->precio ?? 0, 2, '.', '') }}</p>
                        <p class="hidden promociones">{{ $item->promociones }}</p>
                        <!-- Botón de disminución -->
                        <button
                            class=" btn-producto-menos border w-[50px] h-[49px] text-center  border-color-text hover:bg-color-text rounded-full  text-color-text hover:border-red-600 hover:text-white">
                            <i class="fas fa-minus"></i>
                        </button>

                        <!-- Input de cantidad con flex-grow y max-w-xs -->
                        <input type="number" name="cantidad" value="0" readonly
                            class="cantidad flex-grow text-[16px] max-w-[100px] h-[48px] p-2 border border-color-text rounded-xl border-text-color text-center">

                        <!-- Botón de aumento -->
                        <button
                            class="btn-producto-mas border w-[50px] h-[49px] text-center border-color-text hover:bg-color-text rounded-full  text-color-text hover:border-red-600 hover:text-white">
                            <i class="fas fa-plus "></i>
                        </button>
                    </div>
                    <p class="  text-[13px] p-[14px] w-full">Precio Unitario: <span
                            class="text-[13px]  precioprincipal">S/{{ number_format($item->precio ?? 0, 2, '.', '') }}</span>
                    </p>
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
                                // Filtrar las promociones con estado false
                                $promociones_inactivas = collect($item->entregaPromociones)
                                    ->filter(function ($promocion) {
                                        return $promocion->estado == 0; // Asegúrate de que el campo sea 'estado'
                                    })
                                    ->first();
                                // Si no falta, asignar el nombre del producto
                                $producto_gratis = $faltante === 0 ? $promociones_inactivas?->producto : '';
                                $ultima_promocion = collect($item->entregaPromociones)
                                    ->filter(fn($promocion) => $promocion->estado == 1)
                                    ->sortByDesc('created_at') // Ordena por fecha de creación (ajusta si es otro campo)
                                    ->first();

                                $pro_gratis_unitario = $ultima_promocion?->producto;
                            @endphp

                            @if ($usuario->tipo == 'cliente')
                                <span class="bidones-faltan text-[14px] max-h-[55px]  text-color-text">
                                    @if ($promocion_actual['meta'] == 1)
                                        <p class="tex-[14px] pl-2 pr-2 promocion_producto_gratis_valida"
                                            data-producto-id="{{ $item->id }}"> 🎉 Puedes reclamar gratis este producto:
                                        </p>
                                        <span class="font-semibold">{{ $pro_gratis_unitario }}</span>.
                                    @else
                                        @if ($faltante > 0)
                                            ¡Te faltan <span
                                                class="resaltar-numero font-bold text-[16px]">{{ $faltante }}</span>
                                            bidón(es) para tu {{ $ordinal }} GRATIS!
                                        @else
                                            <p class="tex-[14px] pl-2 pr-2 promocion_producto_gratis_valida"
                                                data-producto-id="{{ $item->id }}"> 🎉 ¡Felicidades! Ya has cumplido la
                                                promoción
                                                para reclamar gratis este producto:
                                            </p>
                                            <span class="font-semibold">{{ $producto_gratis }}</span>.
                                        @endif
                                    @endif

                                </span>
                            @endif
                        @endif



                    @endauth

                </div>
                <hr class="w-1/2">
            @endforeach
            <div class="flex flex-col w-full max-w-md mx-auto p-2 mt-1   rounded-lg">
                <!-- Mensaje y botón de despliegue -->
                <div class="flex items-center justify-center space-x-2 cursor-pointer" id="toggleCupon">
                    <p class="text-base font-semibold text-color-titulos-entrega font-sans">¿Tienes algún cupón?</p>
                    <i class="fa-solid fa-chevron-down text-color-titulos-entrega transition-transform duration-300"
                        id="iconoFlecha"></i>
                </div>

                <!-- Input para cupón (oculto por defecto) -->
                <div id="cuponForm" class="hidden mt-3 mx-auto">
                    <div class="flex text-center w-1/2">
                        <input type="text" id="codigoCupon" placeholder="Ingresa tu cupón"
                            class="flex-1 p-2 border border-gray-400 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button id="btnAplicarCupon"
                            class="px-4 bg-blue-500 text-white font-semibold rounded-r-lg hover:bg-blue-600">
                            Aplicar
                        </button>
                    </div>
                </div>
            </div>

            <div id="contenedor-total" class="mt-2 contenedor-total grid  justify-items-center ">
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
                    class="total text-[18px] border w-[328px] h-[55px] rounded-md focus:outline-none bg-secundario text-white font-bold  text-center pt-[10px] pb-[10px] "
                    value="Total: S/0.00">
                <button type="button" disabled
                    class="btnproductoagregar disabled:opacity-50 opacity-100  font-normal text-[18px]  w-[328px] h-[55px] rounded-md custom-bg-button  text-white mt-2">
                    Siguiente
                    <i class=" fas fa-arrow-right-long text-2xl ml-2 "></i></button>

            </div>


        </div>
    @else
        <p class="text-gray-700 mx-auto font-medium text-md m-2">Sin Productos Registrados.</p>
    @endif

    <div id="contenedor_form_realizar_pedido" class="hidden justify-center  mx-auto w-full text-[15px] text-color-text">
        @if ($usuario && $usuario->tipo == 'cliente')
            <form action="{{ route('pedido.crear', ['slug' => $empresa->dominio]) }}" id="form_realizar_pedido"
                class=" text-start " method="POST">
                <div
                    class="flex space-y-2 flex-col w-full md:w-[450px] h-[650px] bg-white rounded-[20px] pt-[30px] pb-[60px] pl-[20px] pr-[20px]">
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
                                @if (!empty($usuario->persona->direccion2))
                                    <option value="{{ $usuario->persona->direccion2 }}">🏢
                                        {{ $usuario->persona->direccion2 }}</option>
                                @endif
                            </select>

                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                        </div>
                    </div>


                    <label for="celular">Celular <span class="text-red-500">*</span></label>
                    <input type="tel" id="celular" name="celular" value="{{ $usuario->usuario }}" minlength="9"
                        maxlength="9" required pattern="\d{9}"
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
                            class="custom-bg-button w-4/5 h-[57px] rounded-[3px] text-white">Realizar
                            pedido</button>
                    </div>

                </div>
            </form>
        @else
            <form action="{{ route('pedido.crear', ['slug' => $empresa->dominio]) }}" method="POST"
                id="form_realizar_pedido" class="mx-auto">
                <div
                    class="flex space-y-2 flex-col w-full md:w-[450px] h-[650px] bg-white rounded-[20px] pt-[30px] pb-[60px] pl-[20px] pr-[20px]">
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
