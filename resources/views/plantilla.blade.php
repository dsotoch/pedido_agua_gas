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
            <div id="contener_producto_item" class="flex flex-col w-full pl-[10px] pr-[10px]">
                <div class="flex flex-col mx-auto h-auto text-color-text  items-center justify-center rounded-3xl  bg-transparent text-center w-full md:w-[520px]"
                    data-index="">
                        <!---Productos Antiguos-->
                        <div class="w-full p-1  bg-white mt-[20px] rounded-[10px] flex ">
                            <div class="w-[79px] h-[145px] my-auto flex items-center">
                                <img src="{{ asset('imagenes/agua.jpg' ) }}" alt="" class=" object-contain">
                            </div>
                            <div class="flex flex-col w-full   ml-4 space-y-2 padre_productos ">
                                <div class="md:flex grid items-center ">
                                    <div class="w-full  md:w-1/2 pt-4  text-start">
                                        <p class="producto_descripcion  text-base ">
                                            Agua Mineral 20 Litros.</p>
                                    </div>
                                    <div
                                        class="flex w-full  md:pl-0  pr-[10px] justify-between  md:w-1/2 items-center space-x-2 md:space-x-1  pt-4">
                                        <div class="w-1/2">
                                            <p class="text-start md:text-center"> <span
                                                    class="text-[15px]  precioprincipal">S/{{ number_format(25.00) }}</span>
                                            </p>
                                        </div>
                                        <div class="item-container p-1  opacity-80 justify-center space-x-2  border-color-text rounded-[3px] flex w-[76px] h-[37px] max-h-[37px] items-center  border">
                                            <p class="hidden precionormal">
                                                {{ number_format(25.00) }}
                                            </p>
                                           
                                            <!-- Botón de disminución -->
                                            <button disabled
                                                class=" btn-producto-menos w-[20px] h-[20px] text-center items-center  opacity-80 text-color-text  ">
                                                <i class="fas fa-minus"></i>
                                            </button>
    
                                            <!-- Input de cantidad con flex-grow y max-w-xs -->
                                            <div class="w-auto flex items-center text-center">
                                                <p name="cantidad" readonly
                                                    class="cantidad text-[15px] w-full  text-color-text opacity-80  p-0 m-0 text-center">
                                                    0</p>
    
                                            </div>
    
                                            <!-- Botón de aumento -->
                                            <button disabled
                                                class="btn-producto-mas  w-[20px] h-[20px] text-center items-center opacity-80  text-color-text  ">
                                                <i class="fas fa-plus "></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full text-start">
                                    <p class="text-color-text text-[13px]">San Luis Purificada 95%.</p>
                                </div>
                                <br>
                                <p class="text-start text-color-text text-[13px]">Te faltan <strong>9</strong> bidones para tu decimo gratis.</p>
    
                               
    
                            </div>
                        </div>
    
                </div>
                <div class="flex flex-col  md:w-[500px] mx-auto  mt-[40px] w-full  bg-white rounded-[10px] p-[10px]">
                    <!-- Mensaje y botón de despliegue -->
                    <div class="flex items-center justify-center space-x-2 cursor-pointer mt-[10px] mb-[10px]"
                        id="toggleCupon">
                        <p class="text-base  text-color-titulos-entrega font-sans">¿Tienes un cupón?</p>
                        <i class="fa-solid fa-chevron-down text-color-titulos-entrega transition-transform duration-300"
                            id="iconoFlecha"></i>
                    </div>
    
                    <!-- Input para cupón (oculto por defecto) -->
                    <div id="cuponForm" class="hidden  mx-auto  mt-[10px] mb-[10px]">
                        <div class="flex text-center w-1/2 space-x-2">
                            <input type="text" id="codigoCupon" placeholder="Ingresa tu codigo"
                                class="flex-1 p-2 text-center border border-gray-400 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button id="btnAplicarCupon"
                                class="px-4 py-2 bg-tarjetas text-white font-semibold rounded-full ">
                                Aplicar
                            </button>
                        </div>
                    </div>
                </div>
                <div id="contenedor-total"
                    class="mt-[40px]  sticky bottom-0 contenedor-total grid  w-full bg-color-fondo-productos justify-items-center ">
                    <div id="contenedor_cupones" class="hidden  flex-col w-full text-center items-center justify-center">
                        <center>
                            <div class="w-full">
                                <p class="text-[15px] grid grid-cols-2 ">
                                    Cupón Aplicado:
                                    <input id="span_cupon" readonly class="font-bold bg-transparent text-start " />
                                </p>
                            </div>
                        </center>
                        <center>
                            <div class="  w-full mt-2">
                                <p class="text-[15px] grid grid-cols-2 ">
                                    Descuento:
                                    <input id="descuento" readonly class="font-bold bg-transparent text-start " />
                                </p>
                            </div>
                        </center>
                    </div>
    
                    <input type="text" readonly
                        class="total text-[18px]   w-[328px] h-[55px] rounded-md focus:outline-none bg-transparent font-bold  text-center  pb-[10px] "
                        value="Total: S/0.00">
                    <button type="button" id="botonp"
                        class=" mb-[10px] disabled:opacity-50 opacity-100  font-normal text-[18px]  w-[328px] h-[55px] rounded-md bg-black text-white ">
                        Siguiente
                        <i class=" fas fa-arrow-right-long text-2xl ml-2 "></i></button>
                 
    
                </div>
            </div>
    
    
                <form method="POST" action="{{ route('empresa.config', ['id' => $empresa->id]) }}"
                    class="w-3/12 container p-4 bg-tarjetas font-sans">
                    @csrf
                    <h2 class="text-xl m-4 text-white">Configurar Plantilla</h2>
                    <br>
    
    
                   
                    <!-- Selector de color para el botón -->
                    <label for="button-color" class="block text-white font-normal mb-2">Ingresa el color del
                        botón (Formato Hexadecimal)</label>
                    <input type="text" name="button-color" id="button-color" value="#000000" class="cursor-pointer p-3 mb-4 w-full rounded-md">
    
                    <input type="text" value="{{ $empresa->id }}" readonly class="text-transparent bg-transparent">
                    <button id="volvercolores" type="button"
                        class="p-4 rounded  w-full hover:bg-gray-400 text-white text-base border border-white">Resetear</button>
    
                    <button type="submit" class="p-4 rounded  text-white w-full mt-2 text-base bg-naranja">Guardar
                        Configuraciones</button>
                </form>
        </div>




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
