@extends('layout-cuenta')
@section('logica')
    <div class="container mx-auto md:w-full w-screen font-sans p-4 md:p-[20px] bg-white shadow-lg rounded-lg text-color-titulos-entrega">
        @if (session('mensaje'))
            <div id="mensaje" class=" p-3 w-full border-2 border-green-500  text-center text-green-500 font-semibold">
                <p class="text-base text-center">{{ session('mensaje') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div id="mensaje" class="p-3 w-full border-red-500 border-2  text-red-500 text-center">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-base text-center font-semibold">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h2 class="text-2xl font-bold mb-4">Registrar Salida de Productos</h2>

        {{-- Formulario de Salida --}}
        <form action="{{ route('salidas.crear') }}" method="POST">
            @csrf

            <div class="mb-4 w-full flex flex-col space-y-3 mt-1">
                <label class="block font-semibold">Vehículo</label>

                <div id="vehiculo-container w-full">
                    <div class="flex items-center space-x-1 w-3/4">
                        <select name="vehiculo_id" id="vehiculo-select" required class="w-full p-2 border rounded-lg">
                            @if (count($vehiculos) > 0)
                                <option value="" disabled selected>Seleccione un vehiculo </option>

                                @foreach ($vehiculos as $vehiculo)
                                    <option value="{{ $vehiculo->placa }} - {{ $vehiculo->repartidor->id }}">
                                        {{ $vehiculo->placa }} - {{ $vehiculo->repartidor->persona->nombres }}</option>
                                @endforeach
                            @else
                                <option value="" disabled selected>No hay vehículos registrados</option>
                            @endif
                        </select>
                        <div class="w-[15%]"><button type="button" class="transform hover:scale-105" id="btn_editar_vehiculo"><i
                                    class="fas fa-edit text-2xl"></i></button></div>
                    </div>
                    <!-- Modal -->
                    <div id="modal_vehiculos"
                        class="fixed inset-0  bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
                        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                            <h2 class="text-xl font-bold mb-4">Cambiar Repartidor</h2>

                            <p class="mb-2 flex space-x-2"><strong>Vehículo:</strong> <span id="modal-vehiculo"></span></p>
                            <p class="mb-4 flex space-x-2"><strong>Repartidor Actual:</strong> <span
                                    id="modal-repartidor"></span></p>

                            <!-- Selección de nuevo repartidor -->
                            <label for="nuevo-repartidor" class="block mb-2">Selecciona un nuevo repartidor:</label>
                            <select id="nuevo-repartidor" class="w-full p-2 border rounded-lg">
                                @foreach ($repartidores as $repartidor)
                                    @if ($repartidor->persona?->estado)
                                        <option value="{{ $repartidor->id }}">{{ $repartidor->persona->nombres }}</option>
                                    @endif
                                @endforeach
                            </select>

                            <!-- Botones -->
                            <div class="flex justify-end mt-4 space-x-2">
                                <button id="cerrar-modal" type="button"
                                    class="bg-gray-400 text-white px-4 py-2 rounded-lg">Cancelar</button>
                                <button id="guardar-cambio" type="button"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-lg">Guardar</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="btn_agregar_vehiculo"
                        class="disabled:opacity-50 border-2 border-color-text text-color-text font-semibold p-1 rounded mt-2">
                        + Agregar Vehículo
                    </button>
                </div>


                {{-- Formulario dinámico de nuevo vehículo (Oculto por defecto) --}}
                <div id="nuevo-vehiculo-form" class="hidden mt-4">
                    <input type="text" id="placa" placeholder="Placa" class="w-full p-2 border rounded mb-2">
                    @if (count($repartidores) > 0)
                        <select id="repartidor-select" class="w-full p-2 border rounded mb-2">
                            @foreach ($repartidores as $repartidor)
                                @if ($repartidor->persona?->estado)
                                    <option value="{{ $repartidor->id }}">{{ $repartidor->persona->nombres }}</option>
                                @endif
                            @endforeach
                        </select>
                    @endif
                    <button type="button" id="btn_guardar_vehiculo"
                        class="bg-blue-500 text-white px-3 py-2 rounded">Guardar</button>
                </div>
            </div>

            <div class="mb-4 w-full">
                <label class="block font-semibold">Productos</label>
                <div id="productos-container" class="w-full">
                    <div class="flex gap-2 mb-2 w-full">
                        <select id="select_productos_salidas" name="productos[]" required
                            class="w-1/2 md:w-3/5 p-2 border rounded-lg">
                            @foreach ($productos as $producto)
                                @if ($producto->categoria === 'gas')
                                    <option value="{{ $producto->id . '_normal' }}" data-tipo="gas"
                                        data-id="{{ $producto->id }}">
                                        {{ $producto->nombre . ' ' . $producto->descripcion . ' - Normal' }}
                                    </option>
                                    <option value="{{ $producto->id . '_premium' }}" data-tipo="gas"
                                        data-id="{{ $producto->id }}">
                                        {{ $producto->nombre . ' ' . $producto->descripcion . ' - Premium' }}
                                    </option>
                                @else
                                    <option value="{{ $producto->id }}" data-tipo="{{ $producto->categoria }}"
                                        data-id="{{ $producto->id }}">
                                        {{ $producto->nombre . ' ' . $producto->descripcion }}
                                    </option>
                                @endif
                            @endforeach

                        </select>
                        <input type="number" name="cantidades[]" min="1" required
                            class="w-1/2 md:w-2/5 p-2 border rounded-lg" placeholder="Cantidad">
                    </div>
                </div>
                <button type="button" onclick="agregarProducto()"
                    class="mt-2 border-2  text-color-text border-color-text font-semibold px-3 py-1 rounded">Añadir
                    Producto</button>
            </div>
            <input type="text" name="empresa_id" hidden value="{{ $empresa->id }}">
            <button type="submit" class="bg-naranja text-white px-4 py-2 mt-4 rounded-lg">Registrar Salida</button>
        </form>

        {{-- Historial de Salidas --}}
        <h2 class="text-2xl font-bold mt-8 mb-4">Historial de Salidas del dia</h2>
        <div class="overflow-x-auto w-full">
            <table class="w-full border whitespace-nowrap border-gray-200">
                <thead>
                    <tr class="bg-tarjetas text-white">
                        <th class="border p-2">Fecha</th>
                        <th class="border p-2">Vehículo</th>
                        <th class="border p-2">Repartidor</th>
                        <th class="border p-2">Productos</th>
                        <th class="border p-2">Opciones</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($salidas as $salida)
                        <tr class="border text-center">
                            <td class="p-2">{{ $salida->fecha }}</td>
                            <td class="p-2">{{ $salida->placa }}</td>
                            <td class="p-2">{{ $salida->repartidor }}</td>

                            <td class="p-2">
                                @php
                                    // Decodificar el JSON de productos
                                    $productos = json_decode($salida->productos, true);
                                @endphp

                                <ul>
                                    @foreach ($productos as $item)
                                        @php
                                            // Buscar el producto en la base de datos por su ID

                                            $id = '';
                                            if (strpos($item['producto_id'], '_') !== false) {
                                                $id = explode('_', $item['producto_id'])[0];
                                            } else {
                                                $id = $item['producto_id'];
                                            }

                                            $producto = \App\Models\Producto::find($id);
                                            $tipo = '';
                                            if (strpos($item['producto_id'], '_') !== false) {
                                                $tipo = substr($item['producto_id'], strpos($item['producto_id'], '_'));
                                        } @endphp

                                        @if ($producto)
                                            <li>{{ $item['cantidad'] }} *
                                                {{ $producto->nombre . ' ' . $producto->descripcion . $tipo }}</li>
                                        @else
                                            <li>{{ $item['cantidad'] }} * Producto no encontrado</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>
                            <td class="p-2">
                                <div class="flex justify-center">
                                    <form action="{{ route('salidas.eliminar') }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <input type="text" hidden name='salida_id' value="{{ $salida->id }}">
                                        <button type="submit"> <i class="fas fa-trash"></i></button>

                                    </form>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Script para añadir más productos dinámicamente --}}
    <script>
        function agregarProducto() {
            let container = document.getElementById('productos-container');
            let newElement = container.children[0].cloneNode(true);
            container.appendChild(newElement);
        }
    </script>
@endsection
