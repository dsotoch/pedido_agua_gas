<div class="mt-10 bg-white w-full">
    <div class="grid mx-auto md:w-[600px] w-full md:max-w-[600px]" id="principal">
        <p class="text-tarjetas text-center leading-[45px] text-[35px] font-medium font-cabin">{{ $empresa->nombre }}</p>
        <br>
        <div class=" w-full grid text-justify text-color-text font-medium p-2">
            <p class="text-center text-[16px] mb-8 font-bold ">
                ¡Gracias a todos nuestros clientes por confiar en nosotros!
            </p>
            <p class="font-normal text[16px] leading-[35px] text-color-text"><span
                    class="text-color-elegido font-bold">{{ $empresa->nombre }}</span> siempre llevando un producto de
                calidad a la puerta de tu hogar. Ahora
                podrás realizar tus pedidos con solo dos clics. <span
                    class="text-color-elegido font-bold">{{ $empresa->nombre }}</span> , siempre pensando en mejorar la
                experiencia de
                nuestros clientes.
            </p>
            <p class="text-wrap font-normal mt-2 text-base leading-[35px] text-color-text">{!! nl2br(e($empresa->descripcion)) !!}</p>
            <div class="relative z-40 w-full  mx-auto  overflow-hidden mb-[40px] mt-16">
                <!-- Contenedor de las diapositivas -->
                <div id="slider" class="flex transition-transform duration-500">
                    @foreach ($imagenes as $item)
                        <div class="w-full flex-shrink-0">
                            <img src="{{ asset('storage/' . $item) }}" class="object-contain"
                                alt="Producto de la empresa">
                        </div>
                    @endforeach

                </div>
                <!-- Controles -->
                <button id="prev"
                    class="border hover:border-red-500 absolute left-0 top-1/2 transform -translate-y-1/2 bg-[#111111] text-white w-[50px] h-[50px]">
                    <i class="fas fa-angle-left text-[30px]"></i>

                </button>
                <button id="next"
                    class="border hover:border-red-500 absolute right-0 top-1/2 transform -translate-y-1/2 bg-[#111111] text-white   w-[50px] h-[50px]">
                    <i class="fas fa-angle-right text-[30px]"></i>

                </button>
            </div>
        </div>
    </div>
    @auth
        @if ($usuario->tipo == 'cliente' && $fueraHorario)
            <div id="anuncio"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 font-sans">
                <div class="bg-white p-6 rounded-lg shadow-lg w-3/4 md:w-1/2 text-center">
                    <h2 class="text-xl font-semibold text-red-600">📢 Aviso Importante</h2>
                    <p class="text-color-titulos-entrega mt-2">
                        Actualmente estamos fuera de nuestro horario de atención.
                        Los pedidos realizados en este momento serán procesados el siguiente día hábil.
                    </p>
                    <p class="text-center text-color-titulos-entrega">
                        {{ date('h:i A', strtotime($empresa->hora_inicio)) }} -
                        {{ date('h:i A', strtotime($empresa->hora_fin)) }}
                    </p>

                    <button
                        onclick="document.getElementById('anuncio').classList.remove('flex');document.getElementById('anuncio').classList.add('hidden');"
                        class="mt-4 px-4 py-3 border shadow-xl border-red-600 text-red-600 rounded text-base font-semibold transform hover:scale-105">
                        Entendido
                    </button>
                </div>
            </div>
        @endif
    @endauth



</div>
