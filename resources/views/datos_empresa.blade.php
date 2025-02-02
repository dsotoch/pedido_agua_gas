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
                        <input type="file" id="logo" name="logo" accept="image/png, image/jpeg"
                            class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('logo')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Logo Cuadrado -->
                    <div>
                        <label for="logo_vertical" class="block text-gray-600 font-medium mb-1">Logo Cuadrado</label>
                        <input type="file" id="logo_vertical" name="logo_vertical" accept="image/png, image/jpeg"
                            class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('logo_cuadrado')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Galería de imágenes (ocupa 2 columnas) -->
                    <div class="mt-4">
                        <label for="imagenes" class="block text-gray-600 font-medium mb-1">Galería de Imágenes</label>
                        <input type="file" id="imagenes" name="imagenes[]" multiple accept="image/png, image/jpeg"
                            class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="mb-2 text-naranja">Puedes seleccionar múltiples imágenes</p>
                        @error('imagenes')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Checkboxes -->
                    <div class="mb-4">
                        <span class="block text-gray-600 font-medium mb-1">Servicios Ofrecidos</span>

                        <label class="inline-flex items-center">
                            <input type="checkbox" name="servicios[]" value="agua" class="form-checkbox text-blue-500"
                                {{ in_array('agua', $serviciosSeleccionados) ? 'checked' : '' }}>
                            <span class="ml-2">Agua</span>
                        </label>

                        <label class="inline-flex items-center ml-6">
                            <input type="checkbox" name="servicios[]" value="gas" class="form-checkbox text-blue-500"
                                {{ in_array('gas', $serviciosSeleccionados) ? 'checked' : '' }}>
                            <span class="ml-2">Gas</span>
                        </label>

                        @error('servicios')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Descripción (ocupa 2 columnas) -->
                <div class="mt-4">
                    <label for="descripcion" class="block text-gray-600 font-medium mb-1">Descripción</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Breve descripción del negocio" rows="3"
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
    </div>
@endsection
