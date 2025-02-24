@extends('layout')
@section('cuerpo')
<div class="w-full flex bg-white shadow-lg items-center pl-2 pt-2 pb-2 pr-0 md:justify-normal justify-between">
    <div class="flex md:w-1/3 w-1/2 md:pl-28">
        <a href="/"><img src="{{ asset('imagenes/entrega.png') }}" alt=""
                class="object-contain  ml-auto  w-[255px] h-[68px] "></a>
    </div>
    <div class="flex md:w-3/5 w-1/2 justify-end items-center space-x-4 md:translate-x-20 translate-x-4">
       
        <!-- Contenedor del botón y el login -->
        <div class="relative group flex items-center w-1/3">
            <button id="btn_acceder"
                class="hidden md:block text-[16px] leading-9 translate-x-8">Acceder&nbsp;&nbsp;<i
                    class="fa-solid fa-right-to-bracket"></i></button>
            <button id="openModalUsuario" class="text-white  block md:hidden p-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1"
                    class="h-[35px] w-[35px]" x="0px" y="0px" viewBox="0 0 720 720" xml:space="preserve">
                    <rect fill="#293241" width="720" height="58.5"></rect>
                    <rect y="661.5" fill="#293241" width="720" height="58.5"></rect>
                    <rect y="330.75" fill="#293241" width="720" height="58.5"></rect>
                </svg>
            </button>
            <div id="contenedor_login"
                class="absolute hidden overflow-y-auto min-w-[400px] md:min-w-[600px] max-h-[80vh] min-h-[400px] z-50 group-hover:flex h-auto flex-col justify-start text-start top-12 right-0  bg-white shadow-md">
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
                        <center><button type="submit"
                                class="p-4  text-white text-[16px] rounded-2xl w-full bg-tarjetas transition duration-200">Acceder</button>
                        </center>
                    </form>

                    <div class="flex flex-col justify-start text-left mt-2 space-y-4">
                        <!-- Alineado a la izquierda -->
                        <button class="text-left text-color-text">¿Olvidaste tu Contraseña? <i
                                class="fas fa-arrow-right-long text-2xl  ml-2"></i></button>
                        <button id="botonregistrarsepanelcliente" class="text-left text-color-text">Crear Una Cuenta
                            <i class="fas fa-arrow-right-long text-2xl ml-2"></i></button>
                    </div>
                </div>
                <!-- Formulario de registro (oculto por defecto) -->
                <div id="contenedor_registrarse" class="hidden bg-white w-full  p-10 ">
                    <p class="text-[22px] font-medium">Crear Una Cuenta</p>
                    <form action="{{ route('crear.usuario') }}" method="POST"
                        class="mt-4 space-y-5 text-[16px]" id="form_registrar_usuario">

                        <!-- Teléfono -->
                        <label for="telefono" class="block text-color-text">Número de Celular
                            <span class="text-red-500">*</span></label>
                        <!-- Input de teléfono -->
                        <input id="telefono" type="tel" maxlength="10" name="telefono" autocomplete="off"
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
                                Electronico <span class="text-red-500">*</span></label>
                            <input type="email" name="correo" autocomplete="off" 
                                class="w-full border-color-text p-3 border rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label for="direccion" class="block  font-medium text-color-texto">Dirección <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="direccion" placeholder="Ej. Av los laureles 250"
                                autocomplete="off"
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

                        <button id="boton_regresar_a_login" class="text-left text-color-text">
                            <i class="fas fa-arrow-left-long text-2xl mr-2 pb-2"></i>Regresar al Login
                        </button>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>


