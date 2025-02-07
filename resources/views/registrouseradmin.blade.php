@extends('layout')
@section('cuerpo')
    <div class="w-full flex bg-white shadow-lg items-center pl-2 pt-2 pb-2 pr-0 md:justify-normal justify-between">
        <div class="flex md:w-1/3 w-1/2 md:pl-28">
            <a href="/"><img src="{{ asset('imagenes/entrega.png') }}" alt=""
                    class="object-contain  ml-auto  w-[255px] h-[68px] "></a>
        </div>


    </div>
    <div class="grid bg-gradient-to-br from-secundario to-principal md:p-10 p-2 items-center">
        @if (session()->has('mensaje'))
            <div id="mensaje" class="text-center p-4 m-4 bg-green-500 text-white text-md">
                {{ session('mensaje') }}
            </div>
        @endif
        @if ($errors->has('mensaje'))
            <div id="mensaje" class=" text-center p-4 m-4 bg-red-500 text-white text-md">
                {{ $errors->first('mensaje') }}
            </div>
        @endif
        <div class="p-6 md:w-1/2 w-full mx-auto  bg-white shadow-md rounded" id="contenedorRegistroAdministrador">
            <h2 class="text-2xl font-normal text-center text-gray-700 mb-6">Registrar Administrador</h2>

            <form id="formRegistroAdministrador" method="POST" action="{{ route('empresa.admin') }}">
                @csrf <!-- Para incluir el token de CSRF en el formulario -->

                <!-- Teléfono -->
                <div class="mb-4">
                    <label for="telefono-admin" class="block text-gray-600 font-normal mb-1">Teléfono</label>
                    <input type="tel" id="telefono-admin" name="telefono" value="{{ old('telefono') }}"
                        placeholder="Ej. 987 654 321" required
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" >
                    @error('telefono')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Dni -->
                <div class="mb-4">
                    <label for="dni-admin" class="block text-gray-600 font-normal mb-1">DNI</label>
                    <input type="tel" id="dni-admin" name="dni" value="{{ old('dni') }}"
                        placeholder="Ej.18082298" required
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" >
                    @error('dni')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nombres -->
                <div class="mb-4">
                    <label for="nombres" class="block text-gray-600 font-normal mb-1">Nombres</label>
                    <input type="text" id="nombres" name="nombres" value="{{ old('nombres') }}" placeholder="Ej. Juan"
                        required class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('nombres')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Apellidos -->
                <div class="mb-4">
                    <label for="apellidos" class="block text-gray-600 font-normal mb-1">Apellidos</label>
                    <input type="text" id="apellidos" name="apellidos" value="{{ old('apellidos') }}"
                        placeholder="Ej.Pérez" required
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('apellidos')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Correo -->
                <div class="mb-4">
                    <label for="correo" class="block text-gray-600 font-normal mb-1">Correo Electronico</label>
                    <input type="email" id="correo" name="correo" value="{{ old('correo') }}"
                        placeholder="Ej.dsoto@gmail.com" required
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('correo')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Contraseña -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-600 font-normal mb-1">Contraseña</label>
                    <input type="password" id="password" name="password" value="{{ old('password') }}"
                        placeholder="********" required
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('password')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tipo de Usuario -->
                <div class="mb-4">
                    <label for="tipo" class="block text-gray-600 font-normal mb-1">Tipo de Usuario</label>
                    <select id="tipo" name="tipo" required
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="admin" {{ old('tipo') == 'admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                    @error('tipo')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <input type="text" class="hidden" name="empresa_id" id="empresa_id" value="{{session('empresa')}}">

                @error('empresa_id')
                    <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                @enderror
                <!-- Botón -->
                <button type="submit"
                    class="w-full p-3 text-white text-base bg-naranja rounded-lg  focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <i class="fas fa-save"></i> Registrar Administrador
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
                    <a href="https://entrega.pe">Entrega.pe</a></span> Todos los Derechos Reservados. |
                Desarrollo de Techub - Diego Soto</p>
        </div>

    </div>
@endsection
