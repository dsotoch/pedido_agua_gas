@extends('layout')
@section('cuerpo')
    <div class="w-full flex bg-white shadow-lg items-center pl-2 pt-2 pb-2 pr-0 md:justify-normal justify-between">
        <div class="flex md:w-1/3 w-1/2 md:pl-28">
            <a href="/"><img src="{{ asset('imagenes/entrega.png') }}" alt=""
                    class="object-contain  ml-auto  w-[255px] h-[68px] "></a>
        </div>


    </div>
    <div class="md:flex grid bg-gradient-to-br from-secundario  to-principal text-base md:p-10 p-2 items-center">
        <!-- Contenedor de textos -->
        <div class="p-6 md:w-1/2  w-full mx-auto bg-white shadow-md rounded" id="contenedorRegistroEmpresa"required>
            <h2 class="text-2xl font-normal text-gray-700 mb-6">Registrar Distribuidora</h2>
            @if ($errors->has('mensaje'))
                <div id="mensaje" class="p-4 m-4 bg-red-500 text-white text-md">
                    {{ $errors->first('mensaje') }}
                </div>
            @endif
            <form id="formRegistroEmpresa" enctype="multipart/form-data" method="POST"
                action="{{ route('empresa.crear') }}">
                @csrf
                <!-- Nombre Distribuidora -->
                <div class="mb-4">
                    <label for="nombre" class="block text-gray-600 font-medium mb-1">Nombre de la Distribuidora</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej. Gas Perú"
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('nombre') }}">
                    @error('nombre')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-4">
                    <label for="slug" class="block text-gray-600 font-medium mb-1">Slug</label>
                    <input type="text" id="slug" name="slug" placeholder="Ej. gas-peru"
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('slug') }}" required>
                    @error('slug')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Dirección del negocio -->
                <div class="mb-4">
                    <label for="direccion" class="block text-gray-600 font-medium mb-1">Dirección del Negocio</label>
                    <input type="text" id="direccion" name="direccion" placeholder="Ej. Av. Principal 123, Lima"
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('direccion') }}" required>
                    @error('direccion')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="mb-4">
                    <label for="descripcion" class="block text-gray-600 font-medium mb-1">Descripción</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Breve descripción del negocio" rows="3"
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Logo -->
                <div class="mb-4">
                    <label for="logo" class="block text-gray-600 font-medium mb-1">Logo Horizontal</label>
                    <input type="file" id="logo" name="logo" accept="image/png, image/jpeg , image/webp"
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('logo')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Logo Cuadrado -->
                <div class="mb-4">
                    <label for="logo_vertical" class="block text-gray-600 font-medium mb-1">Logo Cuadrado</label>
                    <input type="file"  name="logo_vertical" accept="image/png, image/jpeg, image/webp"
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('logo_vertical')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Galería de imágenes -->
                <div class="mb-4">
                    <label for="galeria" class="block text-gray-600 font-medium mb-1">Galería de Imágenes</label>
                    <input type="file" id="imagenes" name="imagenes[]" multiple accept="image/png, image/jpeg, image/webp"
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <p class="mb-2 text-naranja">Puedes seleccionar múltiples imágenes</p>
                    @error('imagenes')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- WhatsApp URL -->
                <div class="mb-4">
                    <label for="whatsapp" class="block text-gray-600 font-medium mb-1">WhatsApp URL</label>
                    <input type="url" id="whatsapp" name="whatsapp" placeholder="https://wa.me/tu-numero"
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('whatsapp') }}" required>
                    @error('whatsapp')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Facebook URL -->
                <div class="mb-4">
                    <label for="facebook" class="block text-gray-600 font-medium mb-1">Facebook URL</label>
                    <input type="url" id="facebook" name="facebook" placeholder="https://facebook.com/tu-pagina"
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('facebook') }}" required>
                    @error('facebook')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Número para llamadas -->
                <div class="mb-4">
                    <label for="telefono" class="block text-gray-600 font-medium mb-1">Número para Llamadas</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="Ej. +51 987 654 321"
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('telefono') }}" required>
                    @error('telefono')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Checkboxes -->
                <div class="mb-4">
                    <span class="block text-gray-600 font-medium mb-1">Servicios Ofrecidos</span>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="servicios[]" value="agua" class="form-checkbox text-blue-500"
                            {{ is_array(old('servicios')) && in_array('agua', old('servicios')) ? 'checked' : '' }}>
                        <span class="ml-2">Agua</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="checkbox" name="servicios[]" value="gas" class="form-checkbox text-blue-500"
                            {{ is_array(old('servicios')) && in_array('value', old('servicios')) ? 'checked' : '' }}>
                        <span class="ml-2">Gas</span>
                    </label>
                    @error('servicios')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Código Entrega.pe -->
                <div class="mb-4">
                    <label for="codigo" class="block text-gray-600 font-medium mb-1">Código Entrega.pe</label>
                    <input type="text" id="codigo" name="codigo" placeholder="Ej. 9wX f54 3d1"
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('codigo') }}" required>
                    @error('codigo')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botón -->
                <button type="submit"
                    class="w-full p-3 text-white text-base bg-naranja rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <i class="fas fa-save"></i> Registrar
                </button>
            </form>

        </div>



    </div>
    <!----Footer--->
    <div class="bg-white  text-color-text w-full text-center flex flex-col">
        <div class="flex mx-auto mt-[40px] mb-2">
            <a href="https://wa.me/921233721"><img class="w-[40px] h-[50px] m-[10px]"
                    src="{{ asset('imagenes/ws.svg') }}" alt=""></a>
            <a href="https://facebook.com/"><img src="{{ asset('imagenes/fa.svg') }} "class="w-[40px] h-[50px] m-[10px]"
                    alt=""></a>
            <a href="tel:921233721"><img class="w-[40px]  h-[50px] m-[10px]" src="{{ asset('imagenes/tel.svg') }}"
                    alt=""></a>
        </div>
        <div class="mb-[40px] mt-4">
            <p class="text-[15px]">Copyright © 2024 <span class="text-naranja font-bold">
                    <a href="https://entrega.pe">Entrega.pe</a></span> Todos los Derechos Reservados.
              </p>
        </div>

    </div>
@endsection
