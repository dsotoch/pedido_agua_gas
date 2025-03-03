@extends('layout-cuenta')
@section('logica')
    <div class="container mx-auto ">
        <div class="w-full mx-auto bg-white shadow-md rounded p-6" id="contenedorRegistroEmpresa">
            <h2 class="text-2xl font-normal text-gray-700 mb-6">Modificar datos de la Distribuidora</h2>
            @if (session('mensaje'))
                <div id="mensaje" class="p-4 m-4 border border-green-500 text-green-500 text-base">
                    {{ session('mensaje') }}
                </div>
            @endif

            @if ($errors->has('mensaje'))
                <div id="mensaje" class="p-4 m-4 border border-red-500 text-red-500 text-base">
                    {{ $errors->first('mensaje') }}
                </div>
            @endif

            <form id="form_editar_datos_empresa" enctype="multipart/form-data" method="POST"
                action="{{ route('empresa.editar') }}">
                @method('put')
                @csrf

                <!-- Contenedor de grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Nombre Distribuidora -->
                    <div>
                        <label for="nombre" class="block text-gray-600 font-medium mb-1">Nombre de la
                            Distribuidora</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Ej. Gas Perú"
                            value="{{ $empresa->nombre }}"
                            class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('nombre')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-gray-600 font-medium mb-1">Slug</label>
                        <input type="text" id="slug" name="slug" placeholder="Ej. gas-peru"
                            value="{{ $empresa->dominio }}"
                            class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        @error('slug')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Dirección del negocio -->
                    <div>
                        <label for="direccion" class="block text-gray-600 font-medium mb-1">Dirección del Negocio</label>
                        <input type="text" id="direccion" name="direccion" placeholder="Ej. Av. Principal 123, Lima"
                            class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ $empresa->direccion }}" required>
                        @error('direccion')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- WhatsApp URL -->
                    <div>
                        <label for="whatsapp" class="block text-gray-600 font-medium mb-1">WhatsApp URL</label>
                        <input type="url" id="whatsapp" name="whatsapp" placeholder="https://wa.me/tu-numero"
                            class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ $empresa->whatsapp }}" required>
                        @error('whatsapp')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Facebook URL -->
                    <div>
                        <label for="facebook" class="block text-gray-600 font-medium mb-1">Facebook URL</label>
                        <input type="url" id="facebook" name="facebook" placeholder="https://facebook.com/tu-pagina"
                            class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ $empresa->facebook }}" required>
                        @error('facebook')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Número para llamadas -->
                    <div>
                        <label for="telefono" class="block text-gray-600 font-medium mb-1">Número para Llamadas</label>
                        <input type="tel" id="telefono" name="telefono" placeholder="Ej. +51 987 654 321"
                            class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ $empresa->telefono }}" required>
                        @error('telefono')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Logo -->
                    <div>
                        <label for="logo" class="block text-gray-600 font-medium mb-1">Logo Horizontal</label>
                        <input type="file" id="logo" name="logo" accept="image/png, image/jpeg, image/webp"
                            class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('logo')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Logo Cuadrado -->
                    <div>
                        <label for="logo_vertical" class="block text-gray-600 font-medium mb-1">Logo Cuadrado</label>
                        <input type="file" id="logo_vertical" name="logo_vertical"
                            accept="image/png, image/jpeg, image/webp"
                            class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('logo_cuadrado')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Galería de imágenes (ocupa 2 columnas) -->
                    <div class="mt-4">
                        <label for="imagenes" class="block text-gray-600 font-medium mb-1">Galería de Imágenes</label>
                        <input type="file" id="imagenes" name="imagenes[]" multiple
                            accept="image/png, image/jpeg, image/webp"
                            class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="mb-2 text-naranja">Puedes seleccionar múltiples imágenes</p>
                        @error('imagenes')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <!-- Checkboxes -->
                        <div class="mb-4">
                            <span class="block text-gray-600 font-medium mb-1">Servicios Ofrecidos</span>

                            <label class="inline-flex items-center">
                                <input type="checkbox" name="servicios[]" value="agua" class="form-checkbox text-blue-500"
                                    {{ in_array('agua', $serviciosSeleccionados) ? 'checked' : '' }}>
                                <span class="ml-2">Agua</span>
                            </label>

                            <label class="inline-flex items-center ml-6">
                                <input type="checkbox" name="servicios[]" value="gas"
                                    class="form-checkbox text-blue-500"
                                    {{ in_array('gas', $serviciosSeleccionados) ? 'checked' : '' }}>
                                <span class="ml-2">Gas</span>
                            </label>

                            @error('servicios')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="minutos" class="block text-gray-600 font-medium mb-1">Tiempo de Entrega
                                (minutos)</label>
                            <input type="number" id="minutos_empresa" name="minutos"
                                class="border rounded p-2 text-center" value="{{ $empresa->tiempo ?? 20 }}">
                            @error('minutos')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-base font-medium text-gray-700 mb-1">Horario de Atención</label>
                    <div class="flex space-x-2">
                        <input type="text" name="empresa_id" hidden value='{{ $empresa->id }}'>
                        <div class="border p-2">
                            <label for="dia">Día:</label>
                            <select id="select_dia_conf">
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miércoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sabado">Sabado</option>
                                <option value="Domingo">Domingo</option>

                            </select>
                        </div>

                        <div class="border p-2">
                            <label for="hora_inicio">Hora Inicio:</label>
                            <input type="time" id="hora_inicio_conf" >
                        </div>
                        <div class="border p-2">
                            <label for="hora_fin">Hora Cierre:</label>
                            <input type="time" id="hora_fin_conf" >
                        </div>



                        <button type="button" id="btn_guardar_horario_conf"
                            class="p-2 rounded bg-naranja text-white hover:border-2 hover:border-red-500">Guardar</button>
                    </div>
                </div>
                <div class="mt-4">
                    <table class="w-full" id="tabla_horario_conf">
                        <thead class="bg-tarjetas text-white ">
                            <th class="p-2">Dia</th>
                            <th class="p-2">Hora de Inicio</th>
                            <th class="p-2">Hora de Cierre</th>
                            <th class="p-2">Acción</th>
                        </thead>
                        <tbody>
                            @foreach ($horarios as $item)
                                <tr class="border-b-2">
                                    <td  class="text-center">
                                        <input type="text" name="dia[]" class="p-2 border w-full text-center" readonly value="{{ $item->dia }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="time" name="hora_inicio[]"  class="p-2 border w-full text-center" value="{{ $item->hora_inicio }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="time" name="hora_fin[]"  class="p-2 border w-full text-center" value="{{ $item->hora_fin }}">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn-eliminar border text-white bg-red-500 p-2 rounded">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                <!-- Descripción (ocupa 2 columnas) -->
                <div class="mt-4">
                    <label for="descripcion" class="block text-gray-600 font-medium mb-1">Descripción de la
                        Distribuidora</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Breve descripción del negocio" rows="15"
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ $empresa->descripcion }}</textarea>
                    @error('descripcion')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>



                <input type="text" value="{{ $empresa->id }}" id="empresa_id" name="empresa_id" class="hidden">

                <!-- Botón -->
                <button type="submit"
                    class="w-auto mx-auto flex justify-center items-center mt-6 p-4 transform hover:scale-105 text-white text-base bg-naranja rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    Actualizar <i class="fas fa-save ml-2"></i>
                </button>
            </form>
        </div>
        <hr>
        <div class="w-full bg-white shadow-md rounded p-6 text-center">
            <h3 class="text-2xl font-normal text-gray-700 mb-6">Configuración de Diseño</h3>
            <p class="text-gray-600 mb-4">Arrastra los productos para modificar su orden.</p>

            <ul id="sortable-list" class="space-y-2">
                @foreach ($productos as $item)
                    <li data-id="{{ $item->id }}"
                        class="bg-gray-100 p-4 rounded shadow flex items-center justify-between cursor-move">
                        <span>{{ $item->nombre.' '.$item->descripcion }}</span>
                        <svg class="w-6 h-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                        </svg>
                    </li>
                @endforeach
            </ul>

            <button id="guardarOrden" class="mt-4 bg-naranja text-white p-4 text-center rounded shadow">
                Guardar Orden
            </button>
        </div>



    </div>
@endsection
