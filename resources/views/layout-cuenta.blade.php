@extends('layout')
@section('cuerpo')
    <div class="bg-color-dashboard w-full h-full flex">
        <div class="flex flex-col  bg-color-titulos-entrega text-white text-center  min-w-[202px] h-screen fixed ">
            <div class="mx-auto mt-[40px] mb-4"> <svg xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px" y="0px" width="100px" height="40px"
                    viewBox="0 0 200 80.25" xml:space="preserve">
                    <path fill="currentColor" class="text-naranja "
                        d="M200.081,80.25H0l7.918-6.022c16.287-13.424,32.324-27.22,44.832-38.703c0,0,31.84-31.72,54.356-34.443 c0,0,36.924-6.597,39.043,24.605c0,0-28.223-19.79-44.343,6.448c-3.373,5.961-9.622,27.799,15.196,31.777 c0,0,14.604,2.272,28.002-10.01c7.515-6.332,11.085-10.01,22.3-9.51c0,0,17.159,0.181,18.614,19.43c0,0-7.824-12.755-15.164-2.996 c0,0-4.269,8.261,5.538,13.254C186.099,79.076,200.081,80.25,200.081,80.25z">
                    </path>

                </svg></div>
            <p class="font-cabin text-[20px] leading-[30px] mt-1 text-wrap max-w-[202px]">
                {{ $usuario ? $usuario->persona->nombres : 'Sandra Maribel' }}</p>
            <p class="hidden" id="tipo_usuario">{{ $usuario ? $usuario->tipo : '' }}</p>
            <div class="grid font-sans text-[14px] leading-[14px] space-y-3 mt-4 mb-6">
                <a @if (($usuario && $usuario->tipo === 'admin') || ($usuario->tipo === 'repartidor' && $usuario->empresas()->exists())) href="{{ route('index.negocio', ['slug' => $usuario->empresas()->first()->dominio]) }}">Pedir</a>
            @else
            <a href="{{ route('index.negocio', ['slug' => $pedido && !empty($pedido->empresa->dominio) ? $pedido->empresa->dominio : 'default-slug']) }}">Pedir</a> @endif
                    <a href="{{ route('index') }}">Distribuidoras</a>
                <a href="{{ route('usuario.logout') }}">Cerrar sesión</a>
            </div>
            <div class="h-[1px] w-full bg-gray-500 mt-2"></div>

            <div class="space-y-0 mx-auto mt-6  text-start">
                @if ($usuario->tipo == 'admin')
                    <div
                        class=" {{ request()->routeIs('usuario.index') ? 'btn-active-mi-cuenta' : '' }} border border-transparent p-3 rounded-md hover:bg-naranja hover:border-red-500">
                        <a class="" href="{{ route('usuario.index') }}" id="btn_boton_dashboard"><i
                                class="fa-solid fa-gauge"></i>&nbsp;&nbsp;Dashboard</a>

                    </div>
                    <div
                        class="{{ request()->routeIs('empresa.index_pagos') ? 'btn-active-mi-cuenta' : '' }} border border-transparent p-3 rounded-md hover:bg-naranja hover:border-red-500">
                        <a class="" href="{{ route('empresa.index_pagos') }}" id="btn_boton_pagos"><i
                                class="fa-solid fa-cash-register"></i>&nbsp;&nbsp;Pagos del día</a>
                    </div>
                    <div
                        class="{{ request()->routeIs('empresa.clientes') ? 'btn-active-mi-cuenta' : '' }}  border border-transparent p-3 rounded-md hover:bg-naranja hover:border-red-500">
                        <a class="" href="{{ route('empresa.clientes') }}" id="btn_boton_clientes"><i
                                class="fa-regular fa-user"></i>&nbsp;&nbsp;Clientes</a>
                    </div>

                    <div
                        class="{{ request()->routeIs('empresa.productos') ? 'btn-active-mi-cuenta' : '' }} border border-transparent p-3 rounded-md hover:bg-naranja hover:border-red-500">
                        <a class="" href="{{ route('empresa.productos') }}" id="btn_boton_productos">
                            <i class="fa-solid fa-box"></i>&nbsp;&nbsp;Productos
                        </a>
                    </div>
                    <div
                        class="{{ request()->routeIs('empresa.usuarios') ? 'btn-active-mi-cuenta' : '' }} border border-transparent p-3 rounded-md hover:bg-naranja hover:border-red-500">
                        <a class="" href="{{ route('empresa.usuarios') }}" id="btn_boton_usuarios">
                            <i class="fa-solid fa-user-gear"></i>&nbsp;&nbsp;Usuarios
                        </a>
                    </div>
                    <div
                        class="{{ request()->routeIs('empresa.reportes') ? 'btn-active-mi-cuenta' : '' }}  border border-transparent p-3 rounded-md hover:bg-naranja hover:border-red-500">
                        <a class="" href="{{ route('empresa.reportes') }}" id="btn_boton_reportes"><i
                                class="fa-solid fa-chart-line"></i>&nbsp;&nbsp;Reportes</a>
                    </div>
                @endif
                @if ($usuario->tipo == 'cliente')
                    <div
                        class=" border {{ request()->routeIs('usuario.index') ? 'btn-active-mi-cuenta' : '' }} border-transparent p-3 rounded-md hover:bg-naranja hover:border-red-500">
                        <a class="" href="{{ route('usuario.index') }}" id="btn_boton_pedidos"><i
                                class="fa-solid fa-cart-shopping"></i>&nbsp;&nbsp;Mis pedidos</a>
                    </div>
                    <div
                        class="border border-transparent p-3 {{ request()->routeIs('empresa.datos') ? 'btn-active-mi-cuenta' : '' }} rounded-md hover:bg-naranja hover:border-red-500">
                        <a class="" href="{{ route('empresa.datos') }}" id="btn_boton_datos"><i
                                class="fa-solid fa-user-gear"></i>
                            &nbsp;&nbsp;Mis Datos</a>
                    </div>
                @endif
                @if ($usuario->tipo == 'repartidor')
                    <div
                        class=" {{ request()->routeIs('usuario.index') ? 'btn-active-mi-cuenta' : '' }} border border-transparent p-3 rounded-md hover:bg-naranja hover:border-red-500">
                        <a class="" href="{{ route('usuario.index') }}" id="btn_boton_dashboard"><i
                                class="fa-solid fa-gauge"></i>&nbsp;&nbsp;Dashboard</a>

                    </div>
                @endif
            </div>

        </div>
        <!----Cajas ---->
        <div class="w-full ml-[202px] pt-[40px] pl-[20px] pr-[20px] min-h-screen">
            @yield('logica')
        </div>




    </div>
@endsection
