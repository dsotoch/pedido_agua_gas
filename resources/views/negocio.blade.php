@extends('layout')
@section('titulo')
{{ $empresa->nombre }} - Entrega
@endsection
@section('cuerpo')
    @include('nav')
    @include('producto')
    @include('descripcion')
    @include('footer')

    <div>
       
       
        <!-- Modal Crear Cliente -->
        <div id="modalCrearCliente" role="dialog" aria-modal="true"
            class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 w-full h-full  justify-center items-center z-50 overflow-auto">
            <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 sm:w-96" style="max-height: 100%; overflow-y: auto;">
                <div class="flex justify-between mb-2">
                    <!-- Título -->
                    <h2 class="text-xl font-semibold text-center mb-4">Finalizar Pedido</h2>
                    <!-- Botón para cerrar -->
                    <button id="closeModalCliente" class="top-2 right-2 text-red-500 font-bold text-3xl">&times;</button>
                </div>
                <hr>
                <hr>
            </div>
        </div>
      
    </div>
    </div>
@endsection
