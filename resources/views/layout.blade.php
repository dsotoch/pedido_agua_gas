<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="token" id="token" content="{{ csrf_token() }}">
    <title>Pidelo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;500;600;700&display=swap" rel="stylesheet">



    <style>
        :root {
            --footer: {{ $colors['footer'] ?? '#000000' }};
            --button: {{ $colors['button'] ?? '#000000' }};
            --nav: {{ $colors['nav'] ?? '#000000' }};

        }
        .text-color-elegido{
            color: var(--button) !important; /* Cambia el color del texto */
        }
        .custom-bg-footer {
            background-color: var(--footer) !important;
        }

        .custom-bg-button {
            background-color: var(--button) !important;
        }

        .custom-bg-nav {
            background-color: var(--nav) !important;
        }
    </style>
    @vite('resources/css/app.css')

    @yield('estilos')
</head>

<body class="font-sans">
    @yield('cuerpo')
    <!-- Modal -->
    <div id="messagesModal" class="hidden fixed inset-0 bg-black bg-opacity-50  justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg md:w-1/2 w-full max-h-full overflow-y-auto relative">
            <button id="closeModalmensajes" class="absolute top-2 right-2 text-red-500 text-3xl">&times;</button>
            <h2 class="text-xl font-medium mb-4">Mensajes Entrantes</h2>
            <div id="messagesContainer" class="space-y-2">
                <!-- Aquí se mostrarán los mensajes -->
            </div>
        </div>
    </div>
    <div id="modalmensajespedidoasignado"
        class="w-full fixed inset-0 z-50 hidden justify-center items-center overflow-y-auto">
        <div class="bg-white p-6 rounded-lg md:w-1/2 w-full max-h-full overflow-y-auto relative">
            <button id="closeModalmensajesAsignacion"
                class="absolute top-2 right-2 text-red-500 text-3xl">&times;</button>
            <h2 class="text-xl font-medium mb-4">Nuevo Pedido Asignado</h2>
            <div id="pedidoAsignado"
                class="pedido bg-tarjetas text-white shadow-md rounded-lg p-4 border border-gray-200">
            </div>
        </div>

    </div>
    <div id="modalmensajespedidodetalle"
        class="shadow-2xl shadow-principal hidden  w-full fixed inset-0 z-50  justify-center items-center overflow-y-auto">
        <div class="bg-tarjetas p-6 rounded-lg md:w-1/2 w-full max-h-full overflow-y-auto relative">
            <button id="closeModalmensajesdetalle" class="absolute top-2 right-2 text-red-500 text-3xl">&times;</button>
            <h2 class="text-xl font-medium mb-4 text-white">Detalles del Pedido </h2>
            <div id="pedidoAsignadoDetalle"
                class="pedido bg-white text-gray-800 shadow-md rounded-lg p-4 border border-gray-200">

            </div>
            <label for="repartidor" class="block text-normal font-medium text-white m-2">Seleccionar
                Repartidor</label>
            <select name="selectrepartidores" id="selectrepartidores" required
                class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200 focus:outline-none">
                <option value="" disabled selected>-- Seleccionar --
                </option>
            </select>
        </div>

    </div>
    <!-- Modal forma de pagos reportes -->
    <div id="modalformadepago"
        class="hidden  fixed inset-0 bg-gray-500 bg-opacity-50 z-50  justify-center items-center">
        <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-normal mb-4">Selecciona el Método de Pago</h2>

            <select id="metodoPago" class="mb-4 px-4 py-2 border rounded-md w-full">
                <option value="yape">Yape</option>
                <option value="efectivo">Efectivo</option>
            </select>

            <div class="flex justify-end space-x-4">
                <button id="confirmarBtn"
                    class="px-4 py-2 bg-principal hover:bg-principalhover text-white rounded">Confirmar</button>
                <button id="cerrarModalBtn"
                    class="px-4 py-2 bg-secundario hover:bg-secundariohover text-white rounded">Cerrar</button>
            </div>
        </div>
    </div>

    <p id="id_usuario_autenticado"class="hidden">{{ Auth::user()?->id }}</p>

    @vite('resources/js/app.js')
    @yield('scripts')

</body>

</html>
