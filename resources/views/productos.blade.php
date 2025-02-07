@extends('layout-cuenta')
@section('logica')
    <div class="flex flex-col w-full h-full">
        <div class="container mx-auto p-5 " id="divproductosadmin">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Card de productos registrados -->
                <div class="bg-white rounded-lg shadow-lg p-5 ">
                    <h2 class="text-2xl font-bold font-cabin text-color-titulos-entrega mb-4">Productos Registrados</h2>
                    <div class="space-y-4" id="contenedorproductos">
                        @if ($empresa->productos && $empresa->productos->isnotEmpty())
                            @foreach ($empresa->productos as $item)
                                <!-- Producto 1 -->
                                <div
                                    class="pro flex items-center text-color-titulos-entrega justify-between border-b pb-4 productosadmin">
                                    <div class="space-y-2 ">
                                        <h3 class="text-lg font-semibold ">
                                            <i class="fas fa-box "></i> #{{ $item->id }}
                                        </h3>
                                        <p class="">
                                            <i class="fas fa-tags"></i>
                                            {{ $item->descripcion }}
                                        </p>
                                        <p class=" font-bold">
                                            <i class="fas fa-dollar-sign "></i> Precio:
                                            S/{{ $item->precio }}
                                        </p>
                                        <p class="">
                                            <i class="fas fa-tags "></i> Promociones:
                                            @foreach ($item->promociones as $pro)
                                                S/{{ $pro->precio_promocional }} x {{ $pro->cantidad }} Un. |
                                            @endforeach
                                        </p>
                                        <p><i class="fa-solid fa-cart-shopping"></i> Disponible para la venta:
                                            {{$item->comercializable ? 'SI':'NO'}}
                                        </p>
                                        <form data-id="{{ $item->id }}" class="flex justify-start"
                                            action="{{ route('eliminar.producto', ['id' => $item->id]) }}" method="post"
                                            class="flex justify-center">
                                            <button type="submit"
                                                class="m-2 p-3 rounded border-2 border-color-titulos-entrega  text-color-titulos-entrega"><i
                                                    class="fas fa-trash"></i> Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="m-2 p-2 text-base" id="mensaje_sin_productos_registrados">No Existen Productos
                                Registrados</p>
                        @endif



                    </div>
                </div>

                <!-- Formulario para agregar nuevos productos -->
                <div class="bg-white rounded-lg shadow-lg p-5">
                    <h2 class="text-2xl font-bold text-color-titulos-entrega font-cabin mb-4">Crear Nuevo Producto</h2>
                    <form id="formproductoadmincrear" class="space-y-4" action="{{ route('crear.producto') }}"
                        method="POST">

                        <!-- Nombre del producto -->
                        <div>
                            <label class="block text-base font-medium text-color-titulos-entrega">Nombre del
                                Producto</label>
                            <input type="text" name="nombre"
                                class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Producto">
                        </div>
                        <!-- Descripción -->
                        <div>
                            <label class="block text-base font-medium text-gray-700">Descripción</label>
                            <textarea name="descripcion"
                                class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                rows="3" placeholder="Descripción del producto"></textarea>
                        </div>
                        <!-- Precio -->
                        <div>
                            <label class="block text-base font-medium text-gray-700">Precio Unitario</label>
                            <input type="number" name="precio"
                                class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Precio">
                        </div>
                        <div class="space-y-4">
                            <h2 class="text-lg font-medium text-gray-800">Configuración de Promoción
                                (Opcional)</h2>

                            <!-- Campo para configurar "Por cada cuántos productos" -->
                            <div>
                                <label for="productosPorCada" class="block text-base font-medium text-gray-700">
                                    Por cada cuántos productos
                                </label>
                                <input type="number" id="productosPorCada" name="productos_por_cada"
                                    class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Ejemplo: 5 (opcional)">
                            </div>

                            <!-- Campo para configurar "Cuántos productos gratis" -->
                            <div>
                                <label for="productosGratis" class="block text-base font-medium text-gray-700">
                                    Producto gratis
                                </label>
                                <select id="productosGratis" name="productos_gratis"
                                    class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="" selected>Seleccione...</option>
                                    @if ($empresa->productos && $empresa->productos->isnotEmpty())
                                        @foreach ($empresa->productos as $item)
                                            <option value="{{ $item->descripcion }}">{{ $item->descripcion }}</option>
                                        @endforeach
                                    @else
                                    <option value="mismo">Mismo Producto a Registrar</option>

                                    @endif
                                </select>
                            </div>
                        </div>


                        <div id="formpromocionproductoadmin" class="space-y-4">

                            <!-- Promoción -->
                            <div class="bg-white rounded-lg shadow-lg p-5">
                                <label class="block text-base font-medium text-gray-700 mb-2">
                                    <i class="fas fa-tags text-blue-500"></i> Promociones (Ej: 2 Un. Precio
                                    Unitario S/40)
                                </label>
                                <!-- Selección de Unidades -->
                                <div class="mb-4">
                                    <label class="block text-base font-medium text-gray-700">Unidades</label>
                                    <select name="unidades" id="unidades"
                                        class="p-2 cursor-pointer mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="" disabled selected>Seleccione las unidades
                                        </option>
                                        <!-- Opciones dinámicas de 1 a 30 -->
                                        <script>
                                            for (let i = 2; i <= 30; i++) {
                                                document.write(`<option value="${i}">${i} Unidades</option>`);
                                            }
                                        </script>
                                    </select>
                                </div>

                                <!-- Ingresar el Precio -->
                                <div class="mb-4">
                                    <label class="block text-base font-medium text-gray-700">Precio</label>
                                    <input type="number" min="1" step="0.01" name="preciopromocion"
                                        id="preciopromocion"
                                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Ingrese el precio (Ej: 40.00)">
                                </div>

                                <!-- Botón para guardar promoción -->
                                <div class="flex justify-center md:justify-end mb-2">
                                    <button type="button" id="btnpromocionadmin"
                                        class="disabled:bg-gray-500 disabled:text-white  text-base border-2 border-color-titulos-entrega text-color-titulos-entrega rounded px-4 py-3  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Agregar Promoción
                                    </button>
                                </div>
                                <div class=" flex-col space-y-2 hidden" id="divdetallespromocionesproductoadmin">
                                    <p class="text-md font-medium text-gray-700 mb-3">Detalles de
                                        Promociones
                                    </p>
                                    <div class='flex flex-col text-gray-700' id="divdetallespromocionesadmin">
                                    </div>
                                    <br>
                                    <button type="button" class="text-red-500  hover:text-red-400 font-medium p-4"
                                        id="btnresetearpromociones"><i class="fas fa-trash m-2"></i>Resetear
                                        Promociones</button>
                                    <hr>
                                </div>
                            </div>
                        </div>

                        <!-----CHECK producto vendible--->
                        <div class="flex text-color-titulos-entrega space-x-2 p-2">
                            <label for="estado">Artículo Comercializable</label>
                            <input type="checkbox" name="estado" id="estado" class="rounded w-[20px] " checked>
                        </div>
                        <!-- Botón -->

                        <div>
                            <button type="submit"
                                class="disabled:bg-gray-500 disabled:text-white w-full bg-naranja text-base text-white rounded py-3  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-plus"></i> Crear Producto
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>



    </div>
@endsection
