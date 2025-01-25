@extends('layout')
@section('cuerpo')
    <div id="contenedor_confeti"
        class="flex flex-col justify-center text-base bg-[#a6d7ec] w-full  h-full font-sans text-color-text">
        <div class="text-center  justify-center mx-auto space-y-4 flex-col flex mt-10">
            <div class="font-semibold text-[25px] space-y-3">
                <p class="text-color-titulos-entrega">Tu Pedido ha sido registrado</p>
                <p class="text-naranja">¡Muchas Gracias!</p>
            </div>

            <p class="font-semibold "> Número de Pedido:</p>
            <span class="text-[60px] font-bold">{{ $pedido->id }}</span>
            <!-- Botón de Copiar Número -->
            <input type="text" id="number-to-copy" value="{{ $pedido->id }}" class="hidden">

            <a href="#" id="copy-button" class="underline text-[14px]} cursor-pointer">
                Copiar número
            </a>
            <p><span class="font-bold ">Tu nombre:</span><span> {{ $pedido->nombres }} </span></p>
            <p>
            <p class="font-bold mb-2">Tu pedido:</p>

            <div class="flex flex-col text-center">
                @if ($detalles->count() > 0)
                    @foreach ($detalles as $dt)
                        <div>
                            <p>{{ $dt->producto->descripcion }} {{ $dt->cantidad }}Un.</p>
                        </div>
                    @endforeach
                @endif

                @if ($entregaPromociones->count() > 0)
                    @foreach ($entregaPromociones as $et)
                        <div>
                            <p>{{ $et->producto }} {{ $et->cantidad }}Un. <span
                                    class="text-[14px] font-semibold">(¡Promoción Gratis!)</span></p>
                        </div>
                    @endforeach
                @endif


            </div>


            </p>
            <p><span class="text-naranja font-bold">Total:</span> S/{{ number_format($pedido->total, 2) }}</p>
            <div class="flex justify-center">
                <a href="{{ route('index.negocio', ['slug' => $empresa->dominio]) }}"
                    class=" bg-color-titulos-entrega text-white flex justify-center items-center w-[201px] h-[59px]  rounded-full hover:scale-110 transition-transform  transform "><i
                        class="fas fa-home mr-2"></i> Regresar al Inicio</a>

            </div>
        </div>
        <img src="{{ asset('imagenes/fondo.png') }}" alt="" class="mt-28">
    </div>
@endsection
