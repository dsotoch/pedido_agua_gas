@extends('layout-cuenta')
@section('logica')
    <div class="container mx-auto p-2">
        <!-- Mensajes de error o éxito -->
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-600 p-4 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('mensaje'))
            <div class="mb-4 bg-green-100 text-green-600 p-4 rounded">
                {{ session('mensaje') }}
            </div>
        @endif

        <!-- Tabla de cupones -->
        <h2 class="text-2xl font-cabin font-semibold mb-4 text-color-titulos-entrega">Lista de Cupones</h2>
        <div class="overflow-x-auto bg-white shadow-md rounded-lg text-base font-sans">
            <table class="min-w-full table-auto" id="tabla_cupones">
                <thead>
                    <tr class="bg-color-titulos-entrega font-normal text-[15px] font-sans text-white">
                        <th class="px-4 py-2 border">Código</th>
                        <th class="px-4 py-2 border">Tipo</th>
                        <th class="px-4 py-2 border">Valor</th>
                        <th class="px-4 py-2 border">Límite de uso</th>
                        <th class="px-4 py-2 border">Expira en</th>
                        <th class="px-4 py-2 border">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cupones as $cupon)
                        <tr class="border-b text-center">
                            <td class="px-4 py-1">{{ $cupon->codigo }}</td>
                            <td class="px-4 py-1">{{ $cupon->tipo }}</td>
                            <td class="px-4 py-1">{{ $cupon->valor }}</td>
                            <td class="px-4 py-1">{{ $cupon->limite_uso }}</td>
                            <td class="px-4 py-1">{{ $cupon->expira_en }}</td>
                            <td class="px-4 py-1 flex space-x-2 justify-center items-center">
                                <!-- Eliminar cupon -->
                                <form action="{{ route('cupones.eliminar', $cupon->id) }}" class="form_eliminar_cupon"
                                    method="POST">
                                    <button type="submit"
                                        class="bg-red-500 text-white font-normal text-base px-4 py-2 rounded hover:bg-red-700"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Formulario para crear un nuevo cupón -->
        <h2 class="text-2xl font-cabin text-color-titulos-entrega font-semibold mt-8 mb-4">Crear Nuevo Cupón</h2>
        <form action="{{ route('cupones.crear') }}" id="form_nuevo_cupon" method="POST"
            class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="codigo" class="block text-gray-700 font-medium">Código</label>
                <input type="text" id="codigo" name="codigo"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
            </div>

            <div class="mb-4">
                <label for="tipo" class="block text-gray-700 font-medium">Tipo de Descuento</label>
                <select name="tipo" id="tipo" class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                    required>
                    <option value="porcentaje">Porcentaje</option>
                    <option value="fijo">Fijo</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="valor" class="block text-gray-700 font-medium">Valor</label>
                <input type="number" id="valor" name="valor"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2" required min="0">
            </div>

            <div class="mb-4">
                <label for="limite_uso" class="block text-gray-700 font-medium">Límite de Uso</label>
                <input type="number" id="limite_uso" name="limite_uso"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2" min="1">
            </div>

            <div class="mb-4">
                <label for="expira_en" class="block text-gray-700 font-medium">Fecha de Expiración</label>
                <input type="date" id="expira_en" name="expira_en"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>
            <input type="text" name="empresa_id" value="{{ $empresa->id }} " class="hidden">

            <button type="submit" class="bg-naranja text-white px-6 py-2 rounded transform hover:scale-105">Crear
                Cupón</button>
        </form>
    </div>
@endsection
