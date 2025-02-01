@extends('layout-cuenta')
@section('logica')
    <div class="w-full container mx-auto  text-base p-6 font-sans ">
        <h1 class="text-2xl font-cabin font-bold mb-4">Filtros de Búsqueda</h1>
        <div class=" justify-between md:flex md:space-x-2 space-x-2 grid">
            <div class="flex space-x-2">

                <!-- Filtro por ID Pedido -->
                <div class="mb-4">
                    <label for="orderId" class="block text-base font-semibold text-color-titulos-entrega">ID Pedido</label>
                    <input id="orderId" type="text" placeholder="Buscar por ID"
                        class="w-full mt-1 p-3 border border-color-titulos-entrega rounded-[20px] focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Filtro por ID Cliente -->
                <div class="mb-4">
                    <label for="clientId" class="block text-base font-semibold text-color-titulos-entrega">ID
                        Cliente</label>
                    <input id="clientId" type="text" placeholder="Buscar por ID Cliente"
                        class="w-full mt-1 p-3 border border-color-titulos-entrega rounded-[20px] focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Buscar por Cliente -->
                <div class="mb-4">
                    <label for="clientName" class="block text-base font-semibold text-color-titulos-entrega">Buscar por
                        Cliente</label>
                    <input id="clientName" type="text" placeholder="Escribe un nombre"
                        class="w-full mt-1 p-3 border border-color-titulos-entrega rounded-[20px] focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex space-x-2">
                <!-- Filtro por Estado Delivery -->
                <div class="mb-4">
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
                <div class="mb-4">
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
                <div class="mb-4">
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
                            <input id="todos" name="paymentStatus" type="radio" value="" class="h-4 w-4"
                                checked>
                            <label for="todos" class="ml-2">Todos</label>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <div class="p-4 flex justify-center space-x-2">
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
            <div class="font-sans tex-base">
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
        <div class="overflow-x-auto mt-4">
            <table class="min-w-full border-collapse border border-gray-300" id="tabla_reportes">
                <thead>
                    <tr class="bg-tarjetas text-center">
                        <th class="border border-gray-300 px-4 py-2  text-white font-semibold">ID</th>
                        <th class="border border-gray-300 px-4 py-2  text-white font-semibold">User ID</th>
                        <th class="border border-gray-300 px-4 py-2  text-white font-semibold">Cliente</th>
                        <th class="border border-gray-300 px-4 py-2  text-white font-semibold">Medio</th>
                        <th class="border border-gray-300 px-4 py-2  text-white font-semibold">Total</th>
                        <th class="border border-gray-300 px-4 py-2  text-white font-semibold">PG</th>
                        <th class="border border-gray-300 px-4 py-2  text-white font-semibold">Pago</th>
                        <th class="border border-gray-300 px-4 py-2  text-white font-semibold">Delivery</th>
                        <th class="border border-gray-300 px-4 py-2  text-white font-semibold">Fecha</th>
                        <th class="border border-gray-300 px-4 py-2  text-white font-semibold">Acción</th>

                    </tr>
                </thead>
                <tbody>

                    @foreach ($pedidos as $item)
                        <tr class="odd:bg-white text-center even:bg-gray-100 h-[40px] text-[14px]">
                            <td class="border border-gray-200">{{ $item->id }}</td>
                            <td class="border border-gray-200">{{ $item->cliente_id }}</td>
                            <td class="border border-gray-200">{{ $item->usuario->persona->nombres }}</td>
                            <td class="border border-gray-200">
                                {{ $item->metodo == 'account' ? 'deuda pendiente' : $item->metodo }}</td>
                            <td class="border border-gray-200">{{ $item->total }}</td>
                            <td class="border border-gray-200">{{ $item->entregapromociones->count() > 0 ? 'PG' : '' }}
                            </td>
                            <td class="border border-gray-200">{{ $item->pago ? 'Pagado' : 'Pendiente de Pago' }}</td>
                            <td class="border border-gray-200">{{ $item->estado }}</td>
                            <td class="border border-gray-200">
                                {{ \Carbon\Carbon::parse($item->fecha)->format('d-m-Y  h:i a') }}
                            </td>
                            <td class="border border-gray-200">
                                @if ($item->metodo == 'account')
                                    <button data-id="{{ $item->id }}"
                                        class="btn_cliente_pago_deuda transform hover:scale-125"
                                        title="El Cliente canceló su deuda"><i class="fas fa-edit"></i></button>
                                @endif
                            </td>




                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="flex ml-2 mt-2 space-x-4 items-center">
            <!-- Número de Pedidos -->
            <div class="flex items-center">
                <b id="numero_pedidos">{{ $pedidos->count() }}</b>
                <span class="ml-1">Pedidos</span>
                <span class="text-naranja font-bold mx-2 text-2xl">></span>
            </div>

            <!-- Total de Pedidos -->
            <div class="flex items-center">
                <span>Total:</span>
                <b id="total_pedidos" class="ml-1">S/{{ $pedidos->sum('total') }}</b>
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
