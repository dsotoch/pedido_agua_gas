@extends('layout-cuenta')

@section('logica')
    <div class="container mx-auto mt-5 md:p-0 p-4">
        <h1 class="text-2xl font-cabin font-medium mb-4 text-color-titulos-entrega">Mis Datos</h1>
        @if (session('success'))
            <p class="mt-4 text-green-500 text-base w-full min-w-full bg-white p-2 text-center border border-green-500">
                {{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <div class="mt-4 text-red-500 text-base w-full min-w-full bg-white p-2 text-center border border-red-500">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('usuario.update', ['id' => $usuario->id]) }}" method="POST"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 text-color-titulos-entrega text-base font-sans">
            @csrf
            @method('PUT')
            <!-- dni -->
            <div class="mb-4">
                <label for="dni" class="block text-gray-700 text-sm font-bold mb-2">DNI:</label>
                <input type="text" name="dni" id="dni" value="{{ old('dni', $usuario->persona->dni) }}"
                    readonly
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <!-- celular -->
            <div class="mb-4">
                <label for="celular" class="block text-gray-700 text-sm font-bold mb-2">Celular:</label>
                <input type="text" name="celular" id="celular" value="{{ old('celular', $usuario->usuario) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <!-- Nombre -->
            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $usuario->persona->nombres) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <!-- Correo Electrónico -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Correo Electrónico:</label>
                <input type="email" name="email" id="email" value="{{ old('email', $usuario->persona->correo) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <!-- Dirección -->
            <div class="mb-4">
                <label for="direccion" class="block text-gray-700 text-sm font-bold mb-2">Dirección:</label>
                <input type="text" name="direccion" id="direccion"
                    value="{{ old('direccion', $usuario->persona->direccion) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <!-- Nota -->
            <div class="mb-4">
                <label for="nota" class="block text-gray-700 text-sm font-bold mb-2">Referencia:</label>
                <textarea name="nota" id="nota" rows="3"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('nota', $usuario->persona->nota) }}</textarea>
            </div>

            <!-- Dirección2 -->
            <div class="mb-4">
                <label for="nota" class="block text-gray-700 text-sm font-bold mb-2">Otras Direcciones:</label>

                <div id="direcciones-container" class="flex flex-col space-y-2">
                    @if ($usuario->direcciones->count() > 0)
                        @foreach ($usuario->direcciones as $index => $direccion)
                            <div class="direccion-item">
                                <input type="text" class="p-2 border-2 w-1/3"
                                    name="direcciones[{{ $index }}][direccion]" value="{{ $direccion->direccion }}"
                                    placeholder="Dirección" required>
                                <input type="text" class="p-2 border-2 md:w-1/3 w-1/2"
                                    name="direcciones[{{ $index }}][referencia]"
                                    value="{{ $direccion->referencia }}" placeholder="Referencia" required>
                                <button type="button" class="editarDireccion">✏️</button>
                                <button type="button" class="eliminarDireccion">🗑️</button>
                            </div>
                        @endforeach
                    @endif
                    <!-- Aquí se agregarán dinámicamente las direcciones -->
                </div>
                <button type="button" id="agregarDireccion"
                    class="mt-2 border transform hover:scale-105 w-1/3 p-3 text-color-titulos-entrega font-semibold text-base rounded border-color-titulos-entrega">Agregar
                    otra
                    dirección</button>
            </div>
            <!-- Contraseña -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña (dejar en blanco si no
                    desea cambiarla):</label>
                <input type="password" name="password" id="password"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <!-- Botón de Guardar -->
            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-naranja hover:border-red-500 border transition  text-white  py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Guardar Cambios
                </button>
            </div>


        </form>
    </div>
@endsection
