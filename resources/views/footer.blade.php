 <!----Footer--->
 <div class="bg-color-fondo-productos  text-color-text w-full text-center flex flex-col">
     <div class="flex mx-auto mt-[40px] mb-2">
         <a href="{{ $empresa->whatsapp }}"><img class="w-[40px] h-[50px] m-[10px]" src="{{ asset('imagenes/ws.svg') }}"
                 alt=""></a>
         <a href="{{ $empresa->facebook }}"><img src="{{ asset('imagenes/fa.svg') }} "class="w-[40px] h-[50px] m-[10px]"
                 alt=""></a>
         <a href="tel:{{ $empresa->telefono }}"><img class="w-[40px]  h-[50px] m-[10px]"
                 src="{{ asset('imagenes/tel.svg') }}" alt=""></a>
     </div>
     <div class="mb-[40px] mt-4">
         <p class="text-[15px]">Copyright © 2024 <span class="text-naranja font-bold">
                 <a href="https://entrega.pe">Entrega.pe</a></span> Todos los Derechos Reservados. |
             Desarrollo de Techub - Diego Soto</p>
     </div>

 </div>
