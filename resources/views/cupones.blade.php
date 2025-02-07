@extends('layout-cuenta')
@section('logica')
    <div class="container  p-2  w-full">
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
        <div class="bg-white shadow-md rounded-lg text-base font-sans overflow-hidden">
            <table class="w-full table-auto border-collapse">
                <!-- Encabezado (Se oculta en móviles) -->
                <thead class="hidden md:table-header-group">
                    <tr class="bg-color-titulos-entrega font-normal text-[15px] text-white">
                        <th class="px-4 py-2 border">Código</th>
                        <th class="px-4 py-2 border">Tipo</th>
                        <th class="px-4 py-2 border">Valor</th>
                        <th class="px-4 py-2 border">Límite de uso</th>
                        <th class="px-4 py-2 border">Expira en</th>
                        <th class="px-4 py-2 border">Acciones</th>
                    </tr>
                </thead>

                <!-- Cuerpo -->
                <tbody>
                    @foreach ($cupones as $cupon)
                        <tr class="block md:table-row md:text-center border-b md:border-0">
                            <td
                                class="block md:table-cell px-4 py-2 before:content-['Código:'] before:font-semibold before:block md:before:hidden">
                                {{ $cupon->codigo }}</td>
                            <td
                                class="block md:table-cell px-4 py-2 before:content-['Tipo:'] before:font-semibold before:block md:before:hidden">
                                {{ $cupon->tipo }}</td>
                            <td
                                class="block md:table-cell px-4 py-2 before:content-['Valor:'] before:font-semibold before:block md:before:hidden">
                                {{ $cupon->valor }}</td>
                            <td
                                class="block md:table-cell px-4 py-2 before:content-['Limites_de_uso:'] before:font-semibold before:block md:before:hidden">
                                {{ $cupon->limite_uso }}
                            </td>
                            <td
                                class="block md:table-cell px-4 py-2 before:content-['Expira_en:'] before:font-semibold before:block md:before:hidden">
                                {{ $cupon->expira_en }}</td>
                            <td class=" md:table-cell px-4 py-2 flex justify-center items-center space-x-2">
                                <form action="{{ route('cupones.eliminar', $cupon->id) }}" class="form_eliminar_cupon"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 text-white font-normal text-base px-4 py-2 rounded hover:bg-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
                <label for="limite_uso" class="block text-gray-700 font-medium">Límite de Uso en general</label>
                <input type="number" id="limite_uso" name="limite_uso" placeholder="cantidad que puede ser usado para este cupón entre los clientes"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2" min="1" required>
            </div>
            <div class="mb-4">
                <label for="limite_uso_cliente" class="block text-gray-700 font-medium">Límite de Uso por cliente</label>
                <input type="number" id="limite_uso_cliente" name="limite_uso_cliente"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2" min="1" required>
            </div>
            <div class="mb-4">
                <label for="expira_en" class="block text-gray-700 font-medium">Fecha de Expiración</label>
                <input type="date" id="expira_en" name="expira_en"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <input type="text" name="empresa_id" value="{{ $empresa->id }} " class="hidden">

            <button type="submit" class="bg-naranja text-white px-6 py-2 rounded transform hover:scale-105">Crear
                Cupón</button>
        </form>
    </div>
@endsection
