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



</div>
