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

        .text-color-elegido {
            color: var(--button) !important;
            /* Cambia el color del texto */
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
            <h2 class="text-xl font-semibold font-cabin text-color-titulos-entrega mb-4">Mensajes Entrantes</h2>
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
        <div
            class="bg-gradient-to-tr from-color-text to-color-titulos-entrega  p-6 rounded-lg md:w-1/2 w-full max-h-full overflow-y-auto relative">
            <button id="closeModalmensajesdetalle" class="absolute top-2 right-2 text-red-500 text-3xl">&times;</button>
            <h2 class="text-xl font-cabin font-medium mb-4 text-white">Detalles del Pedido </h2>
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
    @guest
        <!--- Modal usuario no autenticado--->
        <div id="modal_usuario_no_autenticado"
            class="hidden fixed text-base inset-0  bg-gray-500 bg-opacity-50 z-40 mx-auto justify-center items-center">
            <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-full md:w-1/2">
                <div class="bg-yellow-100 border-l-4 text-center border-yellow-500 text-yellow-700 p-3 mb-4">
                    <p>Para disfrutar de una mejor experiencia y acceder a todas las funciones del sistema, regístrate o
                        inicia sesión.</p>
                    <div class="flex flex-col space-y-2 text-color-titulos-entrega justify-center items-center w-full mt-2">
                        <button id="no_au_btn_login"
                            class="custom-bg-button border rounded w-1/2 border-text-white text-white p-2 transform hover:scale-105 font-semibold">
                            Iniciar Sesión
                        </button>
                        <button id="no_au_btn_register"
                            class="border rounded w-1/2 border-color-titulos-entrega p-2 transform hover:scale-105 font-semibold">
                            Registrarse
                        </button>
                    </div>

                </div>

            </div>
        </div>

        <div id="contenedor_login_no_aut"
            class="fixed hidden inset-0  mx-auto overflow-y-auto h-full z-50 flex-col justify-center  items-center  w-full md:w-3/5 bg-white shadow-md">

            <!-- Formulario de inicio de sesión -->
            <div class="bg-white w-full p-10" id="formLogindiv">
                <p class="text-[22px] font-medium">Iniciar Sesión</p>

                <form id="formulario_login_no_aut" class="text-[16px] grid space-y-4 justify-self-center w-full"
                    action="{{ route('usuario.login-no-aut') }}" method="POST">
                    @csrf
                    <label for="telefono" class="">Numero Telefónico <span class="text-red-500">*</span></label>
                    <input required type="tel" placeholder="" name="telefono"
                        class="p-3  border-color-text border rounded-3xl focus:outline-none" autocomplete="off" />
                    <label for="password" class="">Contraseña <span class="text-red-500">*</span></label>
                    <input required type="password" placeholder="" name="password"
                        class=" border-color-text p-3 border rounded-3xl focus:outline-none" autocomplete="off" />
                    <center><button type="submit"
                            class="p-4  text-white text-[16px] rounded-2xl w-full bg-tarjetas transition duration-200">Acceder</button>
                    </center>
                </form>

                <div class="flex flex-col justify-start text-left mt-2 space-y-4">
                    <!-- Alineado a la izquierda -->
                    <button class="text-left text-color-text">¿Olvidaste tu Contraseña? <i
                            class="fas fa-arrow-right-long text-2xl  ml-2"></i></button>
                    <button id="botonregistrarsepanelcliente_no_au" class="text-left text-color-text">Crear Una Cuenta <i
                            class="fas fa-arrow-right-long text-2xl ml-2"></i></button>
                </div>
            </div>
            <!-- Formulario de registro usuario no autenticado (oculto por defecto) -->
            <div id="contenedor_registrarse_no_aut" class="hidden flex-col  bg-white w-full md:pt-[150px] pt-[250px] p-10 ">
                <p class="text-[22px] font-medium">Crear Una Cuenta</p>
                <form action="{{ route('crear.usuario') }}" method="POST" class="w-full h-full  mt-4 space-y-5 text-[16px]"
                    id="form_registrar_usuario_no_aut">

                    <!-- Teléfono -->
                    <label for="telefono" class="block text-color-text">Número de Celular
                        <span class="text-red-500">*</span></label>
                    <!-- Input de teléfono -->
                    <input  type="tel" maxlength="10" name="telefono" autocomplete="off"
                        class=" w-full border-color-text p-3 border rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-500 ">

                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block   text-color-text">Contraseña <span
                                class="text-red-500">*</span></label>
                        <input type="password" name="password" autocomplete="off"
                            class="w-full border-color-text p-3 border rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label for="nombres" class="block   text-color-text">Nombres <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nombres" autocomplete="off"
                            class="w-full border-color-text p-3 border rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Apellido -->
                    <div>
                        <label for="apellidos" class="block  font-medium text-color-texto">Apellidos <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="apellidos" autocomplete="off"
                            class="w-full border-color-text p-3 border rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Correo -->
                    <div>
                        <label for="correo" class="block  font-medium text-color-texto">Correo
                            Electronico</label>
                        <input type="email" name="correo" autocomplete="off"
                            class="w-full border-color-text p-3 border rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label for="direccion" class="block  font-medium text-color-texto">Dirección <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="direccion" placeholder="Ej. Av los laureles 250" autocomplete="off"
                            class="w-full border-color-text p-3 border rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>


                    <!-- Nota  -->
                    <div>
                        <label for="nota" class="block  font-medium text-color-texto">Referencia para
                            Pedidos <span class="text-red-500">*</span></label>
                        <textarea type="nota" name="nota" autocomplete="off"
                            placeholder="Ej. A espaldas del Coliseo Municipal |Casa color Celeste."
                            class="w-full h-[150px] border-color-text p-3 border rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <!-- Botón de crear cliente -->
                    <div class="mt-4 flex justify-center">
                        <button type="submit"
                            class="p-4 bg-tarjetas text-[16px]  text-white rounded-xl w-full transition">Registrar
                        </button>
                    </div>

                    <br>

                </form>
                <div class="flex flex-col justify-start text-left mt-2">
                    <!-- Alineado a la izquierda -->

                    <button id="boton_regresar_a_login_no_au" class="text-left text-color-text">
                        <i class="fas fa-arrow-left-long text-2xl mr-2 pb-2"></i>Regresar al Login
                    </button>
                </div>
                <br>
            </div>


        </div>
    @endguest


    <p id="id_usuario_autenticado"class="hidden">{{ Auth::user()?->id }}</p>
    <p id="ruta_actual">{{ request()->path() }}</p>
    @vite('resources/js/app.js')
    @yield('scripts')

</body>

</html>
