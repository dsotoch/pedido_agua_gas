@extends('layout-cuenta')
@section('logica')
    <div class="w-screen md:w-full container mx-auto  text-base p-6 font-sans bg-color-dashboard">
        <h1 class="text-2xl font-cabin font-bold mb-4">Filtros de Búsqueda</h1>
        <div class="w-full grid md:flex md:space-x-2">
            <div class="w-full grid grid-cols-1 md:flex md:space-x-2">
                <!-- Filtro por ID Pedido -->
                <div class="w-full mb-4">
                    <label for="orderId" class="block text-base font-semibold text-color-titulos-entrega">ID Pedido</label>
                    <input id="orderId" type="text" placeholder="Buscar por ID"
                        class="w-full mt-1 p-3 border border-color-titulos-entrega rounded-[20px] focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Filtro por ID Cliente -->
                <div class="w-full mb-4">
                    <label for="clientId" class="block text-base font-semibold text-color-titulos-entrega">ID
                        Cliente</label>
                    <input id="clientId" type="text" placeholder="Buscar por ID Cliente"
                        class="w-full mt-1 p-3 border border-color-titulos-entrega rounded-[20px] focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Buscar por Cliente -->
                <div class="w-full mb-4">
                    <label for="clientName" class="block text-base font-semibold text-color-titulos-entrega">Buscar por
                        Cliente</label>
                    <input id="clientName" type="text" placeholder="Escribe un nombre"
                        class="w-full mt-1 p-3 border border-color-titulos-entrega rounded-[20px] focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="w-full grid grid-cols-1 md:flex md:space-x-2">
                <!-- Filtro por Estado Delivery -->
                <div class="w-full mb-4">
                    <label for="deliveryStatus" class="block text-base font-semibold text-color-titulos-entrega">Estado
                        Delivery</label>
                    <select id="deliveryStatus"
                        class="w-full mt-1 p-3 border border-color-titulos-entrega rounded-[20px] focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccione...</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Entregado">Entregado</option>
                        <option value="En Camino">En Ruta</option>
                        <option value="Anulado">Cancelado</option>
                    </select>
                </div>

                <!-- Filtro por Medio de Pago -->
                <div class="w-full mb-4">
                    <label for="paymentMethod" class="block text-base font-semibold text-color-titulos-entrega">Medio de
                        Pago</label>
                    <select id="paymentMethod"
                        class="w-full mt-1 p-3 border border-color-titulos-entrega rounded-[20px] focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccione...</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="yape">Yape</option>
                        <option value="deuda pendiente">Deuda Pendiente</option>
                    </select>
                </div>

                <!-- Checkbox Estado de Pago -->
                <div class="w-full mb-4">
                    <label class="block text-base font-semibold text-color-titulos-entrega">Estado de Pago</label>
                    <div class="flex flex-col items-start space-y-2 mt-2">
                        <div class="flex items-center">
                            <input id="pagado" name="paymentStatus" type="radio" value="Pagado"
                                class="h-4 w-4 text-blue-600 border-color-titulos-entrega rounded focus:ring-blue-500">
                            <label for="pagado" class="ml-2 text-color-titulos-entrega">Pagado</label>
                        </div>
                        <div class="flex items-center">
                            <input id="pendiente" name="paymentStatus" type="radio" value="Pendiente de Pago"
                                class="h-4 w-4 text-blue-600 border-color-titulos-entrega rounded focus:ring-blue-500">
                            <label for="pendiente" class="ml-2 text-color-titulos-entrega">Pendiente de Pago</label>
                        </div>
                        <div class="flex items-center">
                            <input id="deuda_pendiente" name="paymentStatus" type="radio" value="deuda pendiente"
                                class="h-4 w-4 text-blue-600 border-color-titulos-entrega rounded focus:ring-blue-500">
                            <label for="deuda_pendiente" class="ml-2 text-color-titulos-entrega">Deuda Pendiente</label>
                        </div>
                        <div class="flex items-center">
                            <input id="todos" name="paymentStatus" type="radio" value="" class="h-4 w-4"
                                checked>
                            <label for="todos" class="ml-2">Todos</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:p-4 w-full flex flex-col md:flex-row justify-center  space-x-0 md:space-x-2 bg-color-dashboard">
            <!-- Noveno Gratis Checkbox Filter -->
            <div class="">
                <div class="text-lg  font-semibold mb-2">Promoción Gratis</div>
                <fieldset class="space-y-2">
                    <!-- Checkbox NG -->
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="noveno_gratis" value="PG"
                            class="h-5 w-5 text-blue-600 border-color-titulos-entrega rounded focus:ring-blue-500" />
                        <span class="text-sm">PG</span>
                    </label>

                </fieldset>
            </div>
            <!-- Date Range Filter -->
            <div class="font-sans tex-base ">
                <div class="text-lg font-cabin font-semibold mb-2 text-center">Rango de Fechas</div>
                <fieldset class="flex space-x-3">
                    <legend class="sr-only">Fecha</legend>
                    <!-- Date Range From -->
                    <input type="date" placeholder="📆 Desde" name="fecha_from" id="fecha_from"
                        class="border   border-color-titulos-entrega rounded-[20px] px-4 py-3 w-full focus:ring focus:ring-blue-500 focus:border-blue-500" />
                    <!-- Date Range To -->
                    <input type="date" placeholder="📆 Hasta" name="fecha_to" id="fecha_to"
                        class="border  border-color-titulos-entrega rounded-[20px] px-4 py-3 w-full focus:ring focus:ring-blue-500 focus:border-blue-500" />
                    <button class="rounded-md bg-naranja text-white p-3" id="btn_filtar_por_fecha">
                        <i class="fas fa-search fa-xl"></i>
                    </button>
                </fieldset>


            </div>
        </div>
        <div class="mt-4 overflow-x-auto  ">
            <table class=" w-full  table border-collapse border border-gray-300" id="tabla_reportes">
                <thead>
                    <tr class="bg-tarjetas text-center">
                        <th class="break-words w-28 border border-gray-300 px-4 py-2  text-white font-semibold">ID</th>
                        <th class="break-words w-28 border border-gray-300 px-4 py-2  text-white font-semibold">User ID
                        </th>
                        <th class="break-words w-28 border border-gray-300 px-4 py-2  text-white font-semibold">Cliente
                        </th>

                        <th class="break-words w-28 border border-gray-300 px-4 py-2  text-white font-semibold">Medio</th>

                        @foreach ($productos as $item)
                            <th class="break-words w-28 border border-gray-300 px-4 py-2  text-white font-semibold">
                                {{ $item->descripcion }}
                            </th>
                        @endforeach
                        <th class="break-words w-28 border  border-gray-300 px-4 py-2  text-white font-semibold">Total</th>
                        <th class="break-words w-28 border border-gray-300 px-4 py-2  text-white font-semibold">PG</th>
                        <th class="break-words w-28 border border-gray-300 px-4 py-2  text-white font-semibold">Pago</th>
                        <th class="break-words w-28 border border-gray-300 px-4 py-2  text-white font-semibold">Delivery
                        </th>
                        <th class="break-words w-28 border border-gray-300 px-4 py-2  text-white font-semibold">Fecha</th>
                        <th class="break-words w-28 border border-gray-300 px-4 py-2  text-white font-semibold">Acción</th>

                    </tr>
                </thead>
                <tbody>

                    @foreach ($pedidos as $item)
                        <tr class="odd:bg-white text-center even:bg-gray-100 h-[40px] text-[14px]">
                            <td class="border border-gray-200">{{ $item->id }}</td>
                            <td class="border border-gray-200">{{ $item->cliente_id }}</td>
                            <td class="border border-gray-200">{{ $item->usuario->persona->nombres }}</td>
                            <td class="border border-gray-200">
                                {{ $item->metodo == 'account' ? 'deuda pendiente' : $item->metodo }}
                            </td>
                            @foreach ($productos as $pro)
                                @php
                                    if ($item->detalles->count() > 0) {
                                        // Filtrar detalles dentro del pedido actual para obtener la cantidad total de cada producto
                                        $cantidadTotal = $item->detalles
                                            ->where('producto_id', $pro->id) // Verificamos por ID de producto
                                            ->sum('cantidad'); // Sumamos todas las cantidades del mismo producto
                                    } else {
                                        // Filtrar detalles dentro del pedido actual para obtener la cantidad total de cada producto
                                        $cantidadTotal = $item->entregaPromociones
                                            ->where('producto', $pro->descripcion) // Verificamos por ID de producto
                                            ->sum('cantidad'); // Sumamos todas las cantidades del mismo producto
                                    }
                                @endphp
                                <td class="border border-gray-200 px-4 py-2">{{ $cantidadTotal }}</td>
                            @endforeach
                            <td class="border border-gray-200">{{ $item->total }}</td>
                            <td class="border border-gray-200">{{ $item->entregapromociones->count() > 0 ? 'PG' : '' }}
                            </td>
                            <td class="border border-gray-200">{{ $item->pago ? 'Pagado' : 'Pendiente de Pago' }}</td>
                            <td class="border border-gray-200">{{ $item->estado }}</td>
                            <td class="border border-gray-200">
                                {{ \Carbon\Carbon::parse($item->fecha)->format('d-m-Y  h:i a') }}
                            </td>
                            <td class="border border-gray-200">
                                <div class="flex space-x-2 justify-center">
                                    <button data-id="{{ $item->id }}"
                                        class="btn_eliminar_pedido transform hover:scale-125" title="Eliminar Pedido"><i
                                            class="fas fa-trash"></i></button>
                                    @if ($item->metodo == 'account')
                                        <button data-id="{{ $item->id }}"
                                            class="btn_cliente_pago_deuda transform hover:scale-125"
                                            title="El Cliente canceló su deuda"><i class="fas fa-edit"></i></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach


                </tbody>

            </table>
        </div>
        <!-- 🔹 Fijar el footer fuera de la tabla -->
        <div class=" bg-white shadow-md py-2 px-4 ">
            <div class="flex md:justify-center justify-start mt-4 space-x-2">
                @if ($pedidos->onFirstPage())
                    <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed">
                        ⬅️ Anterior
                    </span>
                @else
                    <a href="{{ $pedidos->previousPageUrl() }}"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-300 transition">
                        ⬅️ Anterior
                    </a>
                @endif

                <span class="px-4 py-2 bg-gray-100 border rounded-lg">
                    📄 Página <b>{{ $pedidos->currentPage() }}</b> de <b>{{ $pedidos->lastPage() }}</b>
                </span>

                @if ($pedidos->hasMorePages())
                    <a href="{{ $pedidos->nextPageUrl() }}"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-300 transition">
                        Siguiente ➡️
                    </a>
                @else
                    <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed">
                        Siguiente ➡️
                    </span>
                @endif
            </div>

            <div class="flex ml-2 mt-2 space-x-4 items-center overflow-x-auto">
                <div class="flex items-center">
                    <b id="numero_pedidos">{{ $pedidos->count() }}</b>
                    <span class="ml-1">Pedidos</span>
                    <span class="text-naranja font-bold mx-2 text-2xl">></span>
                </div>
                <div id="contenedor_cantidad_productos" class="flex items-center">
                    @foreach ($productos_cantidades as $producto => $cantidad)
                        <div class="flex items-center">
                            <b id="cantidad_producto">{{ $cantidad }}</b>
                            <span class="ml-1">{{ $producto }}</span>
                            <span class="text-naranja font-bold mx-2 text-2xl">></span>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center">
                    <span>Total:</span>
                    <b id="total_pedidos" class="ml-1">S/{{ $pedidos->sum('total') }}</b>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Pago Pedido Repartidor-->
    <div id="modal_pago_reporte"
        class="hidden fixed flex-col inset-0 bg-black bg-opacity-70  items-center justify-center z-50">
        <div class="bg-white rounded text-color-text text-base font-sans  shadow-md w-full mx-auto max-w-md p-6">
            <h2 class="text-xl font-semibold text-center mb-4 font-cabin">Finalizar Pedido <span
                    id="modal_pago_reporte_id"></span></h2>
            <form id="form_metodo_pago_reporte" method="post" action="{{ route('pedido.cambiarestadopago') }}">
                <input type="text" name="id_pedido" id="id_pedido_modal_pago" hidden>
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

                <p class="text-color-titulo-entrega font-semibold m-2">Notas internas sobre este pedido
                </p>
                <div class="mb-4 w-full">
                    <textarea class="p-4 border w-full"
                        placeholder="Agrega notas internas sobre este pedido. Ejemplo: Le debo un vuelto de x soles, pagó con x cantidad."
                        name="notas" id="notas"></textarea>
                </div>
                <!-- Botones -->
                <div class="flex justify-end mt-6 space-x-1">
                    <button type="submit"
                        class="px-4 py-2 bg-naranja text-white rounded hover:bg-border-red-500 hover:scale-105 transition">
                        Aceptar
                    </button>
                    <button type="button"
                        class="px-4 py-2 border border-color-titulos-entrega text-color-titulos-entrega rounded hover:scale-105 transition"
                        onclick="document.getElementById('modal_pago_reporte').classList.remove('flex');;document.getElementById('modal_pago_reporte').classList.add('hidden')">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
