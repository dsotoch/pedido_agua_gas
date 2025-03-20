    <div class="relative flex bg-white  w-full ">
        <!-- Imagen al inicio -->
        <div class="grid justify-start md:pl-0 pl-1 w-1/2">
            <a href="{{ route('index.negocio', ['slug' => $empresa->dominio]) }}"> <img
                    src="{{ Storage::url($empresa->logo) }}" alt="Logo" class="w-[200px] h-auto pt-[10px] pb-[10px] pl-[10px] object-contain">
            </a>
        </div>

        <!-- Botón al final -->
        <div class="absolute top-0 right-0   flex  items-start pt-3 justify-end w-1/2  pr-2 h-[120px]">

            <button id="btn_acceder" class="md:mr-40 mr-4 text-[16px] leading-9 ">Acceder&nbsp;&nbsp;<i
                    class="fa-solid fa-right-to-bracket"></i></button>
            <div class="flex space-x-2">
                <button id="btn_favorito_dis" data-dominio="{{ $empresa->dominio }}" data-logo="{{ $empresa->logo }}"
                    data-nombre="{{ $empresa->nombre }}"
                    class="text-lg text-color-titulos-entrega hover:text-yellow-500" title="Agregar a Favoritos"><i
                        class="fas fa-star"></i></button>
                <button id="btn_predeterminado" data-dominio="{{ $empresa->dominio }}"
                    data-nombre="{{ $empresa->nombre }}"
                    class="text-lg text-color-titulos-entrega  hover:text-green-500"
                    title="Elegir como Distribuidora Predeterminada"><i class="fas fa-check-circle"></i></button>
            </div>

            <button id="openModalUsuario" class="text-white hidden  p-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1"
                    class="h-[35px] w-[35px]" x="0px" y="0px" viewBox="0 0 720 720" xml:space="preserve">
                    <rect fill="#293241" width="720" height="58.5"></rect>
                    <rect y="661.5" fill="#293241" width="720" height="58.5"></rect>
                    <rect y="330.75" fill="#293241" width="720" height="58.5"></rect>
                </svg>
            </button>
            @guest
                <div id="contenedor_login"
                class="absolute hidden text-wrap transform translate-x-full transition-transform duration-500 ease-in-out overflow-y-auto min-w-[350px] md:min-w-[600px] md:max-h-[80vh] max-h-[95vh] min-h-[250px] z-50   flex-col justify-start text-start  top-10  right-0 bg-white shadow-md">

                    <!-- Formulario de inicio de sesión -->
                    <div class="bg-white w-full p-10" id="formLogindiv">
                        <p class="text-[22px] font-medium">Iniciar Sesión</p>

                        <form id="formulario_login_pagina_principal"
                            class="text-[16px] grid space-y-4 justify-self-center w-full"
                            action="{{ route('usuario.login') }}" method="POST">
                            @csrf
                            <label for="telefono" class="">Numero Telefónico <span
                                    class="text-red-500">*</span></label>
                            <input required type="tel" placeholder="" name="telefono"
                                class="p-3  border-color-text border rounded-3xl focus:outline-none" autocomplete="off" />
                            <label for="password" class="">Contraseña <span class="text-red-500">*</span></label>
                            <input required type="password" placeholder="" name="password"
                                class=" border-color-text p-3 border rounded-3xl focus:outline-none" autocomplete="off" />
                            <div class="flex space-x-1"><label for="remember">Recuérdame</label><input type="checkbox" checked
                                    name="remember" class="w-6"></div>
                            <center><button type="submit"
                                    class="p-4  text-white text-[16px] rounded-2xl w-full bg-tarjetas transition duration-200">Acceder</button>
                            </center>
                        </form>

                        <div class="flex flex-col justify-start text-left mt-2 space-y-4">
                            <!-- Alineado a la izquierda -->
                            <button class="btn_pass text-left text-color-text">¿Olvidaste tu Contraseña? <i
                                    class="fas fa-arrow-right-long text-2xl  ml-2"></i></button>
                            <button id="botonregistrarsepanelcliente" class="text-left text-color-text">Crear Una Cuenta <i
                                    class="fas fa-arrow-right-long text-2xl ml-2"></i></button>
                        </div>
                    </div>
                    <!-- Formulario de registro (oculto por defecto) -->
                    <div id="contenedor_registrarse" class="hidden bg-white w-full  p-10 ">
                        <p class="text-[22px] font-medium">Crear Una Cuenta</p>
                        <form action="{{ route('crear.usuario') }}" method="POST" class="mt-4 space-y-5 text-[16px]"
                            id="form_registrar_usuario">

                       {{--     <!-- Dni -->
                            <label for="dni" class="block text-color-text">Número de Dni
                                <span class="text-red-500">*</span></label>
                            <!-- Input de Dni -->
                            <input type="number" maxlength="8" name="dni" autocomplete="off" required
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
                                <label for="correo" class="block w-full font-medium text-color-texto">Correo
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
            @auth
                <div id="contenedor_login"
                class="absolute hidden text-wrap transform translate-x-full transition-transform duration-500 ease-in-out overflow-y-auto min-w-[360px] md:min-w-[600px] md:max-h-[80vh] max-h-[95vh] min-h-[250px] z-50   flex-col justify-start text-start  top-10  right-0 bg-white shadow-md">

                    <!--Usuario ya Autenticado--->
                    <div class="absolute w-full">
                        <div class="pt-7 w-full mx-auto ">
                            <div class="text-center grid space-y-8">

                                <h1 class="font-cabin font-[500px] text-[25px] text-color-titulos-entrega">
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

        </div>
    </div>
