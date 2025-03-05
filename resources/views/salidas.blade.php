@extends('layout-cuenta')
@section('logica')
    <div
        class="container mx-auto md:w-full w-screen font-sans p-4 md:p-[20px] bg-white shadow-lg rounded-lg text-color-titulos-entrega">
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
                <input type="text" id="empresaId_salida" value="{{ $empresa->id }}" class="hidden">
                <input type="text" id="placa_salida" name="placa_modificada" class="hidden">

                <div id="vehiculo-container w-full">
                    <div class="flex items-center  w-3/4 md:space-x-3 space-x-1">
                        <select name="vehiculo_id" id="vehiculo-select" required class="w-full p-2 border rounded-lg">
                            @if (count($vehiculos) > 0)
                                <option value="" disabled selected>Seleccione un vehiculo </option>

                                @foreach ($vehiculos as $vehiculo)
                                    <option value="{{ $vehiculo->placa }} - {{ $vehiculo->repartidor?->id }}">
                                        {{ $vehiculo->placa }} - {{ $vehiculo->repartidor?->persona->nombres }}</option>
                                @endforeach
                            @else
                                <option value="" disabled selected>No hay vehículos registrados</option>
                            @endif
                        </select>
                        <div class="w-[15%] flex space-x-2"><button type="button" class="transform hover:scale-105"
                                id="btn_editar_vehiculo"><i class="fas fa-edit text-2xl"></i></button>
                            <button type="button" class="transform hover:scale-105" id="btn_eliminar_vehiculo"><i
                                    class="fas fa-trash text-2xl"></i></button>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div id="modal_vehiculos"
                        class="fixed inset-0  bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
                        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                            <h2 class="text-xl font-bold mb-4">Modificar Vehiculo</h2>

                            <p class="mb-2 flex space-x-2 items-center"><strong>Vehículo:</strong><input type="text"
                                    id="modal-vehiculo_input" class="text-left border p-2"> <span id="modal-vehiculo"
                                    class="hidden"></span></p>
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
                                        {{ $producto->nombre . ' - Normal' }}
                                    </option>
                                    <option value="{{ $producto->id . '_premium' }}" data-tipo="gas"
                                        data-id="{{ $producto->id }}">
                                        {{ $producto->nombre . ' - Premium' }}
                                    </option>
                                @else
                                    <option value="{{ $producto->id }}" data-tipo="{{ $producto->categoria }}"
                                        data-id="{{ $producto->id }}">
                                        {{ $producto->nombre }}
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
        <h2 class="text-2xl font-bold mt-8 mb-4">Stock Actual de Repartidores del dia</h2>
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
                    @foreach ($salidas as $item)
                        @foreach ($item->stock as $salida)
                            <tr class="border text-center">
                                <td class="p-2">{{ $item->fecha }}</td>
                                <td class="p-2">{{ $item->placa }}</td>
                                <td class="p-2">{{ $item->repartidor }}</td>

                                <td class="p-2">
                                    @php
                                        // Decodificar el JSON de productos
                                        $productos = json_decode($salida->productos, true);
                                        $productos_formateados = []; // Nuevo array para almacenar los productos formateados

                                        foreach ($productos as $item) {
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
                                            }

                                            if ($producto) {
                                                $productos_formateados[] = [
                                                    'id' => $item['producto_id'],
                                                    'nombre' => $producto->nombre . $tipo,
                                                    'cantidad' => $item['cantidad'],
                                                ];
                                            } else {
                                                $productos_formateados[] = [
                                                    'id' => 'null',
                                                    'nombre' => 'Producto no encontrado',
                                                    'cantidad' => $item['cantidad'],
                                                ];
                                            }
                                        }
                                        $productos_formateados = json_encode(
                                            $productos_formateados,
                                            JSON_UNESCAPED_UNICODE,
                                        );
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
                                                    $tipo = substr(
                                                        $item['producto_id'],
                                                        strpos($item['producto_id'], '_'),
                                                    );
                                            } @endphp

                                            @if ($producto)
                                                <li>{{ $item['cantidad'] }} *
                                                    {{ $producto->nombre . $tipo }}</li>
                                            @else
                                                <li>{{ $item['cantidad'] }} * Producto no encontrado</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="p-2">
                                    <div class="flex justify-center space-x-2">
                                        <button type="button" id="btn_ver_salida" data-id="{{ $salida->salida_id }}">
                                            <i class="fas fa-eye"></i></button>
                                        <button type="button" data-id="{{ $salida->salida_id }}"
                                            data-productos="{{ $productos_formateados }}"> <i
                                                class="fas fa-edit"></i></button>

                                        <form action="{{ route('salidas.eliminar') }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <input type="text" hidden name='salida_id'
                                                value="{{ $salida->salida_id }}">
                                            <button type="submit"> <i class="fas fa-trash"></i></button>

                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="modal_editar_salida"
            class="fixed inset-0  justify-center items-center w-full z-50 bg-black bg-opacity-50 hidden">
            <div
                class="w-11/12 md:w-1/2 bg-white rounded-lg shadow-lg max-h-[90vh] overflow-y-auto transform transition-all p-4">


                <!-- Encabezado -->
                <div class="bg-tarjetas text-white p-4 flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Editar Salida de Productos</h2>
                    <button id="cerrar-modal" onclick="cerrarmodal()"
                        class="text-white text-2xl font-bold hover:text-gray-300">&times;</button>
                </div>

                <!-- Contenido del modal -->
                <div class="p-4 space-y-4 ">
                    <form action="{{ route('salidas.editar') }}" method="post">
                        <div class="p-4">
                            <div class="mb-4 w-full">
                                <label class="block font-semibold">Nuevo Producto</label>
                                <div id="productos-container2" class="w-full">
                                    <div class="contenedor_producto flex  mb-2 border  w-full">
                                        <select id="select_productos_salidas_nuevo" name="productos[]" 
                                            class="w-1/2 md:w-3/5 p-2 border rounded-lg">
                                            <option value="" selected >
                                                Seleccione un Producto
                                            </option>
                                            @foreach ($productos_sele as $pro)
                                                @if ($pro->categoria === 'gas')
                                                    <option value="{{ $pro->id . '_normal' }}" data-tipo="gas"
                                                        data-id="{{ $pro->id }}">
                                                        {{ $pro->nombre . ' - Normal' }}
                                                    </option>
                                                    <option value="{{ $pro->id . '_premium' }}" data-tipo="gas"
                                                        data-id="{{ $pro->id }}">
                                                        {{ $pro->nombre . ' - Premium' }}
                                                    </option>
                                                @else
                                                    <option value="{{ $pro->id }}"
                                                        data-tipo="{{ $pro->categoria }}" data-id="{{ $pro->id }}">
                                                        {{ $pro->nombre }}
                                                    </option>
                                                @endif
                                            @endforeach

                                        </select>
                                        <div class="flex flex-col p-2 space-y-2">
                                            <button onclick="eliminar()"
                                                class="btneliminar border p-2 rounded font-semibold border-color-titulos-entrega text-color-titulos-entrega hover:scale-x-105">
                                                Eliminar
                                            </button>


                                            <input type="number" name="cantidades[]" min="0" required
                                                class="w-full p-2 border rounded-lg" placeholder="Cantidad"
                                                value="0">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" onclick="agregarProducto2()"
                                    class="mt-2 border-2 nuevoproducto  text-color-text border-color-text font-semibold px-3 py-1 rounded">Añadir
                                    Producto</button>
                            </div>
                        </div>
                        @csrf
                        <div id="formulario-edicion-productos"></div>


                        <!-- Pie del modal -->
                        <div class="bg-gray-100 p-4 flex justify-end space-x-2">

                            <button type="submit" class="px-4 py-2 bg-naranja text-white rounded-lg ">Guardar</button>
                        </div>
                    </form>

                </div>


            </div>
        </div>
        <!-- Modal -->
        <div id="modal_ver_salida"
            class="fixed inset-0  items-center justify-center w-full h-full bg-black bg-opacity-50 hidden z-50">
            <div class="bg-white w-11/12 md:w-1/2 rounded-lg shadow-lg overflow-hidden">
                <!-- Encabezado -->
                <div class="bg-tarjetas text-white p-4 flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Detalles de la Salida</h2>
                    <button id="cerrar_modal"
                        onclick="document.getElementById('modal_ver_salida').classList.add('hidden');"
                        class="text-white text-2xl font-bold hover:text-gray-300">&times;</button>
                </div>

                <!-- Contenido -->
                <div class="p-4 space-y-4">
                    <h3 class="text-gray-700 font-semibold">Stock Total de Productos en la Salida

                    </h3>
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border p-2">Producto</th>
                                <th class="border p-2">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody id="tabla_productos">
                            <!-- Aquí se insertarán dinámicamente los productos -->
                        </tbody>
                    </table>
                </div>

                <!-- Pie del modal -->
                <div class="bg-gray-100 p-4 flex justify-end">
                    <button id="cerrar_modal_footer"
                        class="px-4 py-2 bg-naranja text-white rounded-lg transform hover:scale-105">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>

    </div>



    {{-- Script para añadir más productos dinámicamente --}}
    <script>
        function agregarProducto() {
            let container = document.getElementById('productos-container');
            let newElement = container.children[0].cloneNode(true);
            container.appendChild(newElement);
        }

        function agregarProducto2() {
            let container = document.getElementById('productos-container2');
            let newElement = container.children[0].cloneNode(true);
            container.appendChild(newElement);
        }

        function eliminar() {
            document.querySelector('.btneliminar').closest('.contenedor_producto').remove();

            let container = document.getElementById('productos-container2');

            if (container.querySelectorAll('select').length === 0) {
                document.querySelector('.nuevoproducto').disabled = true;
            }
        }

        function cerrarmodal() {
            document.getElementById('modal_editar_salida').classList.add('hidden');
            let container = document.getElementById('productos-container2');

            if (container.querySelectorAll('select').length === 0) {
                location.reload();
            }
        }
    </script>
@endsection
