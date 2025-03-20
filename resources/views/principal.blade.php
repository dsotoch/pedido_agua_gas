@extends('layout')
@section('titulo')
    Entrega
@endsection
@section('cuerpo')
    <div>
        <div
            class="relative w-full flex bg-white shadow-lg items-center pl-2 pt-2 pb-2 pr-0 md:justify-normal justify-between">
            <div class="flex md:w-1/3 w-1/2 md:pl-28 z-50">
                <a href="/"><img src="{{ asset('imagenes/entrega.png') }}" alt=""
                        class="object-contain  ml-auto  w-[255px] h-[68px] "></a>
            </div>
            <div id="mensajeConexion"
                class="hidden fixed top-0 left-0 w-full bg-red-500 z-50 text-white text-center py-2 transition-opacity duration-500">
                No tienes conexión a internet
            </div>
            <div class="flex  md:w-3/5 w-1/2 justify-end pr-52 items-center z-50">
                <!-- Enlace de distribuidor -->
                <a href="#distribuidor"
                    class="text-[16px] z-50 hidden md:block relative after:content-[''] after:absolute after:left-0 after:-bottom-4 
             after:w-0 after:h-[2px] after:bg-naranja after:transition-all hover:after:w-full">
                    ¿Eres distribuidor?
                </a>
            </div>

            <div class="absolute top-0 right-0 h-[120px]  w-1/2 pt-6  pr-4">
                <!-- Contenedor del botón y el login -->
                <div class=" flex justify-end items-center w-full ">
                    <button id="btn_acceder"
                        class="hidden md:block text-[16px] leading-9 -translate-x-36 z-50">Acceder&nbsp;&nbsp;<i
                            class="fa-solid fa-right-to-bracket"></i></button>
                    <div id="openModalUsuario"
                        class="md:hidden block cursor-pointer w-10 h-10 flex flex-col justify-center items-center relative">
                        <span></span>
                        <span></span>
                        <span></span>
                        <p
                            class="x-icon  hidden opacity-0 scale-75 text-black text-3xl font-bold transition-all duration-500 ease-out">
                            X</p>

                    </div>



                    @auth
                        <div id="contenedor_login"
                            class="sticky hidden text-wrap transform translate-x-full transition-transform duration-500 ease-in-out overflow-y-auto min-w-[360px] md:min-w-[600px] md:max-h-[80vh] max-h-[95vh] min-h-[250px] z-50   flex-col justify-start text-start  top-20  right-0 bg-white shadow-md">

                            <!--Usuario ya Autenticado--->
                            <div class="absolute w-full">
                                <div class="pt-7 w-full mx-auto">
                                    <div class="text-center grid space-y-8  p-2">

                                        <h1 class="font-cabin font-[500px] text-wrap text-[25px] text-color-titulos-entrega">
                                            {{ $usuario->persona->nombres }}👋</h1>

                                        <div class="" data-id="4c7b7b9" data-element_type="widget"
                                            data-widget_type="button.default">
                                            <a class="bg-color-titulos-entrega p-4 rounded text-white text-base"
                                                href="{{ route('usuario.index') }}">
                                                <span class="">
                                                    <span class="">Tu zona privada</span>
                                                </span>
                                            </a>
                                        </div>
                                        <div>
                                            <form action="{{ route('usuario.logout') }}" method="get">
                                                <button type="submit" class="underline text-base">Cerrar sesión</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endauth
                    @guest
                        <div id="contenedor_login"
                            class="fixed hidden text-wrap transform translate-x-full transition-transform duration-500 ease-in-out overflow-y-auto min-w-[360px] md:min-w-[600px] md:max-h-[80vh] max-h-[95vh] min-h-[250px] z-50   flex-col justify-start text-start  top-20  right-0 bg-white shadow-md">
                            <!-- Aquí va todo tu contenido del formulario -->
                            <!-- Formulario de inicio de sesión -->
                            <div class="bg-white w-full p-10" id="formLogindiv">
                                <p class="text-[22px] font-medium">Iniciar Sesión</p>

                                <form id="formulario_login_pagina_principal"
                                    class="text-[16px] grid space-y-4 justify-self-center w-full"
                                    action="{{ route('usuario.login') }}" method="POST">
                                    <label for="telefono" class="">Numero Telefónico <span
                                            class="text-red-500">*</span></label>
                                    <input required type="tel" placeholder="" name="telefono"
                                        class="p-3  border-color-text border rounded-3xl focus:outline-none"
                                        autocomplete="off" />
                                    <label for="password" class="">Contraseña <span class="text-red-500">*</span></label>
                                    <input required type="password" placeholder="" name="password"
                                        class=" border-color-text p-3 border rounded-3xl focus:outline-none"
                                        autocomplete="off" />
                                    <div class="flex space-x-1"><label for="remember">Recuérdame</label><input type="checkbox"
                                            checked name="remember" class="w-6"></div>

                                    <center><button type="submit"
                                            class="p-4  text-white text-[16px] rounded-2xl w-full bg-tarjetas transition duration-200">Acceder</button>
                                    </center>
                                </form>

                                <div class="flex flex-col justify-start text-left mt-2 space-y-4">
                                    <!-- Alineado a la izquierda -->
                                    <button class="btn_pass text-left text-color-text">¿Olvidaste tu Contraseña? <i
                                            class="fas fa-arrow-right-long text-2xl  ml-2"></i></button>
                                    <button id="botonregistrarsepanelcliente" class="text-left text-color-text">Crear Una Cuenta
                                        <i class="fas fa-arrow-right-long text-2xl ml-2"></i></button>
                                </div>
                            </div>
                            <!-- Formulario de registro (oculto por defecto) -->
                            <div id="contenedor_registrarse" class="hidden bg-white w-full  p-10 ">
                                <p class="text-[22px] font-medium">Crear Una Cuenta</p>
                                <form action="{{ route('crear.usuario') }}" method="POST" class="mt-4 space-y-5 text-[16px]"
                                    id="form_registrar_usuario">

                                    {{--                                    <!-- Dni -->
                                    <label for="dni" class="block text-color-text">Número de Dni
                                        <span class="text-red-500">*</span></label>
                                    <!-- Input de Dni -->
                                    <input id="dni" type="number" maxlength="8" name="dni" autocomplete="off"
                                        required
                                        class=" w-full border-color-text p-3 border rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-500 ">
                                    --}}
                                    <!-- Teléfono -->
                                    <label for="telefono" class="block text-color-text">Número de Celular
                                        <span class="text-red-500">*</span></label>
                                    <!-- Input de teléfono -->
                                    <input id="telefono" type="tel" maxlength="10" name="telefono" autocomplete="off"
                                        required
                                        class=" w-full border-color-text p-3 border rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-500 ">

                                    <!-- Contraseña -->
                                    <div>
                                        <label for="password" class="block   text-color-text">Contraseña <span
                                                class="text-red-500">*</span></label>
                                        <input type="password" name="password" autocomplete="off" required
                                            class="w-full border-color-text p-3 border rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>

                                    <!-- Nombre -->
                                    <div>
                                        <label for="nombres" class="block   text-color-text">Nombres <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="nombres" autocomplete="off" required
                                            class="w-full border-color-text p-3 border rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>

                                    <!-- Apellido -->
                                    <div>
                                        <label for="apellidos" class="block  font-medium text-color-texto">Apellidos <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="apellidos" autocomplete="off" required
                                            class="w-full border-color-text p-3 border rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>

                                    <!-- Correo -->
                                    <div>
                                        <label for="correo" class="block  font-medium text-color-texto">Correo
                                            Electronico <span class="text-color-text">(Opcional)</span></label>
                                        <input type="email" name="correo" autocomplete="off"
                                            class="w-full border-color-text p-3 border rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>

                                    <!-- Dirección -->
                                    <div>
                                        <label for="direccion" class="block  font-medium text-color-texto">Dirección <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="direccion" placeholder="Ej. Av los laureles 250"
                                            autocomplete="off" required
                                            class="w-full border-color-text p-3 border rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>


                                    <!-- Nota  -->
                                    <div>
                                        <label for="nota" class="block  font-medium text-color-texto">Referencia para
                                            Pedidos <span class="text-red-500">*</span></label>
                                        <textarea type="nota" name="nota" autocomplete="off" required
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

                                    <button id="boton_regresar_a_login" class="text-left text-color-text">
                                        <i class="fas fa-arrow-left-long text-2xl mr-2 pb-2"></i>Regresar al Login
                                    </button>
                                </div>
                                <br>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>


        </div>
        <div class="md:flex grid bg-gradient-to-br from-secundario to-principal p-10 items-center">
            <!-- Contenedor de textos -->
            <div class="grid  text-white text-justify  items-center md:w-1/2 w-full gap-4 md:p-12 p-2">
                <h3 class="font-[500px] font-cabin  text-[35px] leading-tight">
                    Pide tu agua o gas a domicilio con solo unos clics.
                </h3>
                <p class="font-[400px] text-[17px] font-sans leading-[28px]">
                    <span class="font-semibold ">Olvídate de las llamadas y las esperas en línea.</span> Encuentra los
                    distribuidores más cercanos, selecciona tus productos y paga al recibir.
                </p>
                <div class="flex bg-black  items-center border rounded-[20px] overflow-hidden shadow-sm">
                    <input type="search" id="buscador" placeholder="Buscar por distribuidor o ciudad..."
                        class="p-4 pl-4 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 text-black placeholder-black">
                    <button disabled class="p-4 bg-naranja text-white hover:bg-naranja focus:outline-none">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="relative " id="contenedorResultados">
                    <div id="empresas" class="absolute flex flex-col  top-0 w-full bg-white rounded-md">

                    </div>
                    <div class="absolute top-0 shadow-2xl w-full p-2 hidden" id="contenedor_bars">
                        <!-- Aseguramos que el div con audioBars se posicione dentro del contexto relativo -->
                        <div id="audioBars"
                            class="absolute flex top-0 left-0   items-center space-x-1 rounded-md p-3 justify-center  w-full bg-white">
                            <div class="w-2 bg-naranja h-8"></div>
                            <div class="w-2 bg-naranja h-12"></div>
                            <div class="w-2 bg-naranja h-6"></div>
                            <div class="w-2 bg-naranja h-10"></div>
                            <div class="w-2 bg-naranja h-7"></div>
                        </div>

                    </div>
                </div>





            </div>
            <!-- Contenedor de imagen -->
            <div class="md:w-1/2 w-full flex justify-center p-4">
                <img src="{{ asset('imagenes/nautik.png') }}" alt="" class="max-w-full h-auto">
            </div>
        </div>
        <div class=" md:w-[600px] w-full mx-auto p-4 text-justify">
            <!-- Título: ¿Qué es Entrega.pe? -->
            <h2 class="text-[35px] font-medium text-center  text-color-titulos-entrega  mb-6">¿Qué es Entrega.pe?</h2>
            <p class="text-base  leading-[35px] mb-8 text-color-text">
                <span class="text-naranja font-bold">Entrega.pe</span> es una plataforma web que hace que pedir agua
                o
                gas
                a domicilio
                sea fácil y rápido. Con solo unos clics, podrás seleccionar tus productos, registrar tus pedidos al instante
                y
                recibirlos
                directamente en la puerta de tu casa.
            </p>

            <!-- Título: ¿Cómo funciona el sistema? -->
            <h2 class="text-[35px] font-medium text-center  text-color-titulos-entrega mb-6">¿Cómo funciona el sistema?
            </h2>
            <p class="text-base  leading-[35px] mb-8 text-color-text">
                En <span class="text-naranja font-bold">Entrega.pe</span>, conectamos a los usuarios con sus
                distribuidores de confianza
                a través de una plataforma sencilla y eficiente. Así es cómo funciona el proceso:
            </p>
            <ol class="list-decimal ml-6 md:ml-12 text-justify space-y-4">
                <li class="text-base  leading-[35px]  text-color-text">
                    <span class="font-semibold">Realizas tu pedido:</span> El usuario ingresa al enlace de su distribuidor
                    preferido y
                    selecciona los productos que necesita. Al hacer clic en <span class="font-semibold">«Enviar
                        pedido»</span>,
                    el sistema de
                    <span class="font-bold text-naranja">Entrega.pe</span> registra la solicitud de manera
                    instantánea y
                    la envía
                    en tiempo real al dashboard de la distribuidora.
                </li>
                <li class="leading-[35px] text-base text-color-text">
                    <span class="font-semibold">Gestión eficiente:</span> El equipo de la distribuidora recibe y visualiza
                    los
                    pedidos al
                    instante, sin necesidad de hacer registros manuales.
                </li>
                <li class="leading-[35px]  text-color-text text-base">
                    <span class="font-semibold">Entrega rápida y segura:</span> Todo se maneja de forma organizada y
                    eficiente,
                    evitando
                    errores y olvidos, asegurando que el proceso de entrega sea rápido y sin demoras.
                </li>
            </ol>

            <!-- Título: ¿Por qué elegir Entrega.pe? -->
            <h2 class="text-[35px] font-medium text-center  text-color-titulos-entrega mt-12 mb-6">¿Por qué elegir
                Entrega.pe?</h2>
            <ol class="list-disc ml-6 md:ml-12 text-justify space-y-4">
                <li class="leading-[35px]  text-color-text text-base">
                    <span class="font-semibold">Sin llamadas ni esperas:</span> Realiza tu pedido al instante, sin tener
                    que
                    llamar ni esperar
                    a que alguien conteste. Solo te registras una vez y puedes hacer tus pedidos con solo dos clics. ¡Tan
                    fácil
                    como suena!
                </li>
                <li class="leading-[35px]  text-color-text text-base">
                    <span class="font-semibold">Paga al recibir:</span> Solo pagas cuando recibes tu pedido, sin
                    preocupaciones
                    ni sorpresas.
                </li>
                <li class="leading-[35px]  text-color-text text-base">
                    <span class="font-semibold">Gratis para los usuarios:</span> Usar <span
                        class="font-bold text-naranja">Entrega.pe</span>
                    no tiene ningún costo. Realiza tus pedidos y recibe tus entregas sin pagar por el uso de la plataforma.
                    Solo
                    pagas por
                    el agua o gas que pides.
                </li>
                <li class="leading-[35px]  text-color-text text-base">
                    <span class="font-semibold">Promociones activadas por los distribuidores:</span> Los distribuidores
                    tienen
                    la opción de
                    ofrecer <span class="font-semibold">cupones de descuento</span> y promociones como <span
                        class="font-semibold">el décimo
                        bidón gratis</span>, que son automatizados; sin embargo, estas promociones solo estarán disponibles
                    si
                    el distribuidor
                    decide activarlas.
                </li>
                <li class="leading-[35px]  text-color-text text-base">
                    <span class="font-semibold">Futuras promociones de Entrega.pe:</span> Todos los usuarios activos en el
                    sistema podrán
                    participar en sorteos mensuales de cupones y gift cards cuando la plataforma gane popularidad. ¡Más
                    beneficios para ti!
                </li>
            </ol>

            <p class="leading-[35px]  text-color-text text-base mt-8">
                Con <span class="font-bold text-naranja">Entrega.pe</span>, tu comodidad es lo primero. Solo tienes
                que
                elegir lo que
                necesitas y nosotros nos encargamos del resto.
            </p>
        </div>

        <section id="distribuidor" class="bg-blue-50  w-full mx-auto p-3">
            <div class="mx-auto  md:w-[600px] text-justify w-full">
                <h2 class="text-4xl m-6 text-naranja text-center font-semibold">¿Eres distribuidor?</h2>
                <p class="text-base text-color-text leading-[35px]"><span class="text-naranja font-bold">Entrega.pe</span>
                    te ofrece
                    un sistema completo para gestionar pedidos online de agua y gas, eliminando el
                    estrés de las llamadas paralelas. Recibe notificaciones en tiempo real, genera reportes detallados
                    al
                    instante y evita los errores y olvidos que suelen ocurrir cuando intentas atender a todos al mismo
                    tiempo. Mejora la experiencia de tus clientes y optimiza tu negocio con tecnología fácil y
                    accesible.
                </p>
                <br>
                <a href="https://entrega.pe"><button
                        class=" p-4 pr-12 pl-12 rounded-xl bg-naranja w-full text-base text-white">Descubre cómo Funciona
                    </button></a>

            </div>
        </section>

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
                    <a href="https://entrega.pe">Entrega.pe</a></span> Todos los Derechos Reservados.</p>
        </div>

    </div>
@endsection