</div>
    <div class="grid bg-gradient-to-br from-secundario to-principal p-10 items-center">
        @if (session()->has('administrador'))
            <div id="mensaje" class="text-center p-4 m-4 border border-white text-white text-base">
                {{ session('administrador') }}
            </div>
        @endif
        @if ($errors->has('mensaje'))
            <div id="mensaje" class=" text-center p-4 m-4 bg-red-500 text-white text-base">
                {{ $errors->first('mensaje') }}
            </div>
        @endif
        @if (session()->has('plantilla'))
            <div id="notificacion" class="grid gap-4 p-6 m-4 border border-white text-white rounded-md shadow-lg">
                <!-- Mensaje principal -->
                <p class="text-base font-semibold">
                    {{ session('plantilla') ?? 'Configuración guardada correctamente.' }}
                </p>

                <!-- Información de la URL de la distribuidora -->
                <p class="text-base">
                    La URL de tu distribuidora es:
                    <span class="text-lg font-bold underline">{{ url($empresa->dominio) }}</span>
                </p>

                <!-- Botón para ir a la página -->
                <a href="{{ url($empresa->dominio) }}"
                    class="inline-block px-6 py-3 mt-4 text-center text-white bg-orange-500 rounded-md hover:bg-orange-600">
                    Ir a la página de mi distribuidora
                </a>
            </div>
        @endif
        <!-----PLANTILLA DE LA EMPRESA --->
        <div class="flex">

            <div
            class="pt-[40px] pb-[40px] bg-color-fondo-productos flex flex-row  w-full justify-stretch items-center space-x-2 ">


            <div
                class="producto-item  flex flex-col mx-auto bg-white text-color-text  items-center justify-center rounded-3xl pt-4 pb-16 bg-transparent text-center w-[450px] shadow-sm 
                    ">
                <div class="flex">
                    <button class="p-4 text-xl w-2/6 hover:text-principal" id="btnatrasproducto"><i
                            class="fa-solid fa-backward "></i></button>
                    <div>
                        <p
                            class="producto_descripcion text-[18px] w-full
                            font-semibold mt-5 mb-[11px] leading-9">
                            Agua Bidon 20 L.</p>
                        <div class="item-container flex  space-x-2 w-full  mx-auto justify-center">

                            <!-- Botón de disminución -->
                            <button disabled
                                class="btn-producto-menos border w-[50px] h-[49px] text-center  border-color-text hover:bg-color-text rounded-full  text-color-text hover:border-red-600 hover:text-white">
                                <i class="fas fa-minus"></i>
                            </button>

                            <!-- Input de cantidad con flex-grow y max-w-xs -->
                            <input type="number" name="cantidad" value="1" readonly
                                class="cantidad flex-grow text-[16px] max-w-[100px] h-[48px] p-2 border border-color-text rounded-xl border-text-color text-center">

                            <!-- Botón de aumento -->
                            <button disabled
                                class="btn-producto-mas border w-[50px] h-[49px] text-center border-color-text hover:bg-color-text rounded-full  text-color-text hover:border-red-600 hover:text-white">
                                <i class="fas fa-plus "></i>
                            </button>
                        </div>
                        <p class=" mb-5 text-[13px] p-[14px] w-full">Precio Unitario: <span
                                class="text-[13px]  precioprincipal">S/10.00</span></p>
                    </div>
                    <button class="p-4 text-xl w-2/6 hover:text-principal" id="btnsiguienteproducto"><i
                            class="fa-solid fa-forward"></i></button>
                </div>
                <span class="bidones-faltan text-[14px] max-h-[55px] h-[55px]">¡Te faltan <span
                        class="resaltar-numero font-bold text-[16px]">8</span> bidones para tu Noveno
                    GRATIS!</span>
                <div id="contenedor-total" class="contenedor-total grid content-center justify-items-center ">
                    <input type="text" readonly
                        class="total text-[18px] border w-[328px] h-[55px] rounded-md focus:outline-none bg-secundario text-white font-bold  text-center pt-[10px] pb-[10px] "
                        value="Total: S/10.00">
                    <button  id="botonp"
                        class=" font-normal text-[18px]  w-[328px] h-[55px] rounded-md custom-bg-button  text-white mt-2">
                        Siguiente
                        <i class="fas fa-arrow-right-long text-2xl ml-2 "></i></button>

                </div>


            </div>



        </div>


            <form method="POST" action="{{ route('empresa.config', ['id' => $empresa->id]) }}"
                class="w-3/12 container p-4 bg-tarjetas font-sans">
                @csrf
                <h2 class="text-xl m-4 text-white">Configurar Plantilla</h2>
                <br>


               
                <!-- Selector de color para el botón -->
                <label for="button-color" class="block text-white font-normal mb-2">Selecciona el color del
                    botón</label>
                <input type="color" name="button-color" id="button-color" class="cursor-pointer p-3 mb-4 w-full rounded-md">

                <input type="text" value="{{ $empresa->id }}" readonly class="text-transparent bg-transparent">
                <button id="volvercolores" type="button"
                    class="p-4 rounded  w-full hover:bg-gray-400 text-white text-base border border-white">Resetear</button>

                <button type="submit" class="p-4 rounded  text-white w-full mt-2 text-base bg-naranja">Guardar
                    Configuraciones</button>
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
